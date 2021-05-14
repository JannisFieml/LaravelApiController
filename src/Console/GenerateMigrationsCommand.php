<?php

namespace JannisFieml\ApiGenerator\Console;

use JannisFieml\ApiGenerator\Services\GenerateMigrationService;

class GenerateMigrationsCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates migrations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $schemas = $this->getSchemas();

        foreach ($schemas as $schema) {
            $generateMigrationService = new GenerateMigrationService($schema);
            $content = $generateMigrationService->generate();

            $fileName = $generateMigrationService->getFileName();
            $path = base_path();
            $destinationDirectory = "$path/database/migrations";

            $this->createFile($fileName, $destinationDirectory, $content);
        }

        return 0;
    }
}
