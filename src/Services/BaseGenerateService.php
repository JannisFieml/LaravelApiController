<?php


namespace Jannisfieml\LaravelApiGenerator\Services;

use Illuminate\Support\Str;

abstract class BaseGenerateService
{
    protected array $schema;

    protected string $model;

    protected array $attributes;

    protected array $hasMany;

    protected mixed $belongsToMany;

    public function __construct(array $schema)
    {
        $this->schema = $schema;
        $this->model = $schema['name'];
        $this->attributes = $schema['attributes'] ?? [];
        $this->hasMany = $schema['has_many'] ?? [];
        $this->belongsToMany = $schema['belongs_to_many'] ?? [];
    }

    abstract public function generate(): string;

    abstract public function getFileName(): string;

    protected function convertTypeToPhp(string $type): string
    {
        return match ($type) {
            'text', 'shortText', 'longText' => 'string',
            'foreignId', 'integer', 'bigInteger' => 'int',
            'double', 'decimal' => 'float',
            default => $type,
        };
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
