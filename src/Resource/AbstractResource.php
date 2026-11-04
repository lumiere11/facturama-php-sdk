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

use Facturama\Client;

/**
 * Base class for the thin, typed wrappers over the Web API endpoints.
 */
abstract class AbstractResource
{
    public function __construct(protected readonly Client $client)
    {
    }
}
