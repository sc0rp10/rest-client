<?php

/**
 * This file is part of sc/rest-client
 *
 * © Konstantin Zamyakin <dev@weblab.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc\RestClient\RequestSigner;

use Psr\Http\Message\RequestInterface;

interface RequestSignerInterface
{
    public function signRequest(RequestInterface $request): RequestInterface;
}
