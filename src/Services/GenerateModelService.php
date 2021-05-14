<?php


namespace JannisFieml\ApiGenerator\Services;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Printer;

class GenerateModelService extends BaseGenerateService
{
    public function generate(): string
    {
        $printer = new Printer();
        $file = new PhpFile();
        $namespace = $file->addNamespace("App\Models");
        $class = $namespace->addClass($this->getModel());
        $namespace->addUse(HasFactory::class);
        $namespace->addUse(Model::class);

        $class->setExtends(Model::class);
        $class->addTrait(HasFactory::class);

        $class->addProperty('table', $this->getTable())
            ->setProtected()
            ->addComment("The table associated with the model.\n")
            ->addComment("@var string");

        $class->addProperty('fillable', array_column($this->attributes, 'name'))
            ->setProtected()
            ->addComment("The attributes that are mass assignable.\n")
            ->addComment("@var array");

        $class->addProperty('hidden', [])
            ->setProtected()
            ->addComment("The attributes that should be hidden for arrays.\n")
            ->addComment("@var array");

        $class->addProperty('casts', [])
            ->setProtected()
            ->addComment("The attributes that should be cast to native types.\n")
            ->addComment("@var array");

        $class->addMethod('getId')
            ->setPublic()
            ->setReturnType('int')
            ->setBody("return \$this->getAttribute('id');");

        foreach ($this->attributes as $attribute) {
            $name = $attribute['name'];

            $class->addMethod('get' . Str::ucfirst(Str::camel($name)))
                ->setPublic()
                ->setReturnType($this->convertTypeToPhp($attribute['type']))
                ->setBody("return \$this->getAttribute('$name');");

            $class->addMethod('set' . Str::ucfirst(Str::camel($name)))
                ->setPublic()
                ->setReturnType('self')
                ->setBody("\$this->setAttribute('$name', \$$name);\n\n" . "return \$this;")
                ->addParameter($name)->setType($this->convertTypeToPhp($attribute['type']));

            if ($attribute['type'] === 'foreignId') {
                $relationName = Str::replaceLast('_id', '', $name);

                $namespace->addUse(BelongsTo::class);

                $class->addMethod(Str::camel($relationName))
                    ->setPublic()
                    ->setReturnType(BelongsTo::class)
                    ->setBody("return \$this->belongsTo(" . Str::ucfirst(Str::camel($relationName)) . "::class);");
            }
        }

        return $printer->printFile($file);
    }

    public function getFileName(): string
    {
        return $this->getModel() . ".php";
    }
}
