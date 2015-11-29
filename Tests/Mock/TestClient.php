<?php

namespace Sc\RestClient\Tests\Mock;

use GuzzleHttp\Client as HttpClient;
use Sc\RestClient\Client\Client;

class TestClient extends Client
{
    protected $http_client;

    public function setHttpClient(HttpClient $client)
    {
        $this->http_client = $client;
    }

    public function getHttpClient()
    {
        return $this->http_client;
    }
}
