<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeCreateRequest extends FormRequest
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
            "title" => "required|string|min:3",
            "is_input" => "required"
        ];
    }

    public function messages()
    {
        return [
            "title.required" => "tur nomi kiritilmadi",
            "title.string" => "ma'lumot turi string bo'lishi kerak",
            "title.min:3" => "tur nomi kamida 3 ta belgidan ibotrat bo'lishi kerak",
            "is_input" => "krim yoki chiqim tanlanmadi T/F"

        ];
    }
}
