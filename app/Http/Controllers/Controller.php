<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function sendFailedValidationResponse(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Invalid fields',
            'errors' => $validator->errors()
        ], 422));
    }

    protected function sendForbiddenResponse()
    {
        return response()->json([
            'message' => 'Forbidden for you'
        ], 403);
    }
}