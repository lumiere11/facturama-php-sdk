<?php

namespace MyPackage\Commands;

use Illuminate\Console\Command;

class PublishConfigCommand extends Command
{
    protected $signature = 'facturama:publish-config';
    protected $description = 'Publish the configuration file for MyPackage';

    public function handle()
    {
        $this->call('vendor:publish', [
            '--provider' => FacturamaServiceProvider::class,
            '--tag' => 'config',
        ]);

        $this->info('Configuration file published successfully.');
    }
}
