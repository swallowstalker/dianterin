<?php

namespace App\Http\Requests\User;

use App\Exceptions\AddOrderElementFailedException;
use App\Exceptions\ChangeOrderElementAmountFailedException;
use App\Http\Requests\Request;
use App\Order;
use App\OrderElement;
use Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationServiceProvider;

class ChangeOrderElementAmountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        $orderElement = OrderElement::find($this->input("order_element_id"));
        $order = $orderElement;

        if ($user->id == $order->user_id && $order->status == Order::STATUS_ORDERED) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "order_element_id" => "bail|required|exists:order_element,id|allow_change_amount",
            "amount" => "bail|required|numeric|min:1|max:7"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ChangeOrderElementAmountFailedException($validator);
    }
}
