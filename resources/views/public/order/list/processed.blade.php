
@foreach($processedList as $orderKey => $order)

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

                @foreach($order->elements as $pendingTransaction)

                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-xs-4">

                            {!! Form::select(
                                "amount",
                                [1 => 1, 2 => 2, 3 => 3, 4 => 4],
                                $pendingTransaction->amount,
                                ["class" => "col-xs-12"]
                            ) !!}

                            {!! Form::hidden("order", $pendingTransaction->id) !!}
                            {!! Form::hidden("status", $order->status) !!}

                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-12" style="color: black;">
                                    <b>{{ $pendingTransaction->restaurantObject->name }}</b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    {{ $pendingTransaction->menuObject->name }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    ({{ $pendingTransaction->preference }})
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-xs-12" style="text-align: center; font-size: 11pt; color: #4CBB90;">
                        <i class="fa fa-check-circle" style="font-size: 13pt;"></i>
                        Pesanan anda sedang diproses.
                    </div>
                </div>

            </div>
        </div>
    </div>

@endforeach