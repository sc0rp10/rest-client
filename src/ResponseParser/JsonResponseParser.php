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

class JsonResponseParser implements ResponseParserInterface
{
    public function parseResponse(ResponseInterface $response): array
    {
        try {
            return \json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new ParsingFailedException(previous: $e);
        }
    }
}
