<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChildRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date'],
            'ethnicity' => ['required', 'in:branco,pardo,negro,indigena,amarelo'],
            'gender' => ['required', 'in:masculino,feminino'],
            'address' => ['required', 'string', 'max:255'],
            'address_number' => ['required', 'string', 'max:10'],
            'complement' => ['nullable', 'string', 'max:100'],
            'neighborhood' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:2'],
            'zipcode' => ['required', 'string', 'max:9'],
            'father_name' => ['nullable', 'string', 'max:100'],
            'father_phone' => ['nullable', 'string', 'max:15'],
            'mother_name' => ['nullable', 'string', 'max:100'],
            'mother_phone' => ['nullable', 'string', 'max:15'],
            'parent_id' => ['nullable', 'exists:users,id'],
            'employee_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages()
    {
        return [
            'parent_id.required' => 'O responsável é obrigatório.',
            'parent_id.exists' => 'O responsável selecionado é inválido.',
            'employee_id.required' => 'O funcionário responsável é obrigatório.',
            'employee_id.exists' => 'O funcionário responsável selecionado é inválido.',
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais que :max caracteres.',
            'birth_date.required' => 'A data de nascimento é obrigatória.',
            'birth_date.date' => 'A data de nascimento deve ser uma data válida.',
            'ethnicity.required' => 'A etnia é obrigatória.',
            'ethnicity.in' => 'A etnia selecionada é inválida.',
            'gender.required' => 'O gênero é obrigatório.',
            'gender.in' => 'O gênero selecionado é inválido.',
            'address.required' => 'O endereço é obrigatório.',
            'address.max' => 'O endereço não pode ter mais que :max caracteres.',
            'address_number.required' => 'O número é obrigatório.',
            'address_number.max' => 'O número não pode ter mais que :max caracteres.',
            'neighborhood.required' => 'O bairro é obrigatório.',
            'neighborhood.max' => 'O bairro não pode ter mais que :max caracteres.',
            'city.required' => 'A cidade é obrigatória.',
            'city.max' => 'A cidade não pode ter mais que :max caracteres.',
            'state.required' => 'O estado é obrigatório.',
            'state.max' => 'O estado não pode ter mais que :max caracteres.',
            'zipcode.required' => 'O CEP é obrigatório.',
            'zipcode.max' => 'O CEP não pode ter mais que :max caracteres.',
        ];
    }
}
