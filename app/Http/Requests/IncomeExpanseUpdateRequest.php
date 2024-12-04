<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomeExpanseUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "value"=>"required",
            "comment"=>"required|string|min:3"

        ];
    }

    public function messages(){
        return [
            "value.required"=>"qiymat kiritilmadi",
            "comment.required"=>"izoh kiritilmadi",
            "comment.string"=>"izoh string bo'lishi kerak",
            "comment.min:3"=>"izoh kamida 3 ta belgidan iborat bo'lishi kerak"
        ];

    }
}




