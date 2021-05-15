<?php


namespace Jannisfieml\LaravelApiGenerator\Responses;

class ApiErrorResponse extends ApiResponse
{
    public function __construct($errors, $status = 500, $headers = [], $options = 0)
    {
        $messages = __('http-codes');

        $content = [
            'message' => $messages[$status] ?? 'Unknown Error',
            'errors' => $errors,
        ];

        parent::__construct($content, $status, $headers, $options);
    }
}
