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

/**
 * Class QueryParameterProvider.
 */
class QueryParameterProvider implements AuthenticationProviderInterface
{
    protected $param_name;
    protected $value;

    /**
     * @param $param_name
     * @param $value
     */
    public function __construct($param_name, $value)
    {
        $this->param_name = $param_name;
        $this->value = $value;
    }

    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function addAuthentificationInfo(RequestInterface $request)
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
