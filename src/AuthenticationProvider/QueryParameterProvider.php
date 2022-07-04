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

class QueryParameterProvider implements AuthenticationProviderInterface
{
    public function __construct(
        private readonly string $param_name,
        private readonly string $value,
    ) {
    }

    public function addAuthentificationInfo(RequestInterface $request): RequestInterface
    {
        $uri = $request->getUri();
        $qs = $uri->getQuery();

        parse_str($qs, $query_data);

        $query_data[$this->param_name] = $this->value;
        $qs = http_build_query($query_data);

        $uri = $uri->withQuery($qs);

        return $request->withUri($uri);
    }
}
