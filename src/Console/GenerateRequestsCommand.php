<?php

namespace Jannisfieml\LaravelApiGenerator\Console;

use Jannisfieml\LaravelApiGenerator\Services\GenerateRequestService;

class GenerateRequestsCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates requests';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $schemas = $this->getSchemas();

        $actions = ['create', 'update'];

        foreach ($schemas as $schema) {
            foreach ($actions as $action) {
                $generateModelService = new GenerateRequestService($schema, $action);
                $content = $generateModelService->generate();

                $fileName = $generateModelService->getFileName();
                $path = base_path();
                $destinationDirectory = "$path/app/Http/Requests";

                $this->createFile($fileName, $destinationDirectory, $content);
            }
        }

        return 0;
    }
}
