<?php

namespace RenokiCo\L1;

use Illuminate\Support\Facades\DB;
use RenokiCo\L1\D1\D1Connection;
use Illuminate\Support\ServiceProvider;
use RenokiCo\L1\CloudflareD1Connector;

class L1ServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->resolving('db', function ($db) {
            $db->extend('d1', function ($config, $name) {
                $config['name'] = $name;

                $connection = new D1Connection(
                    new CloudflareD1Connector(
                        $config['database'],
                        $config['auth']['token'],
                        $config['auth']['account_id'],
                        $config['api'] ?? 'https://api.cloudflare.com/client/v4',
                    ),
                    $config,
                );

                return $connection;
            });
        });
    }
}
