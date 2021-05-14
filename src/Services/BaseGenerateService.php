<?php


namespace JannisFieml\ApiGenerator\Services;


use Illuminate\Support\Str;

abstract class BaseGenerateService
{
    /**
     * @var array
     */
    protected $schema;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var array
     */
    protected $attributes;

    public function __construct(array $schema)
    {
        $this->schema = $schema;
        $this->model = $schema['name'];
        $this->attributes = $schema['attributes'];
    }

    abstract function generate(): string;

    abstract function getFileName(): string;

    protected function convertTypeToPhp(string $type): string
    {
        switch ($type) {
            case 'text':
            case 'shortText':
            case 'longText':
                return 'string';

            case 'foreignId':
                return 'int';

            default:
                return $type;
        }
    }

    protected function getPlural(): string
    {
        return Str::plural($this->model);
    }

    protected function getTable(): string
    {
        return Str::snake($this->getPlural());
    }

    protected function getModel(): string
    {
        return Str::ucfirst(Str::camel($this->model));
    }

    protected function getModelPlural(): string
    {
        return Str::ucfirst(Str::camel($this->getPlural()));
    }

    protected function getController(): string
    {
        return Str::ucfirst(Str::camel($this->model)) . "Controller";
    }

    protected function getRoute(): string
    {
        return Str::kebab($this->getPlural());
    }
}
