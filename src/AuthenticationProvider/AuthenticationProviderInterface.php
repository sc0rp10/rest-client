<?php

/**
 * This file is part of sc/rest-client
 *
 * © Konstantin Zamyakin <dev@weblab.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc\RestClient\AuthenticationProvider;

use Psr\Http\Message\RequestInterface;

interface AuthenticationProviderInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface new Request instanse
     */
    public function addAuthentificationInfo(RequestInterface $request): RequestInterface;
}
