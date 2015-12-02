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

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Sc\RestClient\AuthenticationProvider\AuthenticationProviderInterface;
use Sc\RestClient\Client\Exception\RequestFailedException;
use Sc\RestClient\Client\Exception\ResourseNotFoundException;
use Sc\RestClient\RequestSigner\RequestSignerInterface;
use Sc\RestClient\ResponseParser\ResponseParserInterface;

/**
 * Class Client.
 */
class Client implements ClientInterface
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var string
     */
    protected $endpoint;

    /** @var RequestSignerInterface */
    protected $request_signer;

    /** @var AuthenticationProviderInterface */
    protected $auth_provider;

    /** @var ResponseParserInterface */
    protected $response_parser;

    /**
     * @param $endpoint
     * @param ResponseParserInterface $responseParser
     */
    public function __construct($endpoint, ResponseParserInterface $responseParser)
    {
        $this->endpoint = rtrim($endpoint, '/').'/';
        $this->response_parser = $responseParser;
    }

    /**
     * @param RequestSignerInterface $signer
     */
    public function useRequestSigner(RequestSignerInterface $signer)
    {
        $this->request_signer = $signer;
    }

    /**
     * @param AuthenticationProviderInterface $auth_provider
     */
    public function useAuthenticator(AuthenticationProviderInterface $auth_provider)
    {
        $this->auth_provider = $auth_provider;
    }

    /**
     * @param $resourse
     * @param $id
     *
     * @return array
     *
     * @throws ResourseNotFoundException
     */
    public function get($resourse, $id)
    {
        try {
            $response = $this->makeRequest($resourse.'/'.$id, self::METHOD_GET);
        } catch (ClientException $e) {
            throw self::createNotFoundException($resourse, $id, $e);
        }

        return $this->response_parser->parseResponse($response);
    }

    /**
     * @param $resourse
     *
     * @return array
     */
    public function getAll($resourse, array $parameters = [])
    {
        $response = $this->makeRequest($resourse.'/', self::METHOD_GET, [], $parameters);

        return $this->response_parser->parseResponse($response);
    }

    /**
     * @param $resourse
     * @param array $data
     *
     * @return bool
     */
    public function create($resourse, array $data)
    {
        $response = $this->makeRequest($resourse.'/', self::METHOD_POST, $data);

        if ((string) $response->getBody() > 0) {
            return $this->response_parser->parseResponse($response);
        }

        if ($response->hasHeader('Location')) {
            return $this->handleLocation($response->getHeaderLine('Location'));
        }
    }

    /**
     * @param $resourse
     * @param $id
     * @param array      $data
     * @param bool|false $partial_update
     *
     * @return array
     *
     * @throws ResourseNotFoundException
     */
    public function update($resourse, $id, array $data, $partial_update = false)
    {
        $method = $partial_update ? self::METHOD_PATCH : self::METHOD_PUT;

        try {
            $response = $this->makeRequest($resourse.'/'.$id, $method, $data);
        } catch (ClientException $e) {
            throw self::createNotFoundException($resourse, $id, $e);
        }

        if ($response->hasHeader('Location')) {
            return $this->handleLocation($response->getHeaderLine('Location'));
        }
    }

    /**
     * @param $resourse
     * @param $id
     *
     * @return bool
     *
     * @throws ResourseNotFoundException
     */
    public function delete($resourse, $id)
    {
        try {
            $response = $this->makeRequest($resourse.'/'.$id, self::METHOD_DELETE);
        } catch (ClientException $e) {
            throw self::createNotFoundException($resourse, $id, $e);
        }

        return ($response->getStatusCode() === 204);
    }

    /**
     * @param $path
     * @param $method
     * @param array $data
     * @param array $parameters
     *
     * @return ResponseInterface
     *
     * @throws RequestFailedException
     */
    protected function makeRequest($path, $method, array $data = [], array $parameters = [])
    {
        $uri = $this->endpoint.$path;

        if ($parameters) {
            $uri .= '?'.http_build_query($parameters);
        }

        $http_client = $this->getHttpClient();

        $request = $this->getRequest($method, $uri, $data);

        if ($this->request_signer) {
            $this->request_signer->signRequest($request);
        }

        if ($this->auth_provider) {
            $request = $this->auth_provider->addAuthentificationInfo($request);
        }

        try {
            return $http_client->send($request);
        } catch (ServerException $e) {
            throw new RequestFailedException(sprintf('Request %s %s failed', $method, $path), $e->getCode(), $e);
        }
    }

    protected static function createNotFoundException($resourse, $identificator, ClientException $prev)
    {
        return new ResourseNotFoundException(sprintf('Resourse [%s/%s] not found', $resourse, $identificator), 404, $prev);
    }

    /**
     * Handle 'Location' header with URI of created/updated resourse.
     *
     * @param $location
     *
     * @return array
     */
    protected function handleLocation($location)
    {
        $uri = new Uri($location);
        $path = trim($uri->getPath(), '/');

        $response = $this->makeRequest($path, self::METHOD_GET);

        return $this->response_parser->parseResponse($response);
    }

    /**
     * @param $method
     * @param $uri
     * @param array $data
     *
     * @return Request
     */
    protected function getRequest($method, $uri, array $data = [])
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        return new Request($method, $uri, $headers, http_build_query($data, null, '&'));
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        return new HttpClient();
    }
}
