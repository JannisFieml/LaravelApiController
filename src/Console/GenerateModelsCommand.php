<?php

namespace Jannisfieml\LaravelApiGenerator\Console;

use Jannisfieml\LaravelApiGenerator\Services\GenerateSchemaService;

class GenerateModelsCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates models';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $schemas = $this->getSchemas();

        foreach ($schemas as $schema) {
            $generateModelService = new GenerateSchemaService($schema);
            $content = $generateModelService->generate();

            $fileName = $generateModelService->getFileName();
            $path = base_path();
            $destinationDirectory = "$path/app/Models";

            $this->createFile($fileName, $destinationDirectory, $content);
        }

        return 0;
    }
}
