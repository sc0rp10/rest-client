<?php

use GuzzleHttp\Psr7\Response;
use Sc\RestClient\ResponseParser\Exception\ParsingFailedException;
use Sc\RestClient\ResponseParser\XmlResponseParser;

class XmlResponseParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var XmlResponseParser */
    protected $parser;

    protected function setUp()
    {
        $this->parser = new XmlResponseParser('root');
    }

    public function testParseValidResponse()
    {
        $data = ['d' => [
            'foo' => 'bar',
        ]];

        $xml = '<root><d><foo>bar</foo></d></root>';

        $response = new Response(200, [], $xml);

        $result = $this->parser->parseResponse($response);

        $this->assertEquals($data, $result);
    }
    public function testParseInvalidResponse()
    {
        $data = ['d' => [
            'foo' => 'bar',
        ]];

        $xml = '<root><d><foo>bar</foo></d></root>';

        $response = new Response(200, [], $xml.'bzbzzz');

        try {
            $this->parser->parseResponse($response);
            $this->fail();
        } catch (ParsingFailedException $e) {}
    }
}
