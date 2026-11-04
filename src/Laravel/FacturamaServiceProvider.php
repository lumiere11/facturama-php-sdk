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

namespace Facturama\Laravel;

use Facturama\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Registers a shared `Facturama\Client` configured from `config/facturama.php`.
 *
 * Auto-discovered through `composer.json` (`extra.laravel`).
 */
final class FacturamaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/facturama.php', 'facturama');

        $this->app->singleton(Client::class, static function (Application $app): Client {
            $config = $app['config']->get('facturama');

            $environment = $config['environment'] ?? 'sandbox';
            if (!isset($config['urls'][$environment])) {
                throw new \InvalidArgumentException(\sprintf('Unknown Facturama environment "%s"; use "sandbox" or "production".', $environment));
            }

            $client = new Client($config['user'] ?? null, $config['password'] ?? null, $config['http'] ?? []);

            return $client->setApiUrl($config['urls'][$environment]);
        });

        $this->app->alias(Client::class, 'facturama');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../../config/facturama.php' => $this->app->configPath('facturama.php')], 'facturama-config');
        }
    }

    /**
     * @return list<string>
     */
    public function provides(): array
    {
        return [Client::class, 'facturama'];
    }
}
