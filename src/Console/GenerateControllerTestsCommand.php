<?php

namespace Jannisfieml\LaravelApiGenerator\Console;

use Illuminate\Support\Str;
use Jannisfieml\LaravelApiGenerator\Services\GenerateRequestService;
use Jannisfieml\LaravelApiGenerator\Services\GenerateTestsService;

class GenerateControllerTestsCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates controller-tests';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $schemas = $this->getSchemas();

        $actions = ['create', 'read', 'update', 'delete'];

        foreach ($schemas as $schema) {
            foreach($actions as $action) {
                $generateModelService = new GenerateTestsService($schema, $action);
                $content = $generateModelService->generate();

                $fileName = $generateModelService->getFileName();
                $path = base_path();
                $destinationDirectory = "$path/tests/Controller/" . Str::ucfirst(Str::camel($schema['name']));

                $this->createFile($fileName, $destinationDirectory, $content);
            }
        }

        return 0;
    }
}
