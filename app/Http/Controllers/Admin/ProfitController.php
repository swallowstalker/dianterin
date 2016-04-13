<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Profit;
use Illuminate\Http\Request;

use App\Http\Requests;
use Psy\Util\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProfitController extends Controller
{
    public function index() {

        return view("admin.profit.graph");
    }

    public function data() {

        $labels = [];
        $values = [];

        for ($day = 1; $day <= date("t"); $day++) {

            $profit = Profit::where("date", date("Y") ."-". date("m") ."-". $day)->first();

            if (! empty($profit)) {
                $labels[] = $day;
                $values[] = $profit->total;
            } else {

                if ($day <= date("j")) {
                    $labels[] = $day;
                    $values[] = 0;
                } else {
                    $labels[] = $day;
                    $values[] = null;
                }

            }
        }

        return new JsonResponse(["labels" => $labels, "values" => $values]);

    }
}
