<?php

namespace App\Http\Requests;

use App\Enums\BrazilianState;
use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssociateCreateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $this->cpf),
            'telephone' => preg_replace('/[^0-9]/', '', $this->telephone),
            'state' => strtoupper($this->state ?? ''),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'unique:associates,email', 'max:255'],
            'cpf' => ['required', 'string', 'size:11', 'unique:associates,cpf', new Cpf()],
            'telephone' => ['required', 'string', 'min:10', 'max:15'],
            'city' => ['required', 'string', 'max:255'],
            'state' => [
                'required',
                'string',
                'size:2',
                Rule::enum(BrazilianState::class)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.unique' => 'CPF already in use.',
            'state.size' => 'The state must contain the 2 letter abbreviation (Ex: SP).',
            'state.in' => 'The informed state is invalid. Use a valid abbreviation (ex: SP, RJ).'
        ];
    }
}
