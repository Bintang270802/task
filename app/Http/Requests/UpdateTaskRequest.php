<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Update Task Request
 * 
 * Validates and sanitizes input for updating an existing task
 */
class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        // Add authorization logic here if needed
        // For example: return $this->user()->can('update', $this->route('task'));
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
                'regex:/^[a-zA-Z0-9\s\-\_\.\,\!\?]+$/',
            ],
            'project_id' => [
                'nullable',
                'integer',
                'exists:projects,id',
                'min:1'
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
            'name.required' => 'Task name is required.',
            'name.min' => 'Task name must be at least 3 characters.',
            'name.max' => 'Task name cannot exceed 255 characters.',
            'name.regex' => 'Task name contains invalid characters.',
            'project_id.exists' => 'The selected project does not exist.',
        ];
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->sanitizeInput($this->name),
            'project_id' => $this->project_id === '' ? null : $this->project_id,
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
