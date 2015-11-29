<?php

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TestHttpClient extends HttpClient
{
    /** @var RequestInterface[] */
    protected $request_stack = [];

    /** @var ResponseInterface[] */
    protected $response_stack = [];

    public function send(RequestInterface $request, array $options = [])
    {
        $this->request_stack[] = $request;

        $resp = $this->getLastResponse();

        if ($resp->getStatusCode() > 399) {
            throw new ClientException($resp->getStatusCode(), $request, $resp);
        }

        return $resp;
    }

    /**
     * @return RequestInterface
     */
    public function getLastRequest()
    {
        $req = array_shift($this->request_stack);

        return $req;
    }

    /**
     * @return ResponseInterface
     */
    public function getLastResponse()
    {
        $req = array_shift($this->response_stack);

        return $req;
    }

    public function addResponse(ResponseInterface $response)
    {
        $this->response_stack[] = $response;
    }

    public function getInfo()
    {
        return [
            'req' => count($this->request_stack),
            'resp' => count($this->response_stack),
        ];
    }
}
