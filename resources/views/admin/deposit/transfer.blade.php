@extends("layouts.admin")

@section("style")

@endsection


@section("javascript")

@endsection


@section("content")


<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Transfer</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">


                <div class="panel-heading">
                    Transfer
                </div>
                <div class="panel-body">

                    {!! Form::open(["url" => "admin/transfer/action"]) !!}

                    @if (! empty($errors))
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-5">
                                {!! implode("<br/>", $errors->all()) !!}
                            </div>
                        </div>
                    @endif

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-2">
                            Sender
                        </div>
                        <div class="col-md-3">

                            {!! Form::select("sender", $userList, $defaultUser, ["class" => "col-md-12"]) !!}

                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-2">
                            Receiver
                        </div>
                        <div class="col-md-3">

                            {!! Form::select("receiver", $userList, $defaultUser, ["class" => "col-md-12"]) !!}

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
                            Amount
                        </div>
                        <div class="col-md-3">

                            {!! Form::text("amount", old("amount", ""), ["class" => "col-md-12"]) !!}

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