<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class Response
{
    public static function Success($data, $message, $code): JsonResponse
    {
        return response()->json([
            'status' => 1,
            'data' => $data,
            'message' => $message
        ], $code, [], JSON_UNESCAPED_UNICODE);
    }

    public static function Error($data, $message, $code = 500): JsonResponse
    {
        return response()->json([
            'status' => 0,
            'data' => $data,
            'message' => $message
        ], $code,[], JSON_UNESCAPED_UNICODE);
    }
}
