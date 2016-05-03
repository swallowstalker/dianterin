<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Restaurant;

class TitipAddRestaurantRequest extends Request
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
        //@todo add active restaurant
        return [
            "restaurant" => "required|exists:direstoranin,id",
            "cost" => "required|numeric|min:0|max:2000000"
        ];
    }
}
