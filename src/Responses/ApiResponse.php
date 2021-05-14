<?php


namespace JannisFieml\ApiGenerator\Responses;

use Illuminate\Http\JsonResponse;
use JannisFieml\ApiGenerator\Services\ArrayTransformerService;

class ApiResponse extends JsonResponse
{
    public function __construct($data = null, $status = 200, $headers = [], $options = 0)
    {
        $data = (new ArrayTransformerService())->transformToCamelCase($data);

        parent::__construct($data, $status, $headers, $options);
    }
}
