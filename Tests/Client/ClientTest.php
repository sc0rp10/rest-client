<?php declare(strict_types=1);

namespace Sc\Tests\Client;

use GuzzleHttp\Psr7\Response;
use Sc\RestClient\Client\Exception\ResourceNotFoundException;
use Sc\Tests\BaseTest;

class ClientTest extends BaseTest
{
    public function testGetAll(): void
    {
        $data = [
            [
                'name' => 'Shaun',
                'age' => 31,
            ],
            [
                'name' => 'Frankenshtein',
                'age' => 500,
            ],
        ];

        $response = new Response(200, [], json_encode($data));

        $this->http_client->addResponse($response);

        $result = $this->rest_client->getAll(self::RESOURCE);

        $req = $this->http_client->getLastRequest();

        $this->assertEquals(self::ENDPOINT.self::RESOURCE.'/', (string)$req->getUri());
        $this->assertEquals($data, $result);
        $this->assertEquals('GET', $req->getMethod());
    }

    public function testGet(): void
    {
        $data = self::STUB;

        $response = new Response(200, [], json_encode($data));

        $this->http_client->addResponse($response);

        $result = $this->rest_client->get(self::RESOURCE, 1234);

        $req = $this->http_client->getLastRequest();

        $this->assertEquals(self::ENDPOINT.self::RESOURCE.'/1234', (string)$req->getUri());
        $this->assertEquals($data, $result);
        $this->assertEquals('GET', $req->getMethod());
    }

    public function testPost(): void
    {
        $data = self::STUB;

        $response = new Response(302, [
            'Location' => '/'.self::RESOURCE.'/1234',
        ]);

        $this->http_client->addResponse($response);

        $response = new Response(200, [], json_encode($data));

        $this->http_client->addResponse($response);

        $result = $this->rest_client->create(self::RESOURCE, $data);

        $original = $this->http_client->getLastRequest();
        $redirected = $this->http_client->getLastRequest();

        $this->assertEquals(self::ENDPOINT.self::RESOURCE.'/1234', (string)$redirected->getUri());
        $this->assertEquals('GET', $redirected->getMethod());

        $this->assertEquals(self::ENDPOINT.self::RESOURCE.'/', (string)$original->getUri());
        $this->assertEquals($data, $result);
        $this->assertEquals('POST', $original->getMethod());
    }

    public function testPut(): void
    {
        $data = self::STUB;

        $response = new Response(302, [
            'Location' => '/'.self::RESOURCE.'/1234',
        ]);

        $this->http_client->addResponse($response);

        $response = new Response(200, [], json_encode($data));

        $this->http_client->addResponse($response);

        $result = $this->rest_client->update(self::RESOURCE, 1234, $data);

        $original = $this->http_client->getLastRequest();
        $redirected = $this->http_client->getLastRequest();

        $this->assertEquals(self::ENDPOINT.self::RESOURCE.'/1234', (string)$redirected->getUri());
        $this->assertEquals('GET', $redirected->getMethod());

        $this->assertEquals(self::ENDPOINT.self::RESOURCE.'/1234', (string)$original->getUri());
        $this->assertEquals($data, $result);
        $this->assertEquals('PUT', $original->getMethod());
    }

    public function testPatch(): void
    {
        $data = [
            'age' => 32,
        ];

        $response = new Response(302, [
            'Location' => '/'.self::RESOURCE.'/1234',
        ]);

        $this->http_client->addResponse($response);

        $response = new Response(200, [], json_encode($data));

        $this->http_client->addResponse($response);

        $result = $this->rest_client->update(self::RESOURCE, 1234, $data, true);

        $original = $this->http_client->getLastRequest();
        $redirected = $this->http_client->getLastRequest();

        $this->assertEquals(self::ENDPOINT.self::RESOURCE.'/1234', (string)$redirected->getUri());
        $this->assertEquals('GET', $redirected->getMethod());


        $this->assertEquals(self::ENDPOINT.self::RESOURCE.'/1234', (string)$original->getUri());
        $this->assertEquals($data, $result);
        $this->assertEquals('PATCH', $original->getMethod());
    }

    public function testDelete(): void
    {
        $response = new Response(204);

        $this->http_client->addResponse($response);

        $this->rest_client->delete(self::RESOURCE, 1234);

        $req = $this->http_client->getLastRequest();

        $this->assertEquals(self::ENDPOINT.self::RESOURCE.'/1234', (string)$req->getUri());
        $this->assertEquals('DELETE', $req->getMethod());
    }

    public function testExceptionOnInvalidResponse(): void
    {
        $data = self::STUB;

        $response = new Response(404, [], json_encode($data));

        $this->http_client->addResponse($response);

        try {
            $this->rest_client->get(self::RESOURCE, 1234);
            $this->fail();
        } catch (ResourceNotFoundException) {
            $this->assertTrue(true);
        }
    }
}
