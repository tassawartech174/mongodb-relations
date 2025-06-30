<?php

namespace TassawarTech174\MongodbRelations;

use Illuminate\Support\ServiceProvider;

class MongodbRelationsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge config for internal use
        $this->mergeConfigFrom(__DIR__.'/config/mongodb-relations.php', 'mongodb-relations');
    }

    public function boot()
    {
        // Publish config for users to override
        $this->publishes([
            __DIR__.'/config/mongodb-relations.php' => config_path('mongodb-relations.php'),
        ], 'mongodb-relations-config');
    }
}
