
@foreach($orderedList as $orderKey => $order)

    <div class="row order" style="margin-bottom: 10px;">
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

                    @if ($orderElement->is_backup)

                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col-xs-12" style="font-weight: bold; color: red;">
                                Cadangan
                            </div>
                        </div>

                    @endif

                    <div class="row order-element" style="margin-bottom: 15px;">
                        <div class="col-xs-4">

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
                                <div class="col-xs-10" style="color: black;">

                                    <b>{{ $orderElement->restaurantObject->name }}</b>

                                </div>
                                <div class="col-xs-2" style="padding: 0;">
                                    <button class="button-red-white cancel-order-element" style="padding: 2px;">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    {{ $orderElement->menuObject->name }}
                                </div>
                            </div>

                            @if (! empty($orderElement->preference))
                                <div class="row">
                                    <div class="col-xs-12">
                                        ({{ $orderElement->preference }})
                                    </div>
                                </div>
                            @endif

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

<script type="text/javascript">
    $(document).ready(function () {

        var cancelElementRoute = "{{ route("user.order.element.delete") }}";
        var csrfHash = "{{ csrf_token() }}";

        $("button.cancel-order-element").click(function () {

            var elementID = $(this).closest(".order-element")
                    .find("input[name=order]").val();

            $.ajax({
                url: cancelElementRoute,
                type: "POST",
                data: {
                    _token: csrfHash,
                    id: elementID
                },
                success: function (data) {
                    location.reload();
                },
                error: function () {
                    location.reload();
                }
            });
        });
    });
</script>