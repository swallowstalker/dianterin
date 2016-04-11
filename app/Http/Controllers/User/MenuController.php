<?php

namespace App\Http\Controllers\User;

use App\Menu;
use App\OrderElement;
use App\Restaurant;
use Auth;
use Datatables;
use Form;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use Psy\Util\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            ->addColumn("reference", '{!! App\Http\Controllers\User\MenuController::hideReference($id) !!}')
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

    /**
     * Get user's last preference on this menu.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getLastPreference(Request $request) {

        $orderElement = OrderElement::where("menu", $request->input("menu"))
            ->byOwner()->get()->last();

        $preference = "";
        if (! empty($orderElement)) {
            $preference = $orderElement->preference;
        }

        return new JsonResponse([
            "preference" => $preference
        ]);
    }
}
