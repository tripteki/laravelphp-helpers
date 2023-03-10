<?php

namespace Tripteki\Helpers\Http\Responses;

use Tripteki\Helpers\Contracts\IResponse;
use Tripteki\Helpers\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;

class Response implements IResponse
{
    /**
     * @param mixed $data
     * @param int $code
     * @param string $message|null
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($data, int $code, string $message = null): JsonResponse
    {
        $helper = new ResponseHelper();

        return response()->json([

            "status" => [

                "code" => $code,
                "type" => $helper->status_type($code),
                "description" => $helper->status_description($code),
            ],

            "data" => $data,

            "message" => $message,
        ],

        $code);
    }
};
