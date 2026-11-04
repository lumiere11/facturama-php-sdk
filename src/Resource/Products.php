<?php

declare(strict_types=1);

/*
 * This file is part of Facturama PHP SDK.
 *
 * (c) Facturama <dev@facturama.com>
 *
 * This source file is subject to a MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Facturama\Resource;

/**
 * CRUD for the account's products (`Product` / `Products` endpoints).
 */
final class Products extends AbstractResource
{
    public function list(string $search = '', int $start = 0, int $length = 100): \stdClass|array|null
    {
        return $this->client->get('Products', ['search' => $search, 'start' => $start, 'length' => $length]);
    }

    public function find(string $id): \stdClass|array|null
    {
        return $this->client->get('Product/'.$id);
    }

    public function create(array $data): \stdClass|array|null
    {
        return $this->client->post('Product', $data);
    }

    public function update(string $id, array $data): \stdClass|array|null
    {
        return $this->client->put('Product/'.$id, $data);
    }

    public function delete(string $id): \stdClass|array|null
    {
        return $this->client->delete('Product/'.$id);
    }
}
