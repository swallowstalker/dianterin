<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationServiceProvider;

class NewOrderRequest extends FormRequest
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
        
        if ($this->input("backup") == 1 || $this->input("backup") == 0) {
            $this->session()->flash("backup_status", $this->input("backup"));
        } else {
            $this->session()->flash("backup_status", 0);
        }

        $errorMessage = implode("<br/>", $validator->errors()->all());

        return redirect("/")
            ->with(["errorMessage" => $errorMessage, "errorFlag" => 1]);
    }
}
