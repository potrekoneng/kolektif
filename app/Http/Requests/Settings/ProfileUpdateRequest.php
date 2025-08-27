<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'uppercase', 'max:255'],
            'kelas' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'darah' => ['required', 'string', 'max:255'],
            'agama' => ['required', 'string', 'max:255'],
            'kelamin' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required'],
            'tmp_lahir' => ['required', 'string', 'max:255'],
            // 'email' => [
            //     'required',
            //     'string',
            //     'lowercase',
            //     'email',
            //     'max:255',
            //     Rule::unique(User::class)->ignore($this->user()->id),
            // ],
        ];
    }
}
