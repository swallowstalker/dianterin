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

                                    @foreach($orderElementListByRestaurant[$visitedRestaurant->allowed_restaurant] as $pendingTransaction)

                                        <section class="order-element">

                                            <div>

                                                <input type="radio"
                                                       class="element-selector"
                                                       name="element[{{ $pendingTransaction->order->id }}]"
                                                       value="{{ $pendingTransaction->id }}"
                                                       @if (! $pendingTransaction->is_backup) checked="checked" @endif
                                                >
                                                <input type="hidden" class="delivery-cost" value="{{ $visitedRestaurant->delivery_cost }}">
                                                <input type="hidden" class="subtotal" value="{{ $pendingTransaction->subtotal }}">

                                                {{ $pendingTransaction->menuObject->name }}
                                                <b>({{ $pendingTransaction->amount }} buah)</b>
                                                {{ $pendingTransaction->preference }}


                                                <span class="pull-right">
                                                    Rp {{ number_format($pendingTransaction->subtotal, 0, ",", ".") }}
                                                </span>

                                            </div>

                                            <div>

                                                <input name="adjustment[{{ $pendingTransaction->order->id }}]" class="adjustment"
                                                       placeholder="Penambahan" disabled="disabled">

                                                <input name="info-adjustment[{{ $pendingTransaction->order->id }}]" class="info"
                                                       placeholder="Info" disabled="disabled">

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

                                    @foreach($order->elements as $pendingTransaction)
                                        <div>
                                            {{ $pendingTransaction->menuObject->name }}
                                            <b>({{ $pendingTransaction->amount }} buah)</b>
                                            {{ $pendingTransaction->preference }}
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

        function _countDeliveryCost() {

            var totalCost = 0;

            $("section.order-element").not(".inactive").each(function (key, selector) {

                var input = $(selector).find("input.delivery-cost");

                if (input.length != 0) {
                    totalCost += parseInt(input.val());
                }

            });

            return totalCost;
        }

        function _countExpectedSubtotal() {

            var totalCost = 0;

            $("section.order-element").not(".inactive").each(function (key, selector) {

                var input = $(selector).find("input.subtotal");

                if (input.length != 0) {
                    totalCost += parseInt(input.val());
                }

            });

            return totalCost;
        }

        function _countAdjustment() {

            var totalCost = 0;

            $("section.order-element").not(".inactive").each(function (key, selector) {

                var input = $(selector).find("input.adjustment");

                if (input.length != 0 && input.val() != "") {
                    totalCost += parseInt(input.val());
                }

            });

            return totalCost;
        }

        function updateExpectedIncome() {

            var deliveryCost = _countDeliveryCost();
            $(".expected-income").html(deliveryCost);
        }

        function updateExpectedSubtotal() {

            var subtotal = _countExpectedSubtotal() + _countAdjustment();
            $(".expected-subtotal").html(subtotal);
        }

        function updateOrderElementActiveness(orderAreaSelector) {

            var elementSelector = orderAreaSelector.find("input.element-selector");
            var selectorName = elementSelector.prop("name");
            selectorName = selectorName.replace("[", "\\[");
            selectorName = selectorName.replace("]", "\\]");

            $("input[name="+ selectorName +"]").each(function (key, selector) {

                var elementAreaSelector = $(selector).closest("section.order-element");

                if ($(selector).is(":checked")) {
                    elementAreaSelector.removeClass("inactive")
                } else {
                    elementAreaSelector.addClass("inactive")
                }

                updateAdjustmentAndInfoActiveness(elementAreaSelector, ! $(selector).is(":checked") );
            });

        }

        function updateAdjustmentAndInfoActiveness(elementAreaSelector, disableFlag) {

            elementAreaSelector.find("input.adjustment").prop("disabled", disableFlag);
            elementAreaSelector.find("input.info").prop("disabled", disableFlag);
        }

        $(document).ready(function () {

            var orderArea = $("section.order-area");
            var elementSelector = orderArea.find("input.element-selector");
            var adjustmentSelector = orderArea.find("input.adjustment");

            elementSelector.each(function (key, selector) {
                updateOrderElementActiveness($(selector).closest("section.order-area"));
            });

            updateExpectedIncome();
            updateExpectedSubtotal();

            elementSelector.change(function () {

                updateOrderElementActiveness($(this).closest("section.order-area"));
                updateExpectedIncome();
                updateExpectedSubtotal();
            });

            adjustmentSelector.keyup(function () {

                updateOrderElementActiveness($(this).closest("section.order-area"));
                updateExpectedIncome();
                updateExpectedSubtotal();
            });
        });

    </script>

@endsection