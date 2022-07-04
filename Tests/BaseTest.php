<?php declare(strict_types=1);

namespace Sc\Tests;

use PHPUnit\Framework\TestCase;
use Sc\RestClient\ResponseParser\JsonResponseParser;
use Sc\Tests\Mock\TestClient;
use Sc\Tests\Mock\TestHttpClient;

abstract class BaseTest extends TestCase
{
    public const ENDPOINT = 'http://api.foo.bar/';
    public const RESOURCE = 'zombies';
    public const STUB = [
        'name' => 'Shaun',
        'age' => 31,
    ];

    protected TestClient $rest_client;

    protected TestHttpClient $http_client;

    protected function setUp(): void
    {
        $this->http_client = new TestHttpClient();
        $this->rest_client = new TestClient(self::ENDPOINT, new JsonResponseParser(), $this->http_client);
    }
}
