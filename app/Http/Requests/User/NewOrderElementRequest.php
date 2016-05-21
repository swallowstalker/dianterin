<?php

namespace App\Http\Requests\User;

use App\Exceptions\AddOrderElementFailedException;
use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationServiceProvider;

class NewOrderElementRequest extends FormRequest
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
            "backup" => "required|in:0,1",
            "menu" => "required|exists:dimenuin,id|sufficient_balance",
            "travel" => "required|exists:courier_travel,id",
            "preference" => "max:1000"
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new AddOrderElementFailedException($validator);
    }
}
