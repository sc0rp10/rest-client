<?php

/**
 * This file is part of sc/rest-client
 *
 * © Konstantin Zamyakin <dev@weblab.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc\RestClient\Client;

use GuzzleHttp\ClientInterface as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Sc\RestClient\AuthenticationProvider\AuthenticationProviderInterface;
use Sc\RestClient\Client\Exception\RequestFailedException;
use Sc\RestClient\Client\Exception\ResourceNotFoundException;
use Sc\RestClient\RequestSigner\RequestSignerInterface;
use Sc\RestClient\ResponseParser\ResponseParserInterface;

class Client implements ClientInterface
{
    public final const METHOD_GET = 'GET';
    public final const METHOD_POST = 'POST';
    public final const METHOD_PUT = 'PUT';
    public final const METHOD_PATCH = 'PATCH';
    public final const METHOD_DELETE = 'DELETE';

    private ?RequestSignerInterface $request_signer = null;
    private ?AuthenticationProviderInterface $auth_provider = null;

    public function __construct(
        private readonly string $endpoint,
        private readonly ResponseParserInterface $responseParser,
        private readonly HttpClient $httpClient,
    ) {
    }

    public function useRequestSigner(RequestSignerInterface $signer): self
    {
        $this->request_signer = $signer;

        return $this;
    }

    public function useAuthenticator(AuthenticationProviderInterface $auth_provider): self
    {
        $this->auth_provider = $auth_provider;

        return $this;
    }

    public function get(string $resource, string|int $id): array
    {
        try {
            $response = $this->makeRequest($resource.'/'.$id, self::METHOD_GET);
        } catch (ClientException $e) {
            throw self::createNotFoundException($resource, $id, $e);
        }

        return $this->responseParser->parseResponse($response);
    }

    public function getAll(string $resource, array $parameters = []): array
    {
        $response = $this->makeRequest($resource.'/', self::METHOD_GET, [], $parameters);

        return $this->responseParser->parseResponse($response);
    }

    public function create(string $resource, array $data): ?array
    {
        $response = $this->makeRequest($resource.'/', self::METHOD_POST, $data);

        if ($response->getBody()->getSize() > 0) {
            return $this->responseParser->parseResponse($response);
        }

        if ($response->hasHeader('Location')) {
            return $this->handleLocation($response->getHeaderLine('Location'));
        }

        return null;
    }

    public function update(string $resource, string|int $id, array $data, bool $partial_update = false): ?array
    {
        $method = $partial_update ? self::METHOD_PATCH : self::METHOD_PUT;

        try {
            $response = $this->makeRequest($resource.'/'.$id, $method, $data);
        } catch (ClientException $e) {
            throw self::createNotFoundException($resource, $id, $e);
        }

        if ($response->hasHeader('Location')) {
            return $this->handleLocation($response->getHeaderLine('Location'));
        }

        return null;
    }

    public function delete(string $resource, string|int $id): bool
    {
        try {
            $response = $this->makeRequest($resource.'/'.$id, self::METHOD_DELETE);
        } catch (ClientException $e) {
            throw self::createNotFoundException($resource, $id, $e);
        }

        return ($response->getStatusCode() === 204);
    }

    private function handleLocation(string $location): array
    {
        $uri = new Uri($location);
        $path = $uri->getPath();

        $response = $this->makeRequest($path, self::METHOD_GET);

        return $this->responseParser->parseResponse($response);
    }

    private function getRequest(string $method, string $uri, array $data = []): Request
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        return new Request($method, $uri, $headers, http_build_query($data));
    }

    private static function createNotFoundException(string $resource, string $identificator, ClientException $prev): ResourceNotFoundException
    {
        return new ResourceNotFoundException(sprintf('Resource [%s/%s] not found', $resource, $identificator), 404, $prev);
    }

    private function makeRequest(string $path, string $method, array $data = [], array $parameters = []): ResponseInterface
    {
        if ($parameters) {
            $path .= '?'.http_build_query($parameters);
        }

        $request = $this->getRequest($method, $path, $data);

        if ($this->request_signer) {
            $request = $this->request_signer->signRequest($request);
        }

        if ($this->auth_provider) {
            $request = $this->auth_provider->addAuthentificationInfo($request);
        }

        try {
            return $this->httpClient->send($request, [
                'base_uri' => rtrim($this->endpoint, '/').'/',
            ]);
        } catch (ServerException $e) {
            throw new RequestFailedException(sprintf('Request %s %s failed', $method, $path), $e->getCode(), $e);
        }
    }
}
