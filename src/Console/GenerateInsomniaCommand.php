<?php

namespace Jannisfieml\LaravelApiGenerator\Console;

use Jannisfieml\LaravelApiGenerator\Services\GenerateInsomniaService;

class GenerateInsomniaCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:insomnia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes an insomnia export';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $schemas = $this->getSchemas();

        $generateInsomniaService = new GenerateInsomniaService($schemas);
        $content = $generateInsomniaService->generate();

        $fileName = $generateInsomniaService->getFileName();
        $path = base_path();
        $destinationDirectory = "$path/insomnia";

        $this->createFile($fileName, $destinationDirectory, $content);

        return 0;
    }
}
