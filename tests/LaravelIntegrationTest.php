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

namespace Facturama\Tests;

use Facturama\Client;
use Facturama\Laravel\Facades\Facturama;
use Facturama\Laravel\FacturamaServiceProvider;
use Orchestra\Testbench\TestCase;

/**
 * Offline tests for the Laravel service provider, config and facade.
 */
final class LaravelIntegrationTest extends TestCase
{
    public function testClientIsBoundAsSingletonAndAliased(): void
    {
        $client = $this->app->make(Client::class);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame($client, $this->app->make(Client::class));
        $this->assertSame($client, $this->app->make('facturama'));
    }

    public function testDefaultsToSandbox(): void
    {
        $this->assertFalse($this->app->make(Client::class)->isProduction());
        $this->assertSame('sandbox', config('facturama.environment'));
    }

    public function testProductionEnvironment(): void
    {
        config(['facturama.environment' => 'production']);

        $this->assertTrue($this->app->make(Client::class)->isProduction());
    }

    public function testUnknownEnvironmentFails(): void
    {
        config(['facturama.environment' => 'staging']);

        $this->expectException(\InvalidArgumentException::class);
        $this->app->make(Client::class);
    }

    public function testFacadeResolvesResources(): void
    {
        $this->assertInstanceOf(\Facturama\Resource\Cfdis::class, Facturama::cfdis());
        $this->assertInstanceOf(\Facturama\Resource\Clients::class, Facturama::clients());
    }

    public function testConfigIsPublishable(): void
    {
        $paths = FacturamaServiceProvider::pathsToPublish(FacturamaServiceProvider::class, 'facturama-config');

        $this->assertCount(1, $paths);
        $this->assertFileExists(array_key_first($paths));
    }

    protected function getPackageProviders($app): array
    {
        return [FacturamaServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return ['Facturama' => Facturama::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('facturama.user', 'pruebas');
        $app['config']->set('facturama.password', 'secret');
    }
}
