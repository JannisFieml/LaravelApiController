<?php

namespace Jannisfieml\LaravelApiGenerator\Console;

use Jannisfieml\LaravelApiGenerator\Services\GenerateApiControllerService;

class GenerateApiControllersCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:controllers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates api-controllers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $schemas = $this->getSchemas();

        foreach ($schemas as $schema) {
            $generateApiControllerService = new GenerateApiControllerService($schema);
            $content = $generateApiControllerService->generate();

            $fileName = $generateApiControllerService->getFileName();
            $path = base_path();
            $destinationDirectory = "$path/app/Http/Controllers";

            $this->createFile($fileName, $destinationDirectory, $content);
        }

        return 0;
    }
}
