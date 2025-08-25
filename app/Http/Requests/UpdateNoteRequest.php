<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul catatan wajib diisi',
            'title.string' => 'Judul catatan harus berupa teks',
            'title.max' => 'Judul catatan maksimal 255 karakter',
            'content.required' => 'Isi catatan wajib diisi',
            'content.string' => 'Isi catatan harus berupa teks',
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