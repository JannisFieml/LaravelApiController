<?php

namespace Jannisfieml\LaravelApiGenerator\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jannisfieml\LaravelApiGenerator\LaravelApiGeneratorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Spatie\\LaravelApiGenerator\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelApiGeneratorServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        include_once __DIR__.'/../database/migrations/create_laravelapigenerator_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
