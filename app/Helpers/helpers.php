<?php

if (!function_exists('response_default')) {
    function response_default(
        bool $success = false,
        string $message = '',
        int $code = null,
        array $data = [],
    ): array {
        return [
            'success' => $success,
            'data' => $data,
            'message' => $message,
            'code' => $code ?? ($success ? 200 : 500)
        ];
    }
}