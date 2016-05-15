<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Order;

class ProcessedOrderLockRequest extends Request
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
            "chosen_element.*" => "required|numeric|exists:order_element,id|by_order_status:". Order::STATUS_PROCESSED,
            "adjustment.*" => "numeric",
            "info_adjustment.*" => "max:300"
        ];
    }
}
