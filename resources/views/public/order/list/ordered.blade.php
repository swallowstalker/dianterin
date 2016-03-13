
@foreach($orderedList as $orderKey => $order)

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">
            <div style="background-color: white; padding: 10px;">

                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-xs-12">
                        <div style="border-bottom: 1px solid #DDDDDD;">
                            Pesanan #{{ $order->id }}
                        </div>
                    </div>
                </div>

                @foreach($order->elements as $orderElement)

                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-xs-4">

                            {{-- @todo need to add spinner and disable it for processed order --}}
                            {!! Form::select(
                                "amount",
                                [1 => 1, 2 => 2, 3 => 3, 4 => 4],
                                $orderElement->amount,
                                ["class" => "col-xs-12"]
                            ) !!}

                            {!! Form::hidden("order", $orderElement->id) !!}
                            {!! Form::hidden("status", $order->status) !!}

                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-12" style="color: black;">
                                    <b>{{ $orderElement->restaurantObject->name }}</b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    {{ $orderElement->menuObject->name }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    ({{ $orderElement->preference }})
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-xs-12">
                        <b>
                            {!! Form::open(["url" => "order/cancel"]) !!}

                            {!! Form::hidden("id", $order->id) !!}

                            {!! Form::submit("BATALKAN PEMESANAN",
                                ["class" => "button-red-white col-xs-12"]) !!}

                            {!! Form::close() !!}
                        </b>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-xs-12" style="text-align: center; font-size: 11pt; color: #4CBB90;">
                        <i class="fa fa-check-circle" style="font-size: 13pt;"></i>
                        Pesanan anda telah didaftarkan.
                    </div>
                </div>

            </div>
        </div>
    </div>

@endforeach