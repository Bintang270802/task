<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Project Request
 * 
 * Validates and sanitizes input for creating a new project
 */
class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\_\.\,]+$/',
                Rule::unique('projects', 'name'),
            ]
        ];
    }

    /**
     * Get custom error messages
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'name.min' => 'Project name must be at least 3 characters.',
            'name.max' => 'Project name cannot exceed 255 characters.',
            'name.regex' => 'Project name contains invalid characters.',
            'name.unique' => 'A project with this name already exists.',
        ];
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->sanitizeInput($this->name),
        ]);
    }

    /**
     * Sanitize input
     */
    private function sanitizeInput(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }

        return trim(strip_tags(preg_replace('/\s+/', ' ', $input)));
    }

    /**
     * Get validated and sanitized data
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        if (isset($validated['name'])) {
            $validated['name'] = htmlspecialchars($validated['name'], ENT_QUOTES, 'UTF-8');
        }
        
        return $validated;
    }
}
