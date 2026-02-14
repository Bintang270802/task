<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Store Task Request
 * 
 * Validates and sanitizes input for creating a new task
 */
class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        // Add authorization logic here if needed
        // For now, allow all requests
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
                'regex:/^[a-zA-Z0-9\s\-\_\.\,\!\?]+$/', // Only allow safe characters
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
     * Get custom error messages for validation rules
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Task name is required.',
            'name.min' => 'Task name must be at least 3 characters.',
            'name.max' => 'Task name cannot exceed 255 characters.',
            'name.regex' => 'Task name contains invalid characters. Only letters, numbers, spaces, and basic punctuation are allowed.',
            'project_id.exists' => 'The selected project does not exist.',
            'project_id.integer' => 'Invalid project ID format.',
        ];
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation(): void
    {
        // Sanitize input
        $this->merge([
            'name' => $this->sanitizeInput($this->name),
            'project_id' => $this->project_id === '' ? null : $this->project_id,
        ]);
    }

    /**
     * Sanitize input to prevent XSS
     *
     * @param string|null $input
     * @return string|null
     */
    private function sanitizeInput(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }

        // Remove HTML tags
        $input = strip_tags($input);
        
        // Trim whitespace
        $input = trim($input);
        
        // Remove multiple spaces
        $input = preg_replace('/\s+/', ' ', $input);
        
        return $input;
    }

    /**
     * Get validated and sanitized data
     *
     * @return array
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Additional sanitization after validation
        if (isset($validated['name'])) {
            $validated['name'] = htmlspecialchars($validated['name'], ENT_QUOTES, 'UTF-8');
        }
        
        return $validated;
    }
}
