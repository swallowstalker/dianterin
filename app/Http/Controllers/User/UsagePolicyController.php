<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class UsagePolicyController extends Controller
{
    public function showServicePage() {

        return view("public.policy.service");
    }

    public function showUserPage() {

        return view("public.policy.user");
    }

    public function showCourierPage() {

        return view("public.policy.courier");
    }

    public function showTransactionPage() {

        return view("public.policy.transaction");
    }

    public function showSanctionPage() {

        return view("public.policy.sanction");
    }
}
