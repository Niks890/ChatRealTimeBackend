<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $repositories = [
            'User',
            'Message',
            'Group',
            // ... thêm ở đây
        ];

        foreach ($repositories as $name) {
            $interface = "App\\Repositories\\Contracts\\{$name}RepositoryInterface";
            $implementation = "App\\Repositories\\Eloquent\\{$name}Repository";
            $this->app->bind($interface, $implementation);
        }
    }
}
