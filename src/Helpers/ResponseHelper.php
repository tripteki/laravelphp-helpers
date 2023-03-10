<?php

namespace Tripteki\Helpers\Helpers;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

class ResponseHelper
{
    /**
     * @param int $code
     * @return string
     */
    public function status_type($code)
    {
        $status_type = "undefined";

        if ($code >= 100 && $code < 200) {

            $status_type = "informational";

        } else if ($code >= 200 && $code < 300) {

            $status_type = "success";

        } else if ($code >= 300 && $code < 400) {

            $status_type = "redirection";

        } else if ($code >= 400 && $code < 500) {

            $status_type = "client_error";

        } else if ($code >= 500 && $code < 600) {

            $status_type = "server_error";
        }

        return $status_type;
    }

    /**
     * @param int $code
     * @return string
     */
    public function status_description($code)
    {
        return BaseResponse::$statusTexts[$code] ?? "";
    }
};
