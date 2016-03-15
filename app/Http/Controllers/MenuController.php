<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Restaurant;
use Datatables;
use Form;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

class MenuController extends Controller
{
    private $restaurant;

    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * Return datatables object, for menu choice in new order page.
     *
     * @param Request $request
     * @return mixed
     */
    public function listForOrder(Request $request) {

        $this->validate(
            $request,
            [
                "restaurantID" => "exists:direstoranin,id"
            ]
        );

        $restaurant = Restaurant::where("id", $request->input("restaurantID"))->first();
        $menu = $restaurant->menus;
        return Datatables::of($menu)
            ->addColumn("reference", '{!! App\Http\Controllers\MenuController::hideReference($id) !!}')
            ->make(true);
    }

    /**
     * Hide reference in food order popup in 3rd column
     *
     * @param $menuID
     * @return \Illuminate\Support\HtmlString
     */
    public static function hideReference($menuID) {

        $hiddenReference = Form::hidden("menu-id", $menuID);
        return $hiddenReference;
    }
}
