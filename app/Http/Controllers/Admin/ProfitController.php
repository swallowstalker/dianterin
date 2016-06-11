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
    
    public function getChartData($dateFilter = "") {

        $month = 0;
        $year = 0;
        $totalDayInCheckedMonth = 0;
        $lastDayWithProfitData = 0;
        if (empty($dateFilter)) {
            $lastDayWithProfitData = date("j");
            $totalDayInCheckedMonth = date("t");
            $month = date("m");
            $year = date("Y");
        } else {
            $totalDayInCheckedMonth = date("t", strtotime($dateFilter ."-01"));
            $lastDayWithProfitData = $totalDayInCheckedMonth;
            $month = date("m", strtotime($dateFilter ."-01"));
            $year = date("Y", strtotime($dateFilter ."-01"));
        }

        $labels = [];
        $values = [];

        for ($day = 1; $day <= $totalDayInCheckedMonth; $day++) {

            $profit = Profit::where("date", $year ."-". $month ."-". $day)->first();

            if (! empty($profit)) {
                $labels[] = $day;
                $values[] = $profit->total;
            } else {

                if ($day <= $lastDayWithProfitData) {
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
