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
            <h1 class="page-header">Order</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Order
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-lg-12" style="margin-bottom: 20px;">


                            {!! Form::open(["url" => "admin/order/lock"]) !!}

                            {!! Form::select("travel", $openTravels, null, ["class" => "button-blue-white"]) !!}
                            <button type="submit" class="button-blue-white">
                                <i class="fa fa-lock fa-fw"></i> Process Order
                            </button>

                            {!! Form::close() !!}

                        </div>
                    </div>

                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover"
                               cellpadding="0" cellspacing="0" id="data" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Status</th>

                                <th>Pesanan</th>
                                <th>ID Perjalanan</th>
                                <th>Pengantar</th>

                                <th></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">

    var source = '{{ url("/") }}/admin/order/data';
    var csrfHash = "{!! csrf_token() !!}";

    var settings = {
        processing: true,
        autoWidth: false,
        ajax: {
            url: source,
            type: "GET",
            data: function(data) {
                data._token = csrfHash;
            }
        },
        serverSide: true,
        lengthChange: false,
        searching: true,
        pageLength: 15,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        order: [
            [0, 'desc']
        ],
        columns: [
            { visible: true, searchable: true, orderable: true, data: "id"},
            { visible: true, searchable: true, orderable: true, data: "name"},
            { visible: true, searchable: false, orderable: false, data: "status"},

            { visible: true, searchable: true, orderable: true, data: "element"},
            { visible: true, searchable: true, orderable: true, data: "travel_id"},
            { visible: true, searchable: true, orderable: true, data: "courier"},

            { visible: true, searchable: false, orderable: false, data: "delete"}
        ],
        responsive: true
    };

    $(document).ready(function () {
        var oTable = $('#data').dataTable(settings);

        $("input[name=opsi1]").keyup(function () {
            oTable.fnFilter($(this).val(), 3);
        });

        $("input[name=opsi2]").keyup(function () {
            oTable.fnFilter($(this).val(), 6);
        });

        $("input[name=opsi3]").keyup(function () {
            oTable.fnFilter($(this).val(), 9);
        });
    });
</script>

@endsection