<?php


namespace JannisFieml\ApiGenerator\Services;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Printer;

class GenerateRequestService extends BaseGenerateService
{
    /**
     * @var string
     */
    private $action;

    public function __construct(array $schema, string $action)
    {
        $this->action = Str::ucfirst($action);
        parent::__construct($schema);
    }

    public function generate(): string
    {
        $printer = new Printer();
        $file = new PhpFile();
        $namespace = $file->addNamespace("App\Http\Requests");
        $class = $namespace->addClass($this->action . $this->getModel() . "Request");
        $namespace->addUse(FormRequest::class);

        $class->setExtends(FormRequest::class);

        $class->addMethod('authorize')
            ->setPublic()
            ->setReturnType('bool')
            ->setBody("return true;");

        $class->addMethod('rules')
            ->setPublic()
            ->setReturnType('array')
            ->setBody($this->getRulesBody());

        return $printer->printFile($file);
    }

    private function getRulesBody(): string
    {
        $body = "return [\n";

        foreach ($this->attributes as $attribute) {
            $validations = $attribute['validations'];
            $validations[] = $this->convertTypeToPhp($attribute['type']);

            $body .=
                "\t'" .
                $attribute['name'] .
                "' => '" .
                implode("|", $validations) .
                "',\n";
        }

        return $body . "];";
    }

    function getFileName(): string
    {
        return $this->action . $this->getModel() . "Request.php";
    }
}
