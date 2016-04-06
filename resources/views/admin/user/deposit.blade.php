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
                    Edit Deposit
                </div>
                <div class="panel-body">

                    {!! Form::open(["url" => "admin/deposit/edit"]) !!}
                    {!! Form::hidden("id", $user->id) !!}

                    @if (! empty($errors))
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-5">
                                {!! implode("<br/>", $errors->all()) !!}
                            </div>
                        </div>
                    @endif

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-2">
                            Name
                        </div>
                        <div class="col-md-3">

                            {!! Form::text("name", $user->name, [
                                "class" => "col-md-12",
                                "disabled" => "disabled"
                            ]) !!}

                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-2">
                            Email
                        </div>
                        <div class="col-md-3">

                            {!! Form::text("email", $user->email, [
                                "class" => "col-md-12",
                                "disabled" => "disabled"
                            ]) !!}

                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-2">
                            Current Balance
                        </div>
                        <div class="col-md-3">

                            {!! Form::text("balance", $user->balance, [
                                "class" => "col-md-12",
                                "disabled" => "disabled"
                            ]) !!}
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-2">
                            Password
                        </div>
                        <div class="col-md-3">

                            {!! Form::password("password", ["class" => "col-md-12"]) !!}

                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-2">
                            Adjustment
                        </div>
                        <div class="col-md-3">

                            {!! Form::text("adjustment", old("adjustment", ""), ["class" => "col-md-12"]) !!}

                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-2">
                            Reason
                        </div>
                        <div class="col-md-3">

                            {!! Form::textarea("reason", old("reason", ""), ["class" => "col-md-12"]) !!}

                        </div>
                    </div>


                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">

                            {!! Form::submit("Edit", ["class" => "col-md-3 pull-right button-blue-white"]) !!}

                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>
</div>



@endsection