<?php

/**
 * This file is part of sc/rest-client
 *
 * © Konstantin Zamyakin <dev@weblab.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc\RestClient\Client;

use Sc\RestClient\AuthenticationProvider\AuthenticationProviderInterface;
use Sc\RestClient\RequestSigner\RequestSignerInterface;

interface ClientInterface
{
    public function useRequestSigner(RequestSignerInterface $signer);

    public function useAuthenticator(AuthenticationProviderInterface $auth_provider);

    public function get($resource, $id);

    public function getAll($resource);

    public function create($resource, array $data);

    public function update($resource, $id, array $data, $partial_update = false);

    public function delete($resource, $id);
}
