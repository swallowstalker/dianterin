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
            <h1 class="page-header">User</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    Currently registered user: {{ $totalUser }} person,
                    deposit total: Rp {{ number_format($totalBalance, 0, ",", ".") }}
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{!! url("admin/transfer") !!}" class="button-blue-white">
                                <i class="fa fa-exchange"></i> Transfer
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover"
                                       cellpadding="0" cellspacing="0" id="data" width="100%">

                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Twitter</th>
                                        <th>Phone</th>

                                        <th>Deposit</th>

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
    </div>
</div>


<script type="text/javascript">

    var source = '{{ url("/") }}/admin/user/data';
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
//            [0, 'asc']
        ],
        columns: [
            { visible: true, searchable: true, orderable: true, data: "name"},
            { visible: true, searchable: true, orderable: true, data: "email"},
            { visible: true, searchable: true, orderable: true, data: "twitter"},
            { visible: true, searchable: true, orderable: true, data: "phone"},
            { visible: true, searchable: true, orderable: true, data: "balance"},
            { visible: true, searchable: false, orderable: false, data: "edit_deposit"}
        ],
        responsive: true
    };

    $(document).ready(function () {
        var oTable = $('#data').DataTable(settings);
    });
</script>

@endsection