<?php

namespace App\Traits;

trait OutputTrait {

    public function sendSuccess($message, $data = []) {
        return response()->json([
            'valid' =>  true,
            'code' => 200,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function sendError($exp, $data = []) {
        return response()->json([
            'valid' => false,
            'code' => $exp->getCode(),
            'message' => $exp->getMessage(),
            'data' => $data
        ]);
    }
}
