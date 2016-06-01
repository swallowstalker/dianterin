
@foreach($pendingTransactionList as $transaction)

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">
            <div style="background-color: white; padding: 10px;">

                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-xs-12">
                        <div style="border-bottom: 1px solid #DDDDDD;">
                            Pesanan #{{ $transaction->order_id }}
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12" style="color: black;">
                                <b>{{ $transaction->restaurant }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                {{ $transaction->menu }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 10px;">

                    <div class="col-xs-12 col-md-6" style="padding-right: 6px;">

                        {!! Form::open(["url" => "order/unreceived", "class" => "not-received"]) !!}

                        {!! Form::hidden("id", $transaction->order_id) !!}
                        <button type="submit" class="button-orange-white">BELUM TERIMA</button>

                        {!! Form::close() !!}
                    </div>

                    <div class="col-xs-12 col-md-6" style="padding-left: 6px;">

                        <a href="#feedback-popup" class="feedback-popup-button open-popup">
                            <button class="button-green-white">SUDAH TERIMA</button>

                        </a>
                        {!! Form::hidden("id", $transaction->order_id) !!}

                    </div>
                </div>

            </div>
        </div>
    </div>

@endforeach