@extends("layouts.auth")

@section("content")

    @include("layouts.login.error")

    {!! Form::open(["url" => "password/reset"]) !!}

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">

            {!! Form::email("email", "", [
                "class" => "col-md-12 input-transparent-white",
                "placeholder" => "Email",
                "style" => "width: 98%;"
            ]) !!}

        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">

            {!! Form::password("password", [
                "class" => "col-md-12 input-transparent-white",
                "placeholder" => "Password",
                "style" => "width: 98%;"
            ]) !!}

        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">

            {!! Form::password("password_confirmation", [
                "class" => "col-md-12 input-transparent-white",
                "placeholder" => "Konfirmasi Password",
                "style" => "width: 98%;"
            ]) !!}

        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">

            {!! Form::submit("RESET PASSWORD", ["name" => "reset", "class" => "col-xs-12 button-yellow-white" ]) !!}

        </div>
    </div>

    {!! Form::close() !!}


    <script type="text/javascript">
        $(document).ready(function() {
            $("input[name=password]").focus();
        });
    </script>

@endsection
