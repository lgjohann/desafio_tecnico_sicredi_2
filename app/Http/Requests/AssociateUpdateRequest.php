<?php

namespace App\Http\Requests;

use App\Enums\BrazilianState;
use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssociateUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('cpf')) {
            $this->merge(['cpf' => preg_replace('/[^0-9]/', '', $this->cpf)]);
        }
        if ($this->has('telephone')) {
            $this->merge(['telephone' => preg_replace('/[^0-9]/', '', $this->telephone)]);
        }
        if ($this->has('state')) {
            $this->merge(['state' => strtoupper($this->state)]);
        }
    }

    public function rules(): array
    {
        $associateId = $this->route('id');

        return [
            'name' => ['sometimes', 'string', 'min:3', 'max:255'],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('associates')->ignore($associateId),
                'max:255'
            ],
            'cpf' => [
                'sometimes',
                'string',
                'size:11',
                new Cpf(),
                Rule::unique('associates')->ignore($associateId)
            ],
            'telephone' => ['sometimes', 'string', 'min:10', 'max:15'],
            'city' => ['sometimes', 'string', 'max:255'],
            'state' => [
                'sometimes',
                'string',
                'size:2',
                Rule::enum(BrazilianState::class)
            ],
        ];
    }
}
