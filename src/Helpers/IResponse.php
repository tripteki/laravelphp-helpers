<?php

use Tripteki\Helpers\Contracts\IResponse;

if (! function_exists("iresponse"))
{
    /**
     * @param mixed $data
     * @param int $code
     * @param string $message|null
     * @return \Illuminate\Http\JsonResponse
     */
    function iresponse($data, $code, $message = null)
    {
        $class = config("helpers.response");
        $instance = new $class;

        if ($instance instanceof IResponse) {

            return $instance($data, $code, $message);

        } else {

            throw new \Exception("Configuration 'helpers.response' must be instance of ".IResponse::class.".");
        }
    };
}
