<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
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
            "username"=> "required|string|min:3",
            "password"=> "required|string|min:3",
        ];
    }
    public function messages(){
        return [
            "username.required"=>"username kiritilmadi",
            "username.string"=>"username string bo'lishi kerak!",
            "username.min:3"=>"username kamida 3 belgidan iborat bo'lishi kerak!",
            "password.required"=>"parol kiritilmadi",
            "password.string"=>"parol string bo'lishi kerak!",
            "password.min:3"=>"parol kamida 3 belgidan iborat bo'lishi kerak!",
        ];
    }

}
