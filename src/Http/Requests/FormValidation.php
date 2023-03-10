<?php

namespace Tripteki\Helpers\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseValidation;

class FormValidation extends BaseValidation
{
    /**
     * @return array
     */
    protected function preValidation()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function postValidation()
    {
        return [];
    }

    /**
     * @return array
     */
    public function validationData()
    {
        return array_merge(parent::validationData(), $this->preValidation());
    }

    /**
     * @param array|int|string|null $key
     * @param mixed $default
     * @return array
     */
    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default), $this->postValidation());
    }
};
