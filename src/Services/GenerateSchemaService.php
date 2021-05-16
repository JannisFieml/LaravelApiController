<?php

namespace Jannisfieml\LaravelApiGenerator\Services;

use Illuminate\Support\Str;

class GenerateSchemaService
{
    private array $schemas;

    private string $name;

    public function __construct(array $schemas, string $name)
    {
        $this->schemas = $schemas;
        $this->name = $name;
    }

    public function generate(): string
    {
        $content = "name: \"" . $this->name . "\"\n";
        $content .= "attributes:\n";
        $content .= "  -\n";
        $content .= "    name: \"attribute\"\n";
        $content .= "    type: \"string\"\n";
        $content .= "    props: []\n";
        $content .= "    validations: []\n";
        $content .= "hasMany: []\n";
        $content .= "belongsToMany: []\n";

        return $content;
    }

    public function getFileName(): string
    {
        return count($this->schemas) . "_" . Str::snake($this->name) . ".yaml";
    }
}
