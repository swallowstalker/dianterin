<?php

namespace App\Http\Controllers\User;

use App\CourierTravelRecord;
use App\CourierVisitedRestaurant;
use App\Events\OrderDelivered;
use App\Events\OrderLocked;
use App\Events\Travel\TravelIsClosing;
use App\Events\Travel\TravelIsFinishing;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\TitipAddRestaurantRequest;
use App\Order;
use App\OrderElement;
use App\PendingTransactionOrder;
use App\Restaurant;
use Auth;
use DB;
use Event;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Session;

class TitipController extends Controller
{

    public function showStartPage() {

        $restaurantList = Restaurant::active()->orderBy("name")->get();
        $restaurantListDropdown = $restaurantList->pluck("name", "id");
        $restaurantListDropdown = $this->excludeAddedRestaurantFromList($restaurantListDropdown);

        $viewData = [
            "restaurantList" => $restaurantListDropdown
        ];

        return view("public.titip.start", $viewData);
    }

    private function excludeAddedRestaurantFromList(Collection $restaurantListDropdown) {

        if (Session::has("titipRestaurantList")) {

            $chosenRestaurantListForTitip = new Collection(Session::get("titipRestaurantList"));
            $chosenRestaurantListID = $chosenRestaurantListForTitip->pluck("data.id");

            foreach ($chosenRestaurantListID as $addedRestaurantID) {
                $restaurantListDropdown->forget($addedRestaurantID);
            }
        }

        return $restaurantListDropdown;
    }
    
    public function addRestaurant(TitipAddRestaurantRequest $request) {

        $restaurant = Restaurant::find($request->input("restaurant"));

        if (Session::has("titipRestaurantList")) {

            Session::push("titipRestaurantList", (object) [
                "data" => $restaurant,
                "cost" => $request->input("cost")
            ]);

        } else {

            Session::put("titipRestaurantList", [ (object) [
                "data" => $restaurant,
                "cost" => $request->input("cost")
            ]]);
        }

        return redirect()->route("user.titip.start");
    }

    public function open() {

        if (Session::has("titipRestaurantList")) {

            $travelRecord = CourierTravelRecord::create([
                "courier_id" => Auth::user()->id,
                "quota" => 0,
                "limit_time" => null,
                "status" => CourierTravelRecord::STATUS_OPENED
            ]);

            $restaurantList = Session::get("titipRestaurantList");

            foreach ($restaurantList as $restaurant) {

                $travelRecord->visitedRestaurants()->create([
                    "allowed_restaurant" => $restaurant->data->id,
                    "delivery_cost" => $restaurant->cost
                ]);

            }

            Session::forget("titipRestaurantList");

            return redirect()->route("user.titip.opened");

        } else {

            // @todo session flash for no restaurant

            return redirect()->route("user.titip.start");
        }
    }





    public function showOpened() {

        $travel = $this->getOwnTravelByStatus(CourierTravelRecord::STATUS_OPENED);

        if (empty($travel)) {
            return redirect()->route("user.titip.start");
        }

        $orderElementListByRestaurant = $this->getOrderElementListGroupedByRestaurant($travel);

        $expectedIncome = $this->getTotalIncome($orderElementListByRestaurant, $travel);

        $totalPaymentWhichUserBorrow = $this->getTotalPaymentWhichUserBorrow(
            $orderElementListByRestaurant);

        return view("public.titip.opened", [
            "travel" => $travel,
            "orderElementListByRestaurant" => $orderElementListByRestaurant,
            "expectedIncome" => $expectedIncome,
            "totalPaymentWhichUserBorrow" => $totalPaymentWhichUserBorrow
        ]);
    }

    public function close() {

        $travel = $this->getOwnTravelByStatus(CourierTravelRecord::STATUS_OPENED);
        Event::fire(new TravelIsClosing($travel));

        $this->markOrderInsideTravelAsProcessed($travel);

        return redirect()->route("user.titip.closed");
    }





    public function showClosed() {

        $travel = $this->getOwnTravelByStatus(CourierTravelRecord::STATUS_CLOSED);

        if (empty($travel)) {
            return redirect()->route("user.titip.start");
        }

        $orderElementListByRestaurant = $this->getOrderElementListGroupedByRestaurant($travel);
        $orderList = Order::where("travel_id", $travel->id)
            ->whereNotNull("travel_id")
            ->get();

        return view("public.titip.closed", [
            "travel" => $travel,
            "orderElementListByRestaurant" => $orderElementListByRestaurant,
            "orderList" => $orderList
        ]);

    }

    public function finish(Request $request) {

        $travel = $this->getOwnTravelByStatus(CourierTravelRecord::STATUS_CLOSED);
        Event::fire(new TravelIsFinishing($travel));

        $this->inspectChosenOrderElementToBillTheUser($request);

        return redirect()->route("user.titip.finished");
    }





    public function showFinished() {
        
        $travel = $this->getOwnTravelByStatus(CourierTravelRecord::STATUS_FINISHED);

        if (empty($travel)) {
            return redirect()->route("user.titip.start");
        }

        $pendingTransactionList = $this->getPendingTransactionByTravel($travel);
        $pendingTransactionGroupedByRestaurant = $pendingTransactionList->groupBy("restaurant");
        $deliveryCostTotal = $pendingTransactionList->sum("delivery_cost");
        $transactionTotal = $pendingTransactionList->sum("price")
            + $pendingTransactionList->sum("adjustment");

        return view("public.titip.finished", [
            "travel" => $travel,
            "pendingTransactionGroupedByRestaurant" => $pendingTransactionGroupedByRestaurant,
            "deliveryCostTotal" => $deliveryCostTotal,
            "transactionTotal" => $transactionTotal
        ]);
    }

    private function getPendingTransactionByTravel(CourierTravelRecord $travel) {

        $activeTravelID = $travel->id;

        $pendingTransactionList = PendingTransactionOrder::whereHas("order", function ($query) use ($activeTravelID) {
            $query->where("travel_id", $activeTravelID)
                ->whereNotNull("travel_id");
        })->get();

        return $pendingTransactionList;

    }

    private function inspectChosenOrderElementToBillTheUser(Request $request) {

        $adjustmentList = $request->input("adjustment");
        $infoAdjustmentList = $request->input("info-adjustment");
        $chosenElementList = $request->input("element");

        foreach ($chosenElementList as $orderID => $chosenElementID) {

            if ($chosenElementID == 0) {

                // change order status to not found

                //@fixme add conditional for order status
                $order = Order::find($orderID);
                $order->status = Order::STATUS_NOT_FOUND;
                $order->save();

            } else {

                $orderElement = OrderElement::find($chosenElementID);

                Event::fire(new OrderDelivered(
                    $orderElement,
                    $adjustmentList[$orderID],
                    $infoAdjustmentList[$orderID]
                ));

                $order = $orderElement->order;
                $order->status = Order::STATUS_DELIVERED;
                $order->save();
            }
        }

        Event::fire(new OrderLocked($chosenElementList));

        return redirect()->route("user.titip.finished");
    }

    private function getOwnTravelByStatus($status) {
        $travel = CourierTravelRecord::byCourier(Auth::user()->id)
            ->byStatus($status)->orderBy("id", "desc")->first();
        return $travel;
    }

    private function markOrderInsideTravelAsProcessed(CourierTravelRecord $travel) {
        Order::byTravel($travel->id)
            ->update(["status" => Order::STATUS_PROCESSED]);
    }

    private function getOrderElementListGroupedByRestaurant(CourierTravelRecord $travel) {

        $activeTravelID = $travel->id;

        $orderElementList = OrderElement::with("order")
            ->whereHas("order", function($query) use ($activeTravelID) {
                $query->where("travel_id", $activeTravelID)
                    ->whereNotNull("travel_id");
            })
            ->get();

        $orderElementListByRestaurant = $orderElementList->groupBy("restaurant");

        return $orderElementListByRestaurant;
    }

    private function getTotalIncome($orderElementListByRestaurant, CourierTravelRecord $travel) {

        $totalPayment = 0;

        foreach ($orderElementListByRestaurant as $orderElementList) {

            foreach ($orderElementList as $orderElement) {

                if (! $orderElement->is_backup) {

                    $visitedRestaurant = $travel->visitedRestaurants()
                        ->where("allowed_restaurant", $orderElement->restaurant)
                        ->first();

                    $totalPayment += $visitedRestaurant->delivery_cost;

                }

            }
        }

        return $totalPayment;

    }

    private function getTotalPaymentWhichUserBorrow($orderElementListByRestaurant) {

        $totalPayment = 0;

        foreach ($orderElementListByRestaurant as $orderElementList) {

            foreach ($orderElementList as $orderElement) {

                if (! $orderElement->is_backup) {

                    $totalPayment += ($orderElement->subtotal);

                }
            }
        }

        return $totalPayment;

    }
}
