<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class RetrieveMessageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'recipient_identifier' => 'required|email',
            'decryption_key' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }

    protected function throwValidationException(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => __('app.validationFailed'),
                'errors' => $errors
            ], Response::HTTP_BAD_REQUEST)
        );
    }

    public function attributes()
    {
        return [
            "recipient_identifier" => __("app.recipientIdentifier"),
            "decryption_key" => __("app.decryptionKey"),
        ];
    }
}