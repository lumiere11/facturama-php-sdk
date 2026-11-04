<?php

/*
 * This file is part of Facturama PHP SDK.
 *
 * (c) Facturama <dev@facturama.com>
 *
 * This source file is subject to a MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Facturama\Laravel\Facades;

use Facturama\Client;
use Facturama\Resource\Cfdis;
use Facturama\Resource\Clients;
use Facturama\Resource\Products;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Cfdis cfdis()
 * @method static Clients clients()
 * @method static Products products()
 * @method static \stdClass|array|null get(string $path, array $params = [])
 * @method static \stdClass|array|null post(string $path, array $body = [], ?array $params = null)
 * @method static \stdClass|array|null put(string $path, ?array $body = null, array $params = [])
 * @method static \stdClass|array|null delete(string $path, array $params = [])
 * @method static bool isProduction()
 *
 * @see Client
 */
final class Facturama extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Client::class;
    }
}
