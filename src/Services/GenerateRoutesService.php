<?php


namespace JannisFieml\ApiGenerator\Services;

class GenerateRoutesService extends BaseGenerateService
{
    public function generate(): string
    {
        $content = "";

        $controller = "App\\Http\\Controllers\\" . $this->getController();
        $route = $this->getRoute();

        $content .= "Route::middleware([])->group(function () {\n";
        $content .= "\tRoute::get('/$route', [$controller::class, 'get" . $this->getModelPlural() . "']);\n";
        $content .= "\tRoute::post('/$route', [$controller::class, 'create" . $this->getModel() . "']);\n";
        $content .= "\tRoute::put('/$route/{id}', [$controller::class, 'update" . $this->getModel() . "']);\n";
        $content .= "\tRoute::delete('/$route/{id}', [$controller::class, 'delete" . $this->getModel() . "']);\n";
        $content .= "});\n\n";

        return $content;
    }

    public function getFileName(): string
    {
        return "api.php";
    }
}
