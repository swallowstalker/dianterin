<?php
/**
 * Created by PhpStorm.
 * User: pulung
 * Date: 3/8/16
 * Time: 6:03 AM
 */

namespace App\Validator;


use App\CourierVisitedRestaurant;
use App\Menu;
use App\Order;
use App\OrderElement;
use Auth;
use Illuminate\Validation\Validator;
use Log;
use Symfony\Component\Translation\TranslatorInterface;

class OrderValidator extends Validator
{

    private $orderCustomMessages = [
        "sufficient_balance" => "Your balance is not enough."
    ];

    public function __construct(TranslatorInterface $translator, array $data, array $rules, array $messages, array $customAttributes)
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);

        $this->setCustomMessages($this->orderCustomMessages);
    }

    /**
     * Validate if we have sufficient balance to order this menu.
     *
     * @param $attribute
     * @param $value menu ID
     * @return bool
     */
    public function validateSufficientBalance($attribute, $value) {

        $latestOrderID = null;
        if ($this->data["backup"] == 1) {
            $latestOrderID = session()->get("latest_order_id");
        }

        $previousSubtotal = $this->getPreviousOrderTotal($latestOrderID);

        $latestOrderPrice = $this->getCurrentOrderSubtotalFromAddOrder($latestOrderID);

        // compare previous order and current order VS user's balance
        $userBalance = Auth::user()->balance;

        if ($previousSubtotal + $latestOrderPrice <= $userBalance) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Sum up all previous order (excluding currently backup-ed order)
     *
     * @param null $excludedOrderID
     * @return mixed
     */
    private function getPreviousOrderTotal($excludedOrderID = null) {

        // get all ordered list
        $orderList = Order::byOwner()->byStatus(Order::STATUS_ORDERED)->get();

        foreach($orderList as $key => $order) {

            // exclude order with backup in it
            if ($excludedOrderID == $order->id) {

                unset($orderList[$key]);
                continue;
            }

            // add max subtotal of order elements to order
            $order->max_subtotal = $this->findMaxSubtotal($order);
            $orderList[$key] = $order;
        }

        return $orderList->sum("max_subtotal");

    }

    /**
     * Get current order subtotal,
     * if new order, then straight add the subtotal
     * if backup order exist, compare it first with latest order.
     *
     * @param $latestOrderID
     * @param int $backup
     * @return int
     */
    private function getCurrentOrderSubtotalFromAddOrder($latestOrderID, $backup = 0) {


        $latestOrderPrice = 0;

        if ($this->data["backup"] == 1 && ! empty($latestOrderID)) {

            $latestOrderPrice = $this->findMaxSubtotal(Order::find($latestOrderID));
        }

        // new order's price
        $chosenMenu = Menu::find($this->data["menu"]);

        // get delivery cost of visited restaurant
        $visitedRestaurant = CourierVisitedRestaurant::where("allowed_restaurant", $chosenMenu->restaurant->id)
            ->where("travel_id", $this->data["travel"])
            ->first();

        $currentOrderPrice = $chosenMenu->price + $visitedRestaurant->delivery_cost;

        if ($latestOrderPrice < $currentOrderPrice) {
            $latestOrderPrice = $currentOrderPrice;
        }

        return $latestOrderPrice;
    }

    /**
     * Find max subtotal of all order element belongs to this order.
     *
     * @param Order $order
     * @return int
     */
    private function findMaxSubtotal(Order $order) {

        // find maximum subtotal, adding menu price together with travel cost
        $maxSubtotal = 0;
        foreach ($order->elements as $orderElement) {

            $visitedRestaurant = CourierVisitedRestaurant::where("allowed_restaurant", $orderElement->restaurantObject->id)
                ->where("travel_id", $order->travel_id)
                ->first();

            $elementSubtotal = ($orderElement->menuObject->price * $orderElement->amount) + $visitedRestaurant->delivery_cost;

            // compare prev max subtotal
            if ($maxSubtotal < $elementSubtotal) {
                $maxSubtotal = $elementSubtotal;
            }
        }

        return $maxSubtotal;
    }

    /**
     * Validate order element's amount change
     *
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function validateAllowChangeAmount($attribute, $value) {

        $orderElementID = $this->data["order_element_id"];

        $orderElement = OrderElement::where("id", $orderElementID)->first();
        $order = $orderElement->order;

        $uncheckedOrderSubtotal = $this->getPreviousOrderTotal($order->id);

        // change amount of order here
        foreach ($order->elements as $key => $element) {

            if ($orderElementID == $element->id) {
                $order->elements[$key]->amount = $this->data["amount"];
            }
        }

        // recount max subtotal of that order
        $changedOrderPrice = $this->findMaxSubtotal($order);

        // compare previous order and currently changed order VS user's balance
        $userBalance = Auth::user()->balance;

        if ($uncheckedOrderSubtotal + $changedOrderPrice <= $userBalance) {
            return true;
        } else {
            return false;
        }
    }



}