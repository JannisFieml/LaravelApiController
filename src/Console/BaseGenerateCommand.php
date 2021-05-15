<?php

namespace Jannisfieml\LaravelApiGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

abstract class BaseGenerateCommand extends Command
{
    protected Filesystem $filesystem;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        parent::__construct();
    }

    protected function getSchemas(): array
    {
        $schemas = [];
        $files = glob(base_path() . "/schemas/*.{yaml}", GLOB_BRACE);

        foreach ($files as $file) {
            $schema = Yaml::parse(file_get_contents($file));
            $schema['index'] = (int)substr(basename($file), 0, strpos(basename($file), "_"));

            $schemas[] = $schema;
        }

        return $schemas;
    }

    protected function createFile($fileName, $destinationDirectory, $content)
    {
        $file = "$destinationDirectory/$fileName";

        if ($this->filesystem->isDirectory($destinationDirectory)) {
            if ($this->filesystem->isFile($file)) {
                $this->warn("$fileName File Already exists!");
            }
        } else {
            $this->filesystem->makeDirectory($destinationDirectory, 0777, true, true);
        }
        if (! $this->filesystem->put($file, $content)) {
            $this->error('Something went wrong!');
        }
        $this->info("$fileName generated!");
    }
}
