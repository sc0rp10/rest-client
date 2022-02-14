<?php declare(strict_types=1);

namespace Sc\Tests\Client;

use GuzzleHttp\Psr7\Response;
use Sc\RestClient\AuthenticationProvider\HeaderProvider;
use Sc\RestClient\AuthenticationProvider\QueryParameterProvider;
use Sc\Tests\BaseTest;

class AuthTest extends BaseTest
{
    public function testQueryAuthenticatorWithGet(): void
    {
        $this->rest_client->useAuthenticator(new QueryParameterProvider('api_key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);
        $this->rest_client->get(self::RESOURCE, 1234);
        $req = $this->http_client->getLastRequest();
        $this->assertStringEndsWith('api_key=foo-bar', $req->getUri()->getQuery());
    }

    public function testQueryAuthenticatorWithGetAll(): void
    {
        $this->rest_client->useAuthenticator(new QueryParameterProvider('api_key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);

        $this->rest_client->getAll(self::RESOURCE);
        $req = $this->http_client->getLastRequest();
        $this->assertStringEndsWith('api_key=foo-bar', $req->getUri()->getQuery());

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);

        $this->rest_client->getAll(self::RESOURCE, ['bar' => 'baz']);
        $req = $this->http_client->getLastRequest();
        $this->assertStringEndsWith('api_key=foo-bar', $req->getUri()->getQuery());
        $this->assertGreaterThan(-1, strpos($req->getUri()->getQuery(), 'bar=baz'));
    }

    public function testQueryAuthenticatorWithGetAllAndAnotherQueryParameters(): void
    {
        $this->rest_client->useAuthenticator(new QueryParameterProvider('api_key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);

        $this->rest_client->getAll(self::RESOURCE, ['bar' => 'baz']);
        $req = $this->http_client->getLastRequest();
        $this->assertStringEndsWith('api_key=foo-bar', $req->getUri()->getQuery());
        $this->assertGreaterThan(-1, strpos($req->getUri()->getQuery(), 'bar=baz'));
    }

    public function testQueryAuthenticatorWithPost(): void
    {
        $this->rest_client->useAuthenticator(new QueryParameterProvider('api_key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);
        $this->rest_client->create(self::RESOURCE, self::STUB);
        $req = $this->http_client->getLastRequest();
        $this->assertStringEndsWith('api_key=foo-bar', $req->getUri()->getQuery());
    }

    public function testQueryAuthenticatorWithPut(): void
    {
        $this->rest_client->useAuthenticator(new QueryParameterProvider('api_key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);
        $this->rest_client->update(self::RESOURCE, 1234, self::STUB);
        $req = $this->http_client->getLastRequest();
        $this->assertStringEndsWith('api_key=foo-bar', $req->getUri()->getQuery());
    }

    public function testQueryAuthenticatorWithPatch(): void
    {
        $this->rest_client->useAuthenticator(new QueryParameterProvider('api_key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);
        $this->rest_client->update(self::RESOURCE, 1234, [
            'age' => 32,
        ]);
        $req = $this->http_client->getLastRequest();
        $this->assertStringEndsWith('api_key=foo-bar', $req->getUri()->getQuery());
    }

    public function testQueryAuthenticatorWithDelete(): void
    {
        $this->rest_client->useAuthenticator(new QueryParameterProvider('api_key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);
        $this->rest_client->delete(self::RESOURCE, 1234);
        $req = $this->http_client->getLastRequest();
        $this->assertStringEndsWith('api_key=foo-bar', $req->getUri()->getQuery());
    }

    public function testHeaderAuthenticatorWithGet(): void
    {
        $this->rest_client->useAuthenticator(new HeaderProvider('X-Api-Key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);
        $this->rest_client->get(self::RESOURCE, 1234);
        $req = $this->http_client->getLastRequest();
        $this->assertTrue($req->hasHeader('X-Api-Key'));
        $this->assertEquals('foo-bar', $req->getHeaderLine('X-Api-Key'));
    }

    public function testHeaderAuthenticatorWithGetAll(): void
    {
        $this->rest_client->useAuthenticator(new HeaderProvider('X-Api-Key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);

        $this->rest_client->getAll(self::RESOURCE);
        $req = $this->http_client->getLastRequest();
        $this->assertTrue($req->hasHeader('X-Api-Key'));
        $this->assertEquals('foo-bar', $req->getHeaderLine('X-Api-Key'));
    }

    public function testHeaderAuthenticatorWithPost(): void
    {
        $this->rest_client->useAuthenticator(new HeaderProvider('X-Api-Key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);
        $this->rest_client->create(self::RESOURCE, self::STUB);
        $req = $this->http_client->getLastRequest();
        $this->assertTrue($req->hasHeader('X-Api-Key'));
        $this->assertEquals('foo-bar', $req->getHeaderLine('X-Api-Key'));
    }

    public function testHeaderAuthenticatorWithPut(): void
    {
        $this->rest_client->useAuthenticator(new HeaderProvider('X-Api-Key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);
        $this->rest_client->update(self::RESOURCE, 1234, self::STUB);
        $req = $this->http_client->getLastRequest();
        $this->assertTrue($req->hasHeader('X-Api-Key'));
        $this->assertEquals('foo-bar', $req->getHeaderLine('X-Api-Key'));
    }

    public function testHeaderAuthenticatorWithPatch(): void
    {
        $this->rest_client->useAuthenticator(new HeaderProvider('X-Api-Key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);
        $this->rest_client->update(self::RESOURCE, 1234, [
            'age' => 32,
        ]);
        $req = $this->http_client->getLastRequest();
        $this->assertTrue($req->hasHeader('X-Api-Key'));
        $this->assertEquals('foo-bar', $req->getHeaderLine('X-Api-Key'));
    }

    public function testHeaderAuthenticatorWithDelete(): void
    {
        $this->rest_client->useAuthenticator(new HeaderProvider('X-Api-Key', 'foo-bar'));

        $response = new Response(200, [], json_encode(self::STUB));
        $this->http_client->addResponse($response);
        $this->rest_client->delete(self::RESOURCE, 1234);
        $req = $this->http_client->getLastRequest();
        $this->assertTrue($req->hasHeader('X-Api-Key'));
        $this->assertEquals('foo-bar', $req->getHeaderLine('X-Api-Key'));
    }
}
