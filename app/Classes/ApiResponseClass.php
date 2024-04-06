<?php

namespace App\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class ApiResponseClass
{
    public static function rollback($e, $message = "Somenthing went wrong! Process not completed") {
        DB::rollBack();
        self::throw($e, $message);
    }

    public static function throw($e, $message = "Something went wrong! Process not completed") {
        Log::error($e);
        throw new HttpResponseException(response()->json([
            "message"=> $message,
            "error" => $e,
        ], 500));
    }

    public static function sendResponses($result, $message, $code=200) {
        $response=[
            'success' => true,
            'data' => $result
        ];

        if(!empty($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }

}
