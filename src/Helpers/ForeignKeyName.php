<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

if (! function_exists("foreignKeyName"))
{
    /**
     * @param string|\Illuminate\Database\Eloquent\Model $model
     * @param string|null $identifier
     * @return string
     */
    function foreignKeyName(string|Model $model, $identifier = null)
    {
        $model = $model instanceof Model ? $model : app($model);

        $table = (string) Str::of($model->getTable())->singular();
        $type = $model->getKeyType();
        $key = $identifier ?? $model->getKeyName();

        return $table."_".$key;
    };
}
