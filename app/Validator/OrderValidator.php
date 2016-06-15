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
use App\PendingTransactionOrder;
use App\User;
use Auth;
use Illuminate\Validation\Validator;
use Log;
use Symfony\Component\Translation\TranslatorInterface;

class OrderValidator extends Validator
{

    private $orderCustomMessages = [
        "sufficient_balance" => "Your balance is not enough.",
        "allow_change_amount" => "Your balance is not enough.",
        "sufficient_balance_for_transfer" => "Your balance is not enough.",
        "by_order_status" => "Order status is incorrect.",
        "allowed_chosen_element" => "Order is not chosen correctly.",
        "element_parent_status" => "Order status is incorrect."
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

        $pendingTransactionTotal = PendingTransactionOrder::byOwner()->sum("final_cost");

        $previousSubtotal = $this->getPreviousOrderTotal([$latestOrderID]);

        $latestOrderPrice = $this->getCurrentOrderSubtotalFromAddOrder($latestOrderID);

        // compare previous order and current order VS user's balance
        $userBalance = Auth::user()->balance;

        if ($pendingTransactionTotal + $previousSubtotal + $latestOrderPrice <= $userBalance) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Sum up all previous order (excluding currently backup-ed order)
     *
     * @param array $excludedOrderIDs
     * @param null $ownerID
     * @return mixed
     * @internal param null $excludedOrderID
     */
    private function getPreviousOrderTotal(array $excludedOrderIDs = [], $ownerID = null) {

        // get all ordered list
        $orderList = Order::byOwner($ownerID)->byStatus(Order::STATUS_ORDERED)->get();

        foreach($orderList as $key => $order) {

            // exclude order with backup in it
            if (in_array($order->id, $excludedOrderIDs)) {

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

        if (empty($chosenMenu)) {
            return false;
        }

        // get delivery cost of visited restaurant
        $visitedRestaurant = CourierVisitedRestaurant::where("allowed_restaurant", $chosenMenu->restaurant->id)
            ->where("travel_id", $this->data["travel"])
            ->first();

        if (empty($visitedRestaurant)) {
            return false;
        }

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

        $pendingTransactionTotal = $this->getTotalPendingTransaction();
        $uncheckedOrderSubtotal = $this->getPreviousOrderTotal([$order->id]);

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

        if ($pendingTransactionTotal + $uncheckedOrderSubtotal + $changedOrderPrice <= $userBalance) {
            return true;
        } else {
            return false;
        }
    }

    private function getTotalPendingTransaction($ownerID = null) {

        $pendingTransactionTotal = PendingTransactionOrder::byOwner($ownerID)->sum("final_cost");
        return $pendingTransactionTotal;
    }

    /**
     * Validate if user has sufficient balance for transfer deposit
     *
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function validateSufficientBalanceForTransfer($attribute, $value) {

        $ownerID = $this->data["sender"];
        $sender = User::find($ownerID);
        if (empty($sender)) {
            return false;
        }

        $pendingTransactionTotal = $this->getTotalPendingTransaction($ownerID);
        $uncheckedOrderSubtotal = $this->getPreviousOrderTotal([], $ownerID);

        if ($pendingTransactionTotal + $uncheckedOrderSubtotal + $value <= $sender->balance) {
            return true;
        } else {
            return false;
        }

    }


    public function validateByOrderStatus($attribute, $value, $parameters) {

        if (empty($parameters)) {
            return false;
        }

        if ($value == 0) {
            return true;
        }

        $orderStatus = $parameters[0];
        $orderElementID = $value;

        $orderElement = OrderElement::where("id", $orderElementID)
            ->whereHas("order", function ($query) use ($orderStatus) {
                $query->where("status", $orderStatus);
            });

        if (empty($orderElement)) {
            return false;
        } else {
            return true;
        }

    }


    public function validateAllowedChosenElement($attribute, $value) {

        if ($value == 0) {
            return true;
        } else {
            return $this->validateExists($attribute, $value, ["order_element", "id"]);
        }
    }

    public function validateElementParentStatus($attribute, $value, $parameters) {

        if (empty($parameters)) {
            return false;
        }

        $expectedStatus = $parameters[0];

        $element = OrderElement::find($value);
        $order = $element->order;

        if ($order->status == $expectedStatus) {
            return true;
        } else {
            return false;
        }

    }
}