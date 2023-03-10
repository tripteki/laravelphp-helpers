<?php

namespace Tripteki\Helpers\Http\Requests;

class FileExportValidation extends FormValidation
{
    /**
     * @return void
     */
    protected function preValidation()
    {
        return [

            "file" => $this->query("file", "csv"),
        ];
    }

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

            "file" => "sometimes|nullable|string|in:csv,xls,xlsx",
        ];
    }
};
