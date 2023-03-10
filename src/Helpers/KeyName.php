<?php

use Illuminate\Database\Eloquent\Model;

if (! function_exists("keyName"))
{
    /**
     * @param string|\Illuminate\Database\Eloquent\Model $model
     * @return string
     */
    function keyName(string|Model $model)
    {
        $model = $model instanceof Model ? $model : app($model);

        $key = $model->getKeyName();

        return $key;
    };
}
