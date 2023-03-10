<?php

namespace Tripteki\Helpers\Http\Requests;

class FileImportValidation extends FormValidation
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            "file" => "required|mimes:csv,txt,xls,xlsx",
        ];
    }
};
