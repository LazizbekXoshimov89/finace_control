<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            "full_name"=> "required|string|min:3",
            "password"=>"required|string|min:3",
            "phone"=>"required|string"
        ];

    }

    public function messages(){
        return[
            "full_name.required"=>"full_name kiritilmadi!",
            "full_name.string"=>"full_name sting bo'lishi kerak!",
            "full_name.min:3"=>"full_name kamida 3 belgidan iborat bo'lishi kerak!",
            "password.required"=>"password kiritilmadi!",
            "password.string"=>"password sting bo'lishi kerak!",
            "password.min:3"=>"password kamida 3 belgidan iborat bo'lishi kerak!",
            "phone.required"=>"tel nomer kiritilmadi!",
            "phone.string"=>"tel nomer sting bo'lishi kerak!"


        ];
}
}
