<?php

namespace App\Http\Requests;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    use FailedValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     * As of now kept it true, but in future we can add some kind of condition in it
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => "required|string|max:256",
            "description" => "required|string",
            "category_id" => "required|exists:categories,id",
            "price" => "required|numeric|max:9999999.99",
            "avatar" => "required|string|url"
        ];
    }
}
