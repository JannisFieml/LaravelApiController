<?php

namespace JannisFieml\ApiGenerator;

use Illuminate\Support\ServiceProvider;
use JannisFieml\ApiGenerator\Console\GenerateApiControllersCommand;
use JannisFieml\ApiGenerator\Console\GenerateControllerTestsCommand;
use JannisFieml\ApiGenerator\Console\GenerateMigrationsCommand;
use JannisFieml\ApiGenerator\Console\GenerateModelsCommand;
use JannisFieml\ApiGenerator\Console\GenerateRequestsCommand;
use JannisFieml\ApiGenerator\Console\GenerateRoutesCommand;

class ApiGeneratorServiceProvider extends ServiceProvider
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
                GenerateModelsCommand::class,
                GenerateRequestsCommand::class,
                GenerateApiControllersCommand::class,
                GenerateRoutesCommand::class,
                GenerateControllerTestsCommand::class,
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
