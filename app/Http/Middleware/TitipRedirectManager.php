<?php

namespace App\Http\Middleware;

use App\CourierTravelRecord;
use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;


class TitipRedirectManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $travel = CourierTravelRecord::byCourier(Auth::user()->id)
            ->orderBy("id", "desc")->first();

        $neededRouteName = "";
        $accessedRouteName = $request->route()->getName();

        if (empty($travel)) {

            $neededRouteName = "user.titip.start";

        } else {

            switch ($travel->status) {
                case CourierTravelRecord::STATUS_OPENED:
                    $neededRouteName = "user.titip.opened";
                    break;
                case CourierTravelRecord::STATUS_CLOSED:
                    $neededRouteName = "user.titip.closed";
                    break;
                case CourierTravelRecord::STATUS_FINISHED:

                    if ($accessedRouteName == "user.titip.finished") {
                        $neededRouteName = "user.titip.finished";
                    } else {
                        $neededRouteName = "user.titip.start";
                    }
                    break;
                default:
                    $neededRouteName = "user.titip.start";
                    break;
            }
        }

        $response = $this->redirectDecisionMaker($neededRouteName, $request, $next);

        return $response;
    }

    private function redirectDecisionMaker($neededRouteName, Request $request, Closure $next) {

        $accessedRouteName = $request->route()->getName();
        $response = $next($request);

        if ($neededRouteName != $accessedRouteName) {
            $response = redirect()->route($neededRouteName);
        }

        return $response;
    }
}
