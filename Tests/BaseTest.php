<?php declare(strict_types=1);

namespace Sc\Tests;

use PHPUnit\Framework\TestCase;
use Sc\RestClient\ResponseParser\JsonResponseParser;
use Sc\Tests\Mock\TestClient;
use Sc\Tests\Mock\TestHttpClient;

abstract class BaseTest extends TestCase
{
    const ENDPOINT = 'http://api.foo.bar/';
    const RESOURCE = 'zombies';
    const STUB = [
        'name' => 'Shaun',
        'age' => 31,
    ];

    protected TestClient $rest_client;

    protected TestHttpClient $http_client;

    protected function setUp(): void
    {
        $this->http_client = new TestHttpClient();
        $this->rest_client = new TestClient(self::ENDPOINT, new JsonResponseParser());
        $this->rest_client->setHttpClient($this->http_client);
    }
}
