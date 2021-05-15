<?php


namespace Jannisfieml\LaravelApiGenerator\Responses;


class ApiDataResponse extends ApiResponse
{
    public function __construct(?array $data = null, $status = 200, $headers = [], $options = 0)
    {
        $content = [
            'data' => $data
        ];

        parent::__construct($content, $status, $headers, $options);
    }
}
