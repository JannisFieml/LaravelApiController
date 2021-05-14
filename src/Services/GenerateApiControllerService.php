<?php


namespace JannisFieml\ApiGenerator\Services;


use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use JannisFieml\ApiGenerator\Responses\ApiDataResponse;
use JannisFieml\ApiGenerator\Responses\ApiErrorResponse;
use JannisFieml\ApiGenerator\Responses\ApiResponse;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Printer;

class GenerateApiControllerService extends BaseGenerateService
{
    public function generate(): string
    {
        $printer = new Printer();
        $file = new PhpFile();
        $namespace = $file->addNamespace("App\Http\Controllers");
        $class = $namespace->addClass(Str::ucfirst(Str::camel($this->model)) . "Controller");

        $createRequest = "App\\Http\\Requests\\Create" . Str::ucfirst(Str::camel($this->model)) . "Request";
        $updateRequest = "App\\Http\\Requests\\Update" . Str::ucfirst(Str::camel($this->model)) . "Request";

        $namespace->addUse("App\\Models\\" . Str::ucfirst(Str::camel($this->model)));
        $namespace->addUse($createRequest);
        $namespace->addUse($updateRequest);
        $namespace->addUse(ApiResponse::class);
        $namespace->addUse(ApiDataResponse::class);
        $namespace->addUse(ApiErrorResponse::class);
        $namespace->addUse(ArrayTransformerService::class);

        $class->setExtends(Controller::class);

        $class->addMethod('get' . $this->getModelPlural())
            ->setPublic()
            ->setReturnType(ApiResponse::class)
            ->setBody($this->getGetBody());

        $class->addMethod('create' . $this->getModel())
            ->setPublic()
            ->setReturnType(ApiResponse::class)
            ->setBody($this->getCreateBody())
            ->addParameter("request")->setType($createRequest);

        $updateMethod = $class->addMethod('update' . $this->getModel())
            ->setPublic()
            ->setReturnType(ApiResponse::class)
            ->setBody($this->getUpdateBody());
        $updateMethod->addParameter("request")->setType($updateRequest);
        $updateMethod->addParameter("id")->setType("int");

        $class->addMethod('delete' . $this->getModel())
            ->setPublic()
            ->setReturnType(ApiResponse::class)
            ->setBody($this->getDeleteBody())
            ->addParameter("id")->setType("int");

        return $printer->printFile($file);
    }

    private function getGetBody(): string
    {
        $body = "\$data = " . $this->getModel() . "::all();\n\n";
        $body .= "return new ApiDataResponse(\$data->toArray());";
        return $body;
    }

    private function getCreateBody(): string
    {
        $mainVar = Str::camel($this->getModel());

        $body = "\$arrayTransformerService = new ArrayTransformerService();\n";
        $body .= "$$mainVar = new " . $this->getModel() . "(\n".
            "\t\$arrayTransformerService->transformToSnakeCase(\$request->toArray())".
            "\n);\n";
        $body .= "$$mainVar". "->save(); \n\n";

        $body .= "return new ApiDataResponse(\$$mainVar" . "->toArray());";
        return $body;
    }

    private function getUpdateBody(): string
    {
        $mainVar = Str::camel($this->getModel());

        $body = "\$arrayTransformerService = new ArrayTransformerService();\n";
        $body .= "\$$mainVar = " . $this->getModel() . "::find(\$id);\n";
        $body .= "$$mainVar" . "->update(\n".
            "\t\$arrayTransformerService->transformToSnakeCase(\$request->toArray())".
            "\n);\n";
        $body .= "$$mainVar". "->save(); \n\n";

        $body .= "return new ApiDataResponse(\$$mainVar" . "->toArray());";
        return $body;
    }

    private function getDeleteBody(): string
    {
        $mainVar = Str::camel($this->getModel());

        $body = "\$$mainVar = " . $this->getModel() . "::find(\$id);\n";
        $body .= "$$mainVar". "->delete(); \n\n";

        $body .= "return new ApiDataResponse();";
        return $body;
    }

    function getFileName(): string
    {
        return $this->getController() . ".php";
    }
}
