<?php

namespace Tripteki\Helpers\Contracts;

use Illuminate\Http\JsonResponse;

interface IResponse
{
    /**
     * @param mixed $data
     * @param int $code
     * @param string $message|null
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($data, int $code, string $message = null): JsonResponse;
};
