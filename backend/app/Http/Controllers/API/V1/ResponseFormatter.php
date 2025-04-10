<?php

namespace App\Http\Controllers\API\V1;

class ResponseFormatter
{
    protected static $response = [
        'meta' => [
            'code' => 200,
            'status' => 'success',
            'message' => NULL
        ],
        'data' => NULL,
        'errors' => NULL
    ];

    public static function success($data = NULL, $message = NULL, $code = 200, $pagination = NULL)
    {
        self::$response['meta']['message'] = $message;
        self::$response['data'] = $data;
        self::$response['meta']['code'] = $code;
        self::$response['errors'] = NULL;
        if ($pagination) {
            self::$response['meta']['pagination'] = $pagination;
        }

        return response()->json(self::$response, self::$response['meta']['code']);
    }

    public static function error($errors = NULL, $message = NULL, $code = 400)
    {
        self::$response['meta']['code'] = $code;
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['message'] = $message;
        self::$response['data'] = NULL;
        self::$response['errors'] = $errors;
        return response()->json(self::$response, self::$response['meta']['code']);
    }

    public static function validationError($errors)
    {
        return self::error($errors, 'Validation error', 422);
    }
}
