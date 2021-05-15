<?php

namespace JannisFieml\LaravelApiGenerator;

use Illuminate\Support\ServiceProvider;
use Jannisfieml\LaravelApiGenerator\Console\GenerateApiControllersCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateControllerTestsCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateMigrationsCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateSchemaCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateRequestsCommand;
use Jannisfieml\LaravelApiGenerator\Console\GenerateRoutesCommand;

class LaravelApiGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateMigrationsCommand::class,
                GenerateSchemaCommand::class,
                GenerateRequestsCommand::class,
                GenerateApiControllersCommand::class,
                GenerateRoutesCommand::class,
                GenerateControllerTestsCommand::class,
                GenerateSchemaCommand::class,
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
        //
    }
}
