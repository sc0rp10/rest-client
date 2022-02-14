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

interface ResponseParserInterface
{
    public function parseResponse(ResponseInterface $response): array;
}
