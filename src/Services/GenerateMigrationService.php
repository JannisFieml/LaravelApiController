<?php


namespace JannisFieml\ApiGenerator\Services;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Printer;

class GenerateMigrationService extends BaseGenerateService
{
    public function generate(): string
    {
        $printer = new Printer();
        $file = new PhpFile();
        $namespace = $file->addNamespace("");
        $class = $namespace->addClass('Create' . $this->getModelPlural() . 'Table');
        $namespace->addUse(Migration::class);
        $namespace->addUse(Blueprint::class);
        $namespace->addUse(Schema::class);

        $class->setExtends(Migration::class);

        $class->addMethod("up")
            ->setPublic()
            ->addComment("Run the migrations.\n")
            ->addComment("@return void")
            ->setBody($this->createUpBody());

        $class->addMethod("down")
            ->setPublic()
            ->addComment("Reverse the migrations.\n")
            ->addComment("@return void")
            ->setBody($this->createDownBody());

        return $printer->printFile($file);
    }

    private function createUpBody(): string
    {
        $body =
            "Schema::create('" . $this->getTable() . "', function (Blueprint \$table) {\n";

        $body .= "\t\$table->id();\n";
        $body .= "\t\$table->timestamps();\n";

        foreach ($this->attributes as $attribute) {
            $row = "\t\$table->";
            $row .= $attribute['type'] . "('" . $attribute['name'] . "')";

            foreach ($attribute['props'] as $prop) {
                $row .= "->$prop()";
            }

            $body .= $row . ";\n";
        }

        $body .= "});";

        return $body;
    }

    private function createDownBody(): string
    {
        return "Schema::dropIfExists('" . $this->getTable() . "');";
    }

    public function getFileName(): string
    {
        return date('Y_m_d_His', (time() + $this->schema['index'])) . "_create_" . $this->getTable() . "_table.php";
    }
}
