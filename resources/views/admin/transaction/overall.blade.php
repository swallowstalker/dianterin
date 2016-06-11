@extends("layouts.admin")

@section("style")

<!-- DataTables CSS -->
<link href="{!! asset("/") !!}bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

<!-- DataTables Responsive CSS -->
<link href="{!! asset("/") !!}bower_components/datatables-responsive/css/responsive.dataTables.css" rel="stylesheet">

<!-- JQuery-UI Theme CSS -->
<link href="{!! asset("/") !!}bower_components/jquery-ui/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">


<link href="{!! asset("/") !!}bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css">


@endsection


@section("javascript")

        <!-- DataTables JavaScript -->
<script src="{!! asset("/") !!}bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="{!! asset("/") !!}bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script src="{!! asset("/") !!}bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>

<!-- JQuery-UI JavaScript -->
<script src="{!! asset("/") !!}bower_components/jquery-ui/jquery-ui.min.js"></script>

<script src="{!! asset("/") !!}bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>


@endsection


@section("content")


<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Overall Transaction</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    Overall transaction
                </div>
                <div class="panel-body">

                    <section class="date-filter">
                        Total transaction until
                        <input name="date-filter" value="{{ date("Y-m-d") }}" />
                        is <span id="total-transaction"></span>
                    </section>

                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover"
                               cellpadding="0" cellspacing="0" id="data" width="100%">

                            <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Author</th>
                                <th>Movement</th>
                                <th>Code</th>
                                <th>Reason</th>
                                <th>Time</th>
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

    var source = '{{ url("/") }}/admin/transaction/data';
    var sourceTotal = '{{ url("/") }}/admin/transaction/total';
    var csrfHash = "{!! csrf_token() !!}";


    var settings = {
        processing: true,
        autoWidth: false,
        ajax: {
            url: source,
            type: "GET",
            data: function(data) {
                data._token = csrfHash;
                data.dateFilter = $("input[name=date-filter]").val();
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
            { visible: false, searchable: true, orderable: true, data: "id"},
            { visible: true, searchable: true, orderable: true, data: "owner"},
            { visible: true, searchable: true, orderable: true, data: "author"},
            { visible: true, searchable: true, orderable: true, data: "movement"},
            { visible: true, searchable: true, orderable: true, data: "code"},
            { visible: true, searchable: true, orderable: true, data: "action"},
            { visible: true, searchable: true, orderable: true, data: "created_at"}
        ],
        responsive: true
    };
    
    function getTotalTransactionByDateFilter() {

        $.ajax({
            url: sourceTotal,
            type: "GET",
            data: {
                dateFilter: $("input[name=date-filter]").val()
            },
            beforeSubmit: function () {
                $("span#total-transaction").html("retrieving total transaction...");
            },
            success: function (data) {
                $("span#total-transaction").html(data.total);
            }
        });
    }

    $(document).ready(function () {
        var oTable = $('#data').DataTable(settings);
        getTotalTransactionByDateFilter();

        $("input[name=date-filter]").datepicker({
            format: "yyyy-mm-dd"
        });
        
        $("input[name=date-filter]").change(function () {
            oTable.ajax.reload(null, false).draw();
            getTotalTransactionByDateFilter();
        });
    });
</script>

@endsection