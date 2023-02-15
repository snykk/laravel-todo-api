<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {

        $method = $this->method();
        if ($method == "PUT" || $method == "POST") {
            return [
                "task" => "required|max:120",
                "priority" => ["required", Rule::in(["low", "medium", "high"])],
            ];
        } else if ($method == "PATCH") {
            return [
                "task" => "sometimes|max:120",
                "priority" => ["sometimes", Rule::in(["low", "medium", "high"])],
            ];
        }
    }
}
