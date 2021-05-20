<?php

namespace JannisFieml\LaravelApiGenerator;

use Illuminate\Support\ServiceProvider;
use Jannisfieml\LaravelApiGenerator\Console\GenerateApiControllersCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateControllerTestsCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateInsomniaCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateMigrationsCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateModelsCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateRequestsCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateRoutesCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateSchemaCommand;

class LaravelApiGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravelapigenerator');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateMigrationsCommand::class,
                GenerateModelsCommand::class,
                GenerateSchemaCommand::class,
                GenerateRequestsCommand::class,
                GenerateApiControllersCommand::class,
                GenerateRoutesCommand::class,
                GenerateControllerTestsCommand::class,
                GenerateSchemaCommand::class,
                GenerateInsomniaCommand::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravelapigenerator.php'),
            ], 'config');
        }
    }
}
