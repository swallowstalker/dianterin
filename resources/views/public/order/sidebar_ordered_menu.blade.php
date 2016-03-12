
<div class="col-md-3" style="margin-bottom: 20px; color: #797979;">
    <div class="row">
        <div class="col-xs-12" style="font-size: 14pt;">
            {!! Html::image("img/ic_grey.png") !!}
            Pesanan hari ini
        </div>
    </div>

    {{-- items already delivered, awaiting user confirmation --}}



    {{-- newly ordered menu and its backup --}}

    @foreach($orderedList as $orderKey => $order)

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-12">
                <div style="background-color: white; padding: 10px;">

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12">
                            <div style="border-bottom: 1px solid #DDDDDD;">
                                Pesanan #{{ $orderKey + 1 }}
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
                                {!! Html::link(
                                    "#",
                                    "BATALKAN PEMESANAN",
                                    ["class" => "button-red-white col-xs-12"]
                                    ) !!}
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

    {{-- processed order --}}


    {{-- @todo add more list to this check (later) --}}
    @if($orderedList->count() == 0)

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-12">
                <div style="background-color: white; padding: 10px;">
                    Silakan memesan makanan terlebih dahulu.
                </div>
            </div>
        </div>

    @endif

</div>


<script type="text/javascript">

    var baseURL = "{{ url("/") }}";

    $(document).ready(function () {

        $("select[name=amount]").spinner({
            step: 1,
            min: 0,
            max: 4,
            spin: function(event, ui) {


                var orderElementID = $(this).parent().parent().find("input[name=order]").val();
                var csrfHash = "{!! csrf_token() !!}";
                var _this = $(this);


                $.ajax({
                    url: baseURL + '/order/change/amount',
                    type: "POST",
                    data: {
                        _token: csrfHash,
                        amount: ui.value,
                        order_element_id: orderElementID
                    },
                    success: function (data) {

                        console.log(data);

//                        if (data.status == false) {
                            _this.spinner("value", data.amount_response);
//                        }
                    }
                });
            }
        });

        $("select[name=amount]").bind("keydown", function(event) {
            event.preventDefault();
        });

        $("select[name=amount]").focus(function () {
            $(this).blur();
        });

        $("select[name=amount]").each(function (key, value) {

            var status = $(this).parent().parent().find("input[name=status]").val();
            if (status != 0) {
                $(this).spinner("disable");
            }

        })
    });
</script>