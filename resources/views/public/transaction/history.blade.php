@extends("layouts.main")


@section("style")

    <!-- DataTables CSS -->
    <link href="{!! asset("/") !!}bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{!! asset("/") !!}bower_components/datatables-responsive/css/responsive.dataTables.css" rel="stylesheet">

    <!-- DataTables TableTools CSS -->
    <link href="{!! asset("/") !!}bower_components/datatables-tabletools/css/dataTables.tableTools.css" rel="stylesheet">


    <!-- JQuery-UI Theme CSS -->
    <link href="{!! asset("/") !!}bower_components/jquery-ui/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">

    <!-- Magnific Popup CSS -->
    <link href="{!! asset("/") !!}bower_components/magnific-popup/dist/magnific-popup.css" rel="stylesheet" type="text/css">

@endsection


@section("javascript")

    <!-- DataTables JavaScript -->
    <script src="{!! asset("/") !!}bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="{!! asset("/") !!}bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="{!! asset("/") !!}bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>


    <!-- JQuery-UI JavaScript -->
    <script src="{!! asset("/") !!}bower_components/jquery-ui/jquery-ui.min.js"></script>

    <!-- Magnific Popup JavaScript -->
    <script src="{!! asset("/") !!}bower_components/magnific-popup/dist/jquery.magnific-popup.min.js"></script>

@endsection


@section("content")

    <div class="row">
        <div class="col-md-9" style="margin-bottom: 20px; font-size: 14pt; color: #797979;">
            <div class="row">
                <div class="col-md-12">
                    {!! Html::image("img/ic_grey.png") !!}
                    Transaksi lalu
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding: 10px 20px; background-color: white; font-size: 11pt;">

                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTables-list">
                            <thead>
                            <tr>
                                <th></th>

                                <th>Tanggal</th>
                                <th>Pesanan</th>
                                <th>Order ID</th>
                                <th>Harga</th>
                                <th>Ongkos</th>
                                <th>Adjustment</th>
                                <th>Info</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>


        {{-- sidebar order list --}}
        @include("public.order.sidebar_ordered_menu")

    </div>

    @include("public.order.popup.feedback_popup")


    <script type="text/javascript">

        var orderForm = $("form#new-order");
        var source = "{!! url("/") ."/transaction/history/data" !!}"

        $(document).ready(function () {

            var settings = {
                processing: true,
                autoWidth: false,
                ajax: {
                    url: source,
                    type: "GET"
                },
                serverSide: true,
                lengthChange: false,
                searching: false,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                order: [
                    [0, 'desc']
                ],
                columns: [
                    {visible: false, searchable: false, orderable: true, data: "id"},
                    {visible: true, searchable: true, orderable: false, width: "16%", data: "transaction_date"},
                    {visible: true, searchable: true, orderable: false, width: "14%", data: "ordered_menu"},
                    {visible: true, searchable: true, orderable: true, width: "14%", data: "order_id"},

                    {visible: true, searchable: true, orderable: true, width: "11%", data: "price", className: "right"},
                    {visible: true, searchable: true, orderable: true, width: "11%", data: "delivery_cost", className: "right"},
                    {visible: true, searchable: true, orderable: true, width: "11%", data: "adjustment", className: "right"},

                    {visible: true, searchable: true, orderable: true, width: "12%", data: "adjustment_info"},
                    {visible: true, searchable: true, orderable: true, width: "11%", data: "final_cost", className: "right"}
                ],
                responsive: true
            };

            oTable = $('#dataTables-list').DataTable(settings);
        });

    </script>

@endsection