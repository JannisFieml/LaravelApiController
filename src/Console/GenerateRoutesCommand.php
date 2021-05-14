<?php

namespace JannisFieml\ApiGenerator\Console;

use JannisFieml\ApiGenerator\Services\GenerateRoutesService;

class GenerateRoutesCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates api-routes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $schemas = $this->getSchemas();
        $routes = "\n\n";
        $routes .= $this->getCommentText();
        $routes .= "\n\n";

        foreach($schemas as $schema) {
            $generateRoutesService = new GenerateRoutesService($schema);
            $routes .= $generateRoutesService->generate();
        }

        $this->updateApiRoutes($routes);

        return 0;
    }

    private function updateApiRoutes($routes) {
        $fileName = "api.php";
        $path = base_path();
        $destinationDirectory = "$path/routes";

        $file = "$destinationDirectory/$fileName";

        if($this->filesystem->isDirectory($destinationDirectory)){
            if($this->filesystem->isFile($file)) {
                $content = $this->filesystem->get($file);
                $indexOfGeneratedRoutes = strpos($content, "\n\n" . $this->getCommentText());

                if($indexOfGeneratedRoutes) {
                    $content = substr($content, 0, $indexOfGeneratedRoutes);
                }

                $content .= $routes;

                if(!$this->filesystem->put($file, $content))
                    $this->error('Something went wrong!');
                $this->info("$fileName updated!");
            } else {
                $this->error("api.php routes file does not exist!");
            }
        } else {
            $this->error("routes directory does not exist!");
        }
    }

    private function getCommentText(): string
    {
        $content = "/*\n";
        $content .= "|--------------------------------------------------------------------------\n";
        $content .= "| These are auto-generated routes of @JannisFieml/apigenerator\n";
        $content .= "| Please don't edit this comment or any lines below!\n";
        $content .= "| Otherwise automated updates of this code won't be possible.\n";
        $content .= "| ...but I am just a comment, so do what you want\n";
        $content .= "|--------------------------------------------------------------------------\n";
        $content .= "*/";

        return $content;
    }
}
