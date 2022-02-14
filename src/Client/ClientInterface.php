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
    public function useRequestSigner(RequestSignerInterface $signer): self;

    public function useAuthenticator(AuthenticationProviderInterface $auth_provider): self;

    public function get(string $resource, string|int $id): array;

    public function getAll(string $resource): array;

    public function create(string $resource, array $data): ?array;

    public function update(string $resource, string|int $id, array $data, bool $partial_update = false): ?array;

    public function delete(string $resource, string|int $id): bool;
}
