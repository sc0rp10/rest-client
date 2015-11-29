<?php

/**
 * This file is part of sc/rest-client
 *
 * © Konstantin Zamyakin <dev@weblab.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc\RestClient\ResponseParser;

use Psr\Http\Message\ResponseInterface;
use Sc\RestClient\ResponseParser\Exception\ParsingFailedException;

/**
 * Class XmlResponseParser.
 */
class XmlResponseParser implements ResponseParserInterface
{
    /**
     * @var string xml root tag
     */
    protected $root_tag;

    /**
     * @param string $root_tag
     */
    public function __construct($root_tag)
    {
        $this->root_tag = $root_tag;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     *
     * @throws ParsingFailedException
     */
    public function parseResponse(ResponseInterface $response)
    {
        $xml = simplexml_load_string((string) $response->getBody());

        if (false === $xml) {
            throw new ParsingFailedException();
        }

        return (array) $xml;
    }
}
