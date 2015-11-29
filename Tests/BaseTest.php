<?php

use Sc\RestClient\ResponseParser\JsonResponseParser;
use Sc\RestClient\Tests\Mock\TestClient;

require_once __DIR__.'/Mock/TestClient.php';
require_once __DIR__.'/Mock/TestHttpClient.php';

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    const ENDPOINT = 'http://api.foo.bar/';
    const RESOURSE = 'zombies';
    const STUB = [
        'name' => 'Shaun',
        'age' => 31,
    ];

    /** @var TestClient */
    protected $rest_client;

    /** @var TestHttpClient */
    protected $http_client;

    protected function setUp()
    {
        $this->http_client = new TestHttpClient();
        $this->rest_client = new TestClient(self::ENDPOINT, new JsonResponseParser());
        $this->rest_client->setHttpClient($this->http_client);
    }
}