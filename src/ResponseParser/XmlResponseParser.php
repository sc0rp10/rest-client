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

class XmlResponseParser implements ResponseParserInterface
{
    public function parseResponse(ResponseInterface $response): array
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string((string)$response->getBody());

        if (false === $xml) {
            throw new ParsingFailedException();
        }

        $json = json_encode($xml, JSON_THROW_ON_ERROR);

        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
}
