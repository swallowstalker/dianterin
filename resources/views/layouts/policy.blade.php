@extends("layouts.main")

@section("style")

    <link rel="stylesheet" href="{!! asset("") !!}css/public/policy.css" />

@endsection

@section("content")

    <div class="row" data-spy="scroll" data-target="#policy-contents">
        <section class="col-md-3 policy-links" style="margin-bottom: 20px; font-size: 14pt; color: #797979;">
            <ul class="nav" role="tablist">

                <li class="@if ($activeness == "service") active @endif">
                    <a href="{{ route("user.policy.service") }}">Layanan</a>
                </li>
                <li class="@if ($activeness == "user") active @endif">
                    <a href="{{ route("user.policy.user") }}">Pengguna</a>
                </li>
                <li class="@if ($activeness == "courier") active @endif">
                    <a href="{{ route("user.policy.courier") }}">Pengantar</a>
                </li>
                <li class="@if ($activeness == "transaction") active @endif">
                    <a href="{{ route("user.policy.transaction") }}">Transaksi</a>
                </li>
                <li class="@if ($activeness == "sanction") active @endif">
                    <a href="{{ route("user.policy.sanction") }}">Sanksi</a>
                </li>
            </ul>
        </section>

        <section class="col-md-9 default-wrapper" style="margin-bottom: 20px; ">

            <section class="policy-title" style="border-bottom: 1px solid #E8E8E8; font-size: 20pt; padding: 10px 10px; margin-bottom: 20px;">
                @yield("policy-title")
            </section>

            @yield("policy-content")

        </section>

    </div>

@endsection