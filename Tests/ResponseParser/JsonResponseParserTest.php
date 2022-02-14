<?php declare(strict_types=1);

namespace Sc\Tests\ResponseParser;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Sc\RestClient\ResponseParser\Exception\ParsingFailedException;
use Sc\RestClient\ResponseParser\JsonResponseParser;

class JsonResponseParserTest extends TestCase
{
    protected JsonResponseParser $parser;

    protected function setUp(): void
    {
        $this->parser = new JsonResponseParser();
    }

    public function testParseValidResponse(): void
    {
        $data = [
            'd' => [
                'foo' => 'bar',
            ],
        ];

        $response = new Response(200, [], json_encode($data));

        $result = $this->parser->parseResponse($response);

        $this->assertEquals($data, $result);
    }

    public function testParseInvalidResponse(): void
    {
        $data = [
            'd' => [
                'foo' => 'bar',
            ],
        ];

        $response = new Response(200, [], json_encode($data).'bzbzzz');

        try {
            $this->parser->parseResponse($response);
            $this->fail();
        } catch (ParsingFailedException) {
            $this->assertTrue(true);
        }
    }
}
