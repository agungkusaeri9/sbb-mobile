<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $id = $this->route('user');
        if ($id) {
            return [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'image' => ['nullable', 'image', 'max:2048', 'mimes:jpg,png,jpeg,svg'],
            ];
        } else {
            return [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'image' => ['nullable', 'image', 'max:2048', 'mimes:jpg,png,jpeg,svg'],
            ];
        }
    }
}
