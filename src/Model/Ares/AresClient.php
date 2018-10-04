<?php declare(strict_types = 1);

namespace App\Model\Ares;

use App\Entity\Organization\Organization;
use App\Repository\Geo\DistrictRepository;
use App\Repository\Geo\DistrictZipCodeRepository;
use App\Repository\Geo\MunicipalityRepository;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
final class AresClient
{
    /** @var DistrictRepository */
    private $districtRepository;

    /** @var MunicipalityRepository */
    private $municipalityRepository;

    /** @var DistrictZipCodeRepository */
    private $zipCodeRepository;

    /** @var Client */
    private $client;

    public function __construct(
        DistrictRepository $districtRepository,
        MunicipalityRepository $municipalityRepository,
        DistrictZipCodeRepository $zipCodeRepository
    ) {
        $this->districtRepository = $districtRepository;
        $this->municipalityRepository = $municipalityRepository;
        $this->zipCodeRepository = $zipCodeRepository;
        $this->client = new Client([
            'base_uri' => 'http://wwwinfo.mfcr.cz/cgi-bin/ares/',
        ]);
    }

    public function resolveOrganizationByCrn(string $crn, Organization $organization): void
    {
        $response = $this->get(
            sprintf(
                'darv_std.cgi?ico=%s',
                $crn
            )
        );

        $this->parseCompanyInformation(
            $crn,
            $response->getBody()->getContents(),
            $organization
        );
    }

    private function get(string $uri, array $options = []): ResponseInterface
    {
        return $this->client->get($uri, $options);
    }

    private function parseCompanyInformation(
        string $crn,
        string $xmlResponse,
        Organization $organization
    ): void
    {
        $xml = simplexml_load_string($xmlResponse);

        if ($xml === false) {
            throw new \RuntimeException('ARES is not available or returned an empty response.');
        }

        $crawler = new Crawler($xmlResponse);
        $crawler = $crawler->filter('are|Odpoved are|Zaznam');

        if ($crawler->count() === 0) {
            throw new \RuntimeException('');
        }

        $name = $crawler->filter('are|Obchodni_firma')->text();
        $foundedAt = \DateTime::createFromFormat('Y-m-d', $crawler->filter('are|Datum_vzniku')->text());
        $addressNode = $crawler->filter('are|Identifikace are|Adresa_ARES');
        // $addressId = $addressNode->filter('dtt|ID_adresy')->text();
        // $stateCode = $addressNode->filter('dtt|Kod_statu')->text();
        $districtTitle = $addressNode->filter('dtt|Nazev_okresu')->text();
        $municipalityTitle = $addressNode->filter('dtt|Nazev_obce')->text();
        $street = $addressNode->filter('dtt|Nazev_ulice')->text();

        try {
            $streetNumber = $addressNode->filter('dtt|Cislo_domovni')->text();
        } catch (\InvalidArgumentException $e) {
            $streetNumber = '';
        }

        try {
            $streetNumber2 = $addressNode->filter('dtt|Cislo_orientacni')->text();
        } catch (\Exception $e) {
            $streetNumber2 = '';
        }

        $zipTitle = $addressNode->filter('udt|PSC')->text();

        $district = $this->districtRepository->findByTitle($districtTitle);
        $municipality = $this->municipalityRepository->findByTitles($municipalityTitle, $districtTitle);
        $region = $district ? $district->getRegion() : null;

        /**
         * TODO: resolve ZIP!
         */
        // $zip = $this->zipCodeRepository->findByZip();

        $houseNumber = trim(trim(sprintf('%s/%s', $streetNumber, $streetNumber2)), '/');
        $address = sprintf('%s %s, %s', $street, $houseNumber, $zipTitle);
        if ($municipality) {
            $address .= ' ' . $municipality->getTitle();
        }

        $organization->setName($name);
        $organization->setCrn($crn);
        $organization->setRegion($region);
        $organization->setDistrict($district);
        $organization->setMunicipality($municipality);
        $organization->setAddress($address);
        if ($foundedAt) {
            $organization->setFoundedAt($foundedAt);
        }
    }
}
