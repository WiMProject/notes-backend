<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . auth()->id(),
            'password' => 'sometimes|required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan oleh user lain',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}