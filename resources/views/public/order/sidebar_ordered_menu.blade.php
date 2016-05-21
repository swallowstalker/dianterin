
<div class="col-md-3" style="margin-bottom: 20px; color: #797979;">
    <div class="row">
        <div class="col-xs-12" style="font-size: 14pt;">
            {!! Html::image("img/ic_grey.png") !!}
            Pesanan hari ini
        </div>
    </div>

    {{-- items already delivered, awaiting user confirmation --}}
    @include("public.order.list.pending_transaction")


    {{-- newly ordered menu and its backup --}}
    @include("public.order.list.ordered")


    {{-- processed order --}}
    @include("public.order.list.processed")


    @if($orderedList->count() == 0 && $processedList->count() == 0 && $pendingTransactionList->count() == 0)

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

        var previousValue = $("select[name=amount]").val();

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

                        if ($.isEmptyObject(data)) {
                            console.log(data);
                            _this.spinner("value", previousValue);
                        } else {
                            previousValue = data.amount_response;
                            _this.spinner("value", data.amount_response);
                        }
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