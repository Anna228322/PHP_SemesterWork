<?php


namespace App\Models;


use Illuminate\Foundation\Http\FormRequest;

class CsvRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => [
                'required',
                'file'
            ],
            'tag' => [
                'required',
                'unique:csvs,tag',
                'string'
            ]
        ];
    }
}
