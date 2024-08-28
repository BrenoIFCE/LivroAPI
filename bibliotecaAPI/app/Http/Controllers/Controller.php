<?php

namespace App\Http\Controllers;

use http\Env\Response;

abstract class Controller
{

    public function Response(int $status, mixed $data, string $message){
        return response()->json([
            'message' => $message,
            'data' => $data
        ],$status);
    }
}
