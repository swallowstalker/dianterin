@extends("layouts.main")


@section("style")

    <!-- JQuery-UI Theme CSS -->
    <link href="{!! asset("/") !!}bower_components/jquery-ui/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">

    <!-- Magnific Popup CSS -->
    <link href="{!! asset("/") !!}bower_components/magnific-popup/dist/magnific-popup.css" rel="stylesheet" type="text/css">

    <link href="{!! asset("/") !!}css/public/titip.css" rel="stylesheet" type="text/css">

@endsection


@section("javascript")

    <!-- JQuery-UI JavaScript -->
    <script src="{!! asset("/") !!}bower_components/jquery-ui/jquery-ui.min.js"></script>

    <!-- Magnific Popup JavaScript -->
    <script src="{!! asset("/") !!}bower_components/magnific-popup/dist/jquery.magnific-popup.min.js"></script>

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

            <form method="POST" class="form-horizontal"
                  id="finish-form-travel"
                  action="{{route('user.titip.finish')}}"
                  enctype="multipart/form-data">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">

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

                                    @foreach($orderElementListByRestaurant[$visitedRestaurant->allowed_restaurant] as $orderElement)

                                        <section class="order-element

                                        @if($orderElement->is_backup) inactive @endif

                                        ">

                                            <div>

                                                <input type="radio"
                                                       class="element-selector"
                                                       name="element[{{ $orderElement->order->id }}]"
                                                       value="{{ $orderElement->id }}"
                                                       @if (! $orderElement->is_backup) checked="checked" @endif
                                                >
                                                <input type="hidden" class="delivery-cost" value="{{ $visitedRestaurant->delivery_cost }}">
                                                <input type="hidden" class="subtotal" value="{{ $orderElement->subtotal }}">

                                                {{ $orderElement->menuObject->name }}
                                                <b>({{ $orderElement->amount }} buah)</b>
                                                {{ $orderElement->preference }}


                                                <span class="pull-right">
                                                    Rp {{ number_format($orderElement->subtotal, 0, ",", ".") }}
                                                </span>

                                            </div>
                                            <div>
                                                <input name="adjustment[{{ $orderElement->order->id }}]" placeholder="Penambahan">
                                                <input name="info-adjustment[{{ $orderElement->order->id }}]" placeholder="Info">
                                            </div>
                                            <div style="font-size: 11pt;">
                                                {{ $orderElement->order->user->name }}
                                            </div>
                                        </section>

                                    @endforeach

                                @endif

                            </section>

                        </div>
                    </div>

                @endforeach

                {{-- bagian penanda "pesanan tidak ada" --}}

                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-12" style="background: white; padding: 10px; font-size: 12pt;">

                        <section class="head-area">
                            <span style="font-weight: bold; font-size: 15pt;">
                                Pesanan tidak ada
                            </span>
                        </section>

                        <section class="order-area" style="font-size: 12pt;">

                            @foreach($orderList as $order)

                                <section class="order-element inactive">

                                    <div>

                                        <input type="radio"
                                               class="element-selector"
                                               name="element[{{ $order->id }}]" value="0">

                                        {{ $order->user->name }}

                                    </div>

                                    @foreach($order->elements as $orderElement)
                                        <div>
                                            {{ $orderElement->menuObject->name }}
                                            <b>({{ $orderElement->amount }} buah)</b>
                                            {{ $orderElement->preference }}
                                        </div>
                                    @endforeach


                                </section>

                            @endforeach

                        </section>

                    </div>
                </div>
            </form>

        </div>


        {{-- sidebar order list --}}
        @include("public.titip.element.sidebar_closed")

    </div>


    <script type="text/javascript">

        function countDeliveryCost() {

            var totalCost = 0;

            $("section.order-element").not(".inactive").each(function (key, selector) {

                var input = $(selector).find("input.delivery-cost");

                if (input.length != 0) {
                    totalCost += parseInt(input.val());
                }

            });

            return totalCost;
        }

        function countExpectedSubtotal() {

            var totalCost = 0;

            $("section.order-element").not(".inactive").each(function (key, selector) {

                var input = $(selector).find("input.subtotal");

                if (input.length != 0) {
                    totalCost += parseInt(input.val());
                }

            });

            return totalCost;
        }

        function updateExpectedIncome() {

            var deliveryCost = countDeliveryCost();
            $(".expected-income").html(deliveryCost);
        }

        function updateExpectedSubtotal() {

            var subtotal = countExpectedSubtotal();
            $(".expected-subtotal").html(subtotal);
        }

        $(document).ready(function () {

            updateExpectedIncome();
            updateExpectedSubtotal();

            $("section.order-area input.element-selector").change(function () {

                var selectorName = $(this).prop("name");
                selectorName = selectorName.replace("[", "\\[");
                selectorName = selectorName.replace("]", "\\]");

                $("input[name="+ selectorName +"]").each(function (key, selector) {

                    var elementAreaSelector = $(selector).closest("section.order-element");

                    if ($(selector).is(":checked")) {
                        elementAreaSelector.removeClass("inactive")
                    } else {
                        elementAreaSelector.addClass("inactive")
                    }
                });

                updateExpectedIncome();
                updateExpectedSubtotal();
            });
        });

    </script>

@endsection