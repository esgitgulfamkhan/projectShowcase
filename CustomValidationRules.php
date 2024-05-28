<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class CustomValidationRulesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Custom rule: check if a string is a palindrome
        Validator::extend('palindrome', function ($attribute, $value, $parameters, $validator) {
            return $value === strrev($value);
        });

        // Custom rule: check if an integer is prime
        Validator::extend('prime', function ($attribute, $value, $parameters, $validator) {
            if ($value < 2) return false;
            for ($i = 2, $sqrt = sqrt($value); $i <= $sqrt; $i++) {
                if ($value % $i === 0) return false;
            }
            return true;
        });
    }

    public function register()
    {
        //
    }
}

// Usage example in a Form Request
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => 'required|palindrome',
            'number' => 'required|integer|prime',
        ];
    }

    public function messages()
    {
        return [
            'username.palindrome' => 'The username must be a palindrome.',
            'number.prime' => 'The number must be a prime number.',
        ];
    }
}
