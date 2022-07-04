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

class HeaderProvider implements AuthenticationProviderInterface
{
    public function __construct(
        private readonly string $header_name,
        private readonly string $value,
    ) {
    }

    public function addAuthentificationInfo(RequestInterface $request): RequestInterface
    {
        return $request->withAddedHeader($this->header_name, $this->value);
    }
}
