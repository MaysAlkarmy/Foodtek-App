<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function api_response($status, $message, $data = [], $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ], $code);
    }
}
