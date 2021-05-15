<?php


namespace Jannisfieml\LaravelApiGenerator\Services;

use Illuminate\Support\Str;

class ArrayTransformerService
{
    public function transformToCamelCase(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->transformToCamelCase($value);
            } elseif (is_object($value)) {
                if (method_exists($value, 'toArray')) {
                    $value = $this->transformToCamelCase($value->toArray());
                } else {
                    $value = $this->transformToCamelCase((array)$value);
                }
            }

            $result[Str::camel($key)] = $value;
        }

        return $result;
    }

    public function transformToSnakeCase(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->transformToSnakeCase($value);
            } elseif (is_object($value)) {
                if (method_exists($value, 'toArray')) {
                    $value = $this->transformToSnakeCase($value->toArray());
                } else {
                    $value = $this->transformToSnakeCase((array)$value);
                }
            }

            $result[Str::snake($key)] = $value;
        }

        return $result;
    }
}
