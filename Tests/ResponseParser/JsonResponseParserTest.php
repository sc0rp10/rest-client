<?php

use GuzzleHttp\Psr7\Response;
use Sc\RestClient\ResponseParser\Exception\ParsingFailedException;
use Sc\RestClient\ResponseParser\JsonResponseParser;

class JsonResponseParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var JsonResponseParser */
    protected $parser;

    protected function setUp()
    {
        $this->parser = new JsonResponseParser();
    }

    public function testParseValidResponse()
    {
        $data = ['d' => [
            'foo' => 'bar',
        ]];

        $response = new Response(200, [], json_encode($data));

        $result = $this->parser->parseResponse($response);

        $this->assertEquals($data, $result);
    }
    public function testParseInvalidResponse()
    {
        $data = ['d' => [
            'foo' => 'bar',
        ]];

        $response = new Response(200, [], json_encode($data).'bzbzzz');

        try {
            $this->parser->parseResponse($response);
            $this->fail();
        } catch (ParsingFailedException $e) {}
    }
}
