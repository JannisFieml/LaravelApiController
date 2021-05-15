<?php


namespace Jannisfieml\LaravelApiGenerator\Responses;


use Jannisfieml\LaravelApiGenerator\Services\ArrayTransformerService;
use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{
    public function __construct($data = null, $status = 200, $headers = [], $options = 0)
    {
        $data = (new ArrayTransformerService())->transformToCamelCase($data);

        parent::__construct($data, $status, $headers, $options);
    }
}
