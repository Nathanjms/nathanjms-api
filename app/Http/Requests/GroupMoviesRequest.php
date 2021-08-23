<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupMoviesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sortBy' => ['sometimes', Rule::in(['title', 'id', 'created_at'])],
            'perPage' => 'sometimes|integer|min:1|max:200',
            'isSeen' => 'sometimes|boolean'
        ];
    }
}
