<?php declare(strict_types=1);

namespace Sc\Tests\Mock;

use GuzzleHttp\Client as HttpClient;
use Sc\RestClient\Client\Client;

class TestClient extends Client
{
    protected HttpClient $http_client;

    public function setHttpClient(HttpClient $client)
    {
        $this->http_client = $client;
    }

    public function getHttpClient(): HttpClient
    {
        return $this->http_client;
    }
}
