<?php

namespace Jannisfieml\LaravelApiGenerator\Console;

use Jannisfieml\LaravelApiGenerator\Services\GenerateSchemaService;

class GenerateSchemaCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:schema {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes a new schema';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $schemas = $this->getSchemas();

        $generateModelService = new GenerateSchemaService($schemas, $this->argument('name'));
        $content = $generateModelService->generate();

        $fileName = $generateModelService->getFileName();
        $path = base_path();
        $destinationDirectory = "$path/schemas";

        $this->createFile($fileName, $destinationDirectory, $content);

        return 0;
    }
}
