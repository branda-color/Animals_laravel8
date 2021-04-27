<?php

namespace App\Traits;


trait ApiResponseTrait
{

    //統一定義回傳方法
    public function errorResponse($message, $status, $code = null)
    {
        $code = $code ?? $status; //code為null時預設http狀態碼
        return response()->json(
            [
                'message' => $message,
                'code' => $code
            ],
            $status
        );
    }
}
