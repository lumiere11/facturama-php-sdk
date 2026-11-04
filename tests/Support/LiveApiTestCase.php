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

namespace Facturama\Tests\Support;

use Facturama\Client;
use PHPUnit\Framework\TestCase;

/**
 * Base class for tests that call the live Sandbox API.
 */
abstract class LiveApiTestCase extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client(getenv('api_username') ?: null, getenv('api_password') ?: null);
        $this->client->setApiUrl('https://apisandbox.facturama.mx');
    }
}
