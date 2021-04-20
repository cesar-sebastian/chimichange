<?php

namespace App\Service;

use GuzzleHttp\Exception\BadResponseException;

class FixerService
{
    private $endpoint;
    private $accessKey;

    public function __construct()
    {
        $this->endpoint = $_ENV["FIXER_ENDPOINT"];
        $this->accessKey = $_ENV["FIXER_ACCESS_KEY"];
    }

    public function getSymbols()
    {
        $url = 'symbols';
        $data = $this->callServices('GET', $url);

        if ($data && $data->getBody() !== null)
        {
            $data = json_decode($data->getBody());
            return $data;
        }

        return null;
    }

    public function getRates()
    {
        $url = 'latest';

        $data = $this->callServices('GET', $url);

        if ($data && $data->getBody() !== null)
        {
            return json_decode($data->getBody(), true);
        }

        return null;
    }

    private function callServices($method, $url, $data = null)
    {
        $url = $this->endpoint.$url.'?access_key='.$this->accessKey;

        try {
            $client = new \GuzzleHttp\Client();

            $requestData = [];

            if (isset($data)) {
                $requestData['json'] = $data;
            }

            $res = $client->request($method, $url, $requestData);
            return $res;
        } catch (BadResponseException $e) {
            // TODO - Log
            return null;
        }
    }

}
