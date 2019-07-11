<?php declare(strict_types = 1);

namespace App\Model\Ares;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

final class AresClient
{
    /** @var Client */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://wwwinfo.mfcr.cz/cgi-bin/ares/',
        ]);
    }

    public function getOrganizationByCrn(string $crn): ResponseInterface
    {
        return $this->get(sprintf('darv_std.cgi?ico=%s', $crn));
    }

    private function get(string $uri, array $options = []): ResponseInterface
    {
        return $this->client->get($uri, $options);
    }
}
