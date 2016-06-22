@extends("layouts.admin")


@section("content")


<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Edit Deposit</h1>
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

                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ url('/admin/deposit/edit') }}">

                        <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                        <input type="hidden" name="id" value="{!! $user->id !!}" />

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

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>



@endsection