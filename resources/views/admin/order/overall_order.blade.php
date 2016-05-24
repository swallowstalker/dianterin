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


                            {!! Form::open(["url" => "admin/order/ordered/lock"]) !!}

                            {!! Form::select("travel", $openTravels, $openTravels->first(), ["class" => "button-blue-white"]) !!}

                            <button type="submit" class="button-blue-white">
                                <i class="fa fa-lock fa-fw"></i> Close Travel
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

    $(document).ready(function () {

        var settings = {
            processing: true,
            autoWidth: false,
            ajax: {
                url: source,
                type: "GET",
                data: function(data) {
                    data._token = csrfHash;
                    data.travel = $("select[name=travel]").val();
                    console.log(data.travel);
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
                { visible: true, searchable: true, orderable: true, data: "travel_id", name: "travel_id"},
                { visible: true, searchable: true, orderable: true, data: "courier"},

                { visible: true, searchable: false, orderable: false, data: "delete"}
            ],
            responsive: true
        };

        var oTable = $('#data').DataTable(settings);

        $("select[name=travel]").on("change", function () {
            oTable.draw();
        });

    });
</script>

@endsection