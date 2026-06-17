<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class InseeIrlService
{
    private const DATA_URL = 'https://api.insee.fr/series/BDM/V1/data/SERIES_BDM/001515333';

    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    public function getHistory(): array
    {
        $response = $this->httpClient->request('GET', self::DATA_URL);
        $xml = new \SimpleXMLElement($response->getContent());

        $xml->registerXPathNamespace('message', 'http://www.sdmx.org/resources/sdmxml/schemas/v2_1/message');
        $xml->registerXPathNamespace('ss', 'http://www.sdmx.org/resources/sdmxml/schemas/v2_1/data/structurespecific');

        $obs = $xml->xpath('//Obs');

        $irls = [];
        foreach ($obs as $o) {
            $irls[] = [
                'period' => (string) $o['TIME_PERIOD'],
                'value'  => (float)  $o['OBS_VALUE'],
            ];
        }

        usort($irls, fn($a, $b) => strcmp($b['period'], $a['period']));

        return $irls;
    }
}
