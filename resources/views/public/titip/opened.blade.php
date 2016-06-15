@extends("layouts.main")


@section("style")

    <!-- JQuery-UI Theme CSS -->
    <link href="{!! asset("/") !!}bower_components/jquery-ui/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">

    <link href="{!! asset("/") !!}css/public/titip.css" rel="stylesheet" type="text/css">

@endsection


@section("javascript")

    <!-- JQuery-UI JavaScript -->
    <script src="{!! asset("/") !!}bower_components/jquery-ui/jquery-ui.min.js"></script>

@endsection


@section("content")

    <div class="row">
        <div class="col-md-9" style="margin-bottom: 20px; font-size: 14pt; color: #797979;">

            <div class="row">
                <div class="col-md-12" style="font-size: 14pt;">
                    {!! Html::image("img/ic_grey.png") !!}
                    Daftar restoran
                </div>
            </div>


            @foreach($travel->visitedRestaurants as $visitedRestaurant)

                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-12" style="background: white; padding: 10px; font-size: 12pt;">

                        <section class="head-area">
                            <span style="font-weight: bold; font-size: 15pt;">
                                {{ $visitedRestaurant->restaurant->name }}
                            </span>
                            <span class="pull-right">
                                Ongkos Rp {{ number_format($visitedRestaurant->delivery_cost, 0, ",", ".") }}
                            </span>
                        </section>

                        <section class="order-area" style="font-size: 12pt;">

                            @if (isset($orderElementListByRestaurant[$visitedRestaurant->allowed_restaurant]))

                                @foreach($orderElementListByRestaurant[$visitedRestaurant->allowed_restaurant] as $pendingTransaction)

                                    <section class="order-element

                                    @if($pendingTransaction->is_backup) inactive @endif

                                    ">

                                        <div>

                                            {{ $pendingTransaction->menuObject->name }}
                                            <b>({{ $pendingTransaction->amount }} buah)</b>
                                            {{ $pendingTransaction->preference }}


                                            <span class="pull-right">
                                                Rp {{ number_format($pendingTransaction->subtotal, 0, ",", ".") }}
                                            </span>

                                        </div>
                                        <div style="font-size: 11pt;">
                                            {{ $pendingTransaction->order->user->name }}
                                        </div>
                                    </section>

                                @endforeach

                            @endif

                        </section>

                    </div>
                </div>

            @endforeach

        </div>


        {{-- sidebar order list --}}
        @include("public.titip.element.sidebar_opened")

    </div>


    <script type="text/javascript">

    </script>

@endsection