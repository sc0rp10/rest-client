<?php declare(strict_types=1);

namespace Sc\Tests\ResponseParser;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Sc\RestClient\ResponseParser\Exception\ParsingFailedException;
use Sc\RestClient\ResponseParser\XmlResponseParser;

class XmlResponseParserTest extends TestCase
{
    protected XmlResponseParser $parser;

    protected function setUp(): void
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
        $xml = '<root><d><foo>bar</foo></d></root>';

        $response = new Response(200, [], $xml.'bzbzzz');

        try {
            $this->parser->parseResponse($response);
            $this->fail();
        } catch (ParsingFailedException) {
            $this->assertTrue(true);
        }
    }
}
