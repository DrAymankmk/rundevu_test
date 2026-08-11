<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class Request.
 */
abstract class AdminRequest extends FormRequest
{



    protected function formatErrors(\Illuminate\Contracts\Validation\Validator $validator)
    {
        return $validator->errors()->all();

    }



}
