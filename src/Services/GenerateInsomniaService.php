<?php

namespace Jannisfieml\LaravelApiGenerator\Services;

use Illuminate\Support\Str;

class GenerateInsomniaService
{
    private array $schemas;

    public function __construct(array $schemas)
    {
        $this->schemas = $schemas;
    }

    public function generate(): string
    {
        // base object
        $content = [
            '_type' => 'export',
            '__export_format' => 4,
            '__export_date' =>  date(DATE_ISO8601),
            '__export_source' => 'laravelapigenerator',
            'resources' => []
        ];

        $workspaceId = '__WORKSPACE_1__';
        $localGroupId = '__LOCAL_1__';

        // workspace
        $content['resources'][] = [
            '_id' => $workspaceId,
            '_type' => 'workspace',
            'modified' => time(),
            'created' => time(),
            'name' => env('APP_NAME'),
        ];

        // local-environment group
        $content['resources'][] = [
            '_id' => $localGroupId,
            '_type' => 'request_group',
            'parentId' => $workspaceId,
            'modified' => time(),
            'created' => time(),
            'name' => 'Local-Environment',
            'description' => 'My local dev-environment to test my api-routes',
            'environment' => [
                'base_url' => 'http://localhost:8000/api'
            ],
            'environmentPropertyOrder' => [
                '&' => ['base_url']
            ],
        ];

        $i = 1;
        foreach ($this->schemas as $schema) {
            $jsonBody = [];

            foreach($schema['attributes'] as $attribute) {
                $jsonBody[$attribute['name']] = "";
            }

            $content['resources'][] = [
                '_id' => '__GET_' . $i . '__',
                '_type' => 'request',
                'name' => Str::snake($schema['name']),
                'method' => 'GET',
                'url' => '{{ _.base_url }}/' . Str::kebab($schema['name']),
                'parentId' => $localGroupId
            ];

            $content['resources'][] = [
                '_id' => '__POST_' . $i . '__',
                '_type' => 'request',
                'name' => Str::snake($schema['name']),
                'method' => 'POST',
                'url' => '{{ _.base_url }}/' . Str::kebab($schema['name']),
                'parentId' => $localGroupId,
                'body' => [
                    'mimeType' => 'application/json',
                    'text' => json_encode($jsonBody, JSON_PRETTY_PRINT)
                ],
            ];

            $content['resources'][] = [
                '_id' => '__PUT_' . $i . '__',
                '_type' => 'request',
                'name' => Str::snake($schema['name']),
                'method' => 'PUT',
                'url' => '{{ _.base_url }}/' . Str::kebab($schema['name']) . '/{id}',
                'parentId' => $localGroupId,
                'body' => [
                    'mimeType' => 'application/json',
                    'text' => json_encode($jsonBody, JSON_PRETTY_PRINT)
                ],
            ];

            $content['resources'][] = [
                '_id' => '__DELETE_' . $i . '__',
                '_type' => 'request',
                'name' => Str::snake($schema['name']),
                'method' => 'DELETE',
                'url' => '{{ _.base_url }}/' . Str::kebab($schema['name']) . '/{id}',
                'parentId' => $localGroupId
            ];

            $i++;
        }

        return json_encode($content);
    }

    public function getFileName(): string
    {
        return date('Y_m_d_His', time()) . "_insomnia.json";
    }
}
