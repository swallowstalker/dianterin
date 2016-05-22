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

class DeleteOrderElementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        $orderElement = OrderElement::find($this->input("id"));
        $order = $orderElement->order;

        if ($user->id == $order->user_id) {
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
            "id" => "required|numeric|exists:order_element,id|".
                "element_parent_status:". Order::STATUS_ORDERED
        ];
    }
}
