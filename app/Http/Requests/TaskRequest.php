<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Название задачи обязательно.',
            'title.string' => 'Название должно быть строкой.',
            'title.max' => 'Название не должно превышать 255 символов.',

            'description.string' => 'Описание должно быть строкой.',

            'is_completed.boolean' => 'Поле завершённости должно быть true или false.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'название задачи',
            'description' => 'описание',
            'is_completed' => 'статус выполнения',
        ];
    }
}
