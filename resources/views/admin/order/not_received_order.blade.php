@extends("layouts.admin")

@section("style")

<!-- DataTables CSS -->
<link href="{!! asset("/") !!}bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

<!-- DataTables Responsive CSS -->
<link href="{!! asset("/") !!}bower_components/datatables-responsive/css/responsive.dataTables.css" rel="stylesheet">

<!-- JQuery-UI Theme CSS -->
<link href="{!! asset("/") !!}bower_components/jquery-ui/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">

@endsection


@section("javascript")

        <!-- DataTables JavaScript -->
<script src="{!! asset("/") !!}bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="{!! asset("/") !!}bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script src="{!! asset("/") !!}bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>


<!-- JQuery-UI JavaScript -->
<script src="{!! asset("/") !!}bower_components/jquery-ui/jquery-ui.min.js"></script>

@endsection


@section("content")


<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Not Received Order</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Not Received Order
                </div>
                <div class="panel-body">

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-lg-12">

                            {!! Form::open(["url" => "admin/order/unreceived", "method" => "GET"]) !!}

                            Travel
                            {!! Form::select("travel", $openTravels, $travel, ["class" => "button-blue-white"]) !!}
                            <button type="submit" class="button-blue-white">
                                Filter
                            </button>

                            {!! Form::close() !!}

                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-lg-12">

                            <table id="data" width="100%" border="1" style="border: 1px solid #E8E8E8; margin-bottom: 10px;">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Travel ID</th>
                                    <th>Nama</th>
                                    <th>Menu</th>
                                    <th>Adjustment</th>
                                    <th>Total Harga</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>

                                @foreach($notReceivedOrderList as $order)

                                    <tr class="transaction-row">

                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->travel_id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td class="menu">

                                             {{--loop all order element, let admin choose one of this--}}
                                            @foreach($order->elements as $key => $pendingTransaction)

                                                <div>

                                                    @if ($key == 0)
                                                        {!! Form::radio("chosen_element[". $order->id ."]", $pendingTransaction->id, true, ["class" => "element-choice"]) !!}
                                                    @else
                                                        {!! Form::radio("chosen_element[". $order->id ."]", $pendingTransaction->id, false, ["class" => "element-choice"]) !!}
                                                    @endif

                                                    {!! Form::hidden("subtotal", $pendingTransaction->subtotal) !!}

                                                    &nbsp;

                                                    {{ $pendingTransaction->restaurantObject->name }},
                                                    {{ $pendingTransaction->menuObject->name }},
                                                    {{ $pendingTransaction->preference }},
                                                    ({{ $pendingTransaction->amount }} buah)

                                                </div>

                                            @endforeach

                                             {{--backup order not available case handling--}}
                                            <div>

                                                {!! Form::radio("chosen_element[". $order->id ."]", 0, false, ["class" => "element-choice"]) !!}
                                                {!! Form::hidden("subtotal", 0) !!}

                                                &nbsp;

                                                Tidak Ada
                                            </div>

                                        </td>
                                        <td class="adjustment">
                                            <div class="row">
                                                <div class="col-md-12">

                                                    {{ Form::text(
                                                        "adjustment[". $order->id ."]", "",
                                                        [
                                                            "class" => "adjustment col-md-8",
                                                            "placeholder" => "Adjustment"
                                                        ]) }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <textarea name="info_adjustment[{{ $order->id }}]" class="info_adjustment col-md-8"
                                                              rows="2" cols="20" placeholder="Information"></textarea>

                                                </div>
                                            </div>

                                        </td>
                                        <td class="subtotal">0</td>
                                        <td>
                                            <button type="submit" class="button-blue-white force-receive">Force Receive</button>
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">

                            Total: <span id="budget-result">0</span>

                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>


    <script type="text/javascript">

        /**
         * Count total budget in all subtotal area
         */
        function updateTotalBudget() {

            var budgetResult = 0;

            $(".subtotal").each(function (index) {

                var subtotal = $(this).html();
                budgetResult += parseInt(subtotal);

            });

            $("#budget-result").html(budgetResult);
        }

        /**
         * Update subtotal in given rowSelector
         * @param rowSelector transaction-row selector
         */
        function updateSubtotal(rowSelector) {

            // update subtotal in affected row
            var elementChoiceValue = $(rowSelector)
                    .find("input[class=element-choice]:checked").parent()
                    .find("input[name=subtotal]").val();

            var adjustment = $(rowSelector).find("input.adjustment").val();
            if (adjustment == "") {
                adjustment = 0;
            }

            var subtotal = parseInt(elementChoiceValue) + parseInt(adjustment);

            $(rowSelector).find(".subtotal").html(subtotal);

            // update global total
            updateTotalBudget();
        }

        function submitForceReceiveForm(rowSelector) {

            var formDestination = "{!! url("admin/order/unreceived/lock") !!}";
            var csrfHash = "{!! csrf_token() !!}";

            var elementChoice = rowSelector.find(".element-choice:checked");
            var adjustment = rowSelector.find("input.adjustment");
            var adjustmentInfo = rowSelector.find("textarea.info_adjustment");

            var formData = {
                _token: csrfHash
            };

            formData[elementChoice.prop("name")] = elementChoice.val();
            formData[adjustment.prop("name")] = adjustment.val();
            formData[adjustmentInfo.prop("name")] = adjustmentInfo.val();

            $.ajax({
                url: formDestination,
                type: "POST",
                data: formData,
                success: function (data) {

                    location.reload();
                }
            });

        }

        $(document).ready(function () {

            // init all subtotal from checked choice in respective row.
            $(".transaction-row").each(function(index, selector) {

                updateSubtotal($(selector));
            });

            $("input[class=element-choice]").change(function () {

                updateSubtotal($(this).closest(".transaction-row"));
            });

            $("input.adjustment").on("keyup change", function () {

                updateSubtotal($(this).closest(".transaction-row"));
            });
            
            $("button.force-receive").click(function () {
                submitForceReceiveForm($(this).closest(".transaction-row"));
            });

        });
    </script>

@endsection