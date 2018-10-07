<?php declare(strict_types = 1);

namespace App\Model\Ares;

use App\Entity\Organization\Organization;
use App\Repository\Geo\DistrictRepository;
use App\Repository\Geo\DistrictZipCodeRepository;
use App\Repository\Geo\MunicipalityRepository;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
final class AresToOrganizationResolver
{
    /** @var DistrictRepository */
    private $districtRepository;

    /** @var DistrictZipCodeRepository */
    private $zipCodeRepository;

    /** @var MunicipalityRepository */
    private $municipalityRepository;

    public function __construct(
        DistrictRepository $districtRepository,
        DistrictZipCodeRepository $zipCodeRepository,
        MunicipalityRepository $municipalityRepository
    ) {
        $this->districtRepository = $districtRepository;
        $this->zipCodeRepository = $zipCodeRepository;
        $this->municipalityRepository = $municipalityRepository;
    }

    public function resolve(
        string $crn,
        Organization $organization,
        ResponseInterface $response
    ): void
    {
        $crawler = $this->getCrawler($response);

        $organization->setCrn($crn);

        $this->resolveName($crawler, $organization);
        $this->resolveFoundedAt($crawler, $organization);
        $this->resolveLocality($crawler, $organization);
    }

    private function getCrawler(ResponseInterface $response): Crawler
    {
        $xml = $response->getBody()->getContents();

        $crawler = new Crawler($xml);
        $crawler = $crawler->filter('are|Odpoved are|Zaznam');

        if ($crawler->count() === 0) {
            throw new \RuntimeException('');
        }

        return $crawler;
    }

    private function resolveName(Crawler $crawler, Organization $organization): void
    {
        $organization->setName(
            $crawler->filter('are|Obchodni_firma')->text()
        );
    }

    private function resolveFoundedAt(Crawler $crawler, Organization $organization): void
    {
        try {
            $foundedAt = \DateTime::createFromFormat('Y-m-d', $crawler->filter('are|Datum_vzniku')->text());
        } catch (\Exception $e) {
            $foundedAt = false;
        }

        if ($foundedAt) {
            $organization->setFoundedAt($foundedAt);
        }
    }

    private function resolveLocality(Crawler $crawler, Organization $organization): void
    {
        $addressNode = $crawler->filter('are|Identifikace are|Adresa_ARES');

        try {
            $municipalityTitle = $addressNode->filter('dtt|Nazev_obce')->text();
        } catch (\Exception $e) {
            $municipalityTitle = null;
        }

        try {
            $districtTitle = $addressNode->filter('dtt|Nazev_okresu')->text();
        } catch (\Exception $e) {
            $districtTitle = null;
        }

        try {
            $zipTitle = $addressNode->filter('udt|PSC')->text();
        } catch (\Exception $e) {
            $zipTitle = null;
        }

        try {
            $cityPartTitle = $addressNode->filter('dtt|Nazev_casti_obce')->text();
        } catch (\Exception $exception) {
            $cityPartTitle = null;
        }

        try {
            $streetTitle = $addressNode->filter('dtt|Nazev_ulice')->text();
        } catch (\Exception $e) {
            $streetTitle = null;
        }

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

        $district = $districtTitle ? $this->districtRepository->findByTitle($districtTitle) : null;
        $municipality = $municipalityTitle ? $this->municipalityRepository->findByTitles($municipalityTitle, $districtTitle) : null;
        $region = $district ? $district->getRegion() : null;
        $zip = $zipTitle && $cityPartTitle ? $this->zipCodeRepository->findByZipAndCityPart($zipTitle, $cityPartTitle) : null;

        $houseNumber = trim(trim(sprintf('%s/%s', $streetNumber, $streetNumber2)), '/');

        if ($streetTitle) {
            $address = sprintf('%s %s, %s', $streetTitle, $houseNumber, $zipTitle);
        } else {
            $address = sprintf('%s %s, %s', $municipalityTitle, $houseNumber, $zipTitle);
        }

        if ($municipality) {
            $address .= ' ' . $municipality->getTitle();
        }

        $address = trim(trim(trim($address), ','));

        $organization->setRegion($region);
        $organization->setDistrict($district);
        $organization->setMunicipality($municipality);
        $organization->setAddress(!empty($address) ? $address : null);
        $organization->setZip($zip);
    }
}
