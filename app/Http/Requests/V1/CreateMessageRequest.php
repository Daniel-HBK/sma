<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateMessageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'content' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'expiry_type' => 'required|in:read_once,time_based',
            'expiry_time' => 'required_if:expiry_type,time_based|date',
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
            "content" => __("app.content"),
            "recipients" => __("app.recipients"),
            "expiry_type" => __("app.expiryType"),
            "expiry_time" => __("app.expiryTime"),
        ];
    }
}