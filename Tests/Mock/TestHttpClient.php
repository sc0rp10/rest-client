<?php declare(strict_types=1);

namespace Sc\Tests\Mock;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TestHttpClient extends HttpClient
{
    protected array $request_stack = [];

    protected array $response_stack = [];

    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        $this->request_stack[] = $request;

        $resp = $this->getLastResponse();

        if ($resp->getStatusCode() > 399) {
            throw new ClientException(sprintf('Error %d happened', $resp->getStatusCode()), $request, $resp);
        }

        return $resp;
    }

    public function getLastRequest(): RequestInterface
    {
        return array_shift($this->request_stack);
    }

    public function getLastResponse(): ResponseInterface
    {
        return array_shift($this->response_stack);
    }

    public function addResponse(ResponseInterface $response): void
    {
        $this->response_stack[] = $response;
    }

    public function getInfo(): array
    {
        return [
            'req' => count($this->request_stack),
            'resp' => count($this->response_stack),
        ];
    }
}
