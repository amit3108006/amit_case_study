<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

trait FailedValidationTrait {

    protected function failedValidation(Validator $validator)
        {
            $response = new JsonResponse([
                "valid" => false,
                "message" => __("Please correct all the validations"),
                "data" => $validator->errors()
            ], 422);

            throw new ValidationException($validator, $response);
        }
}
