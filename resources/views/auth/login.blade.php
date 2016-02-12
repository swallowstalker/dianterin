@extends("layouts.auth")

@section("content")

    {!! Form::open(["url" => "login"]) !!}

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
        <div class="col-md-12" style="padding-top: 3px; text-align: center; font-size: 10pt;">

            {!! Html::link("password/reset", "Lupa password",
                ["style" => "color: #FEBD3D;"]) !!}

        </div>
    </div>
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">

            {!! Form::submit("YUK MASUK!", ["name" => "login", "class" => "col-xs-12 button-yellow-white" ]) !!}

        </div>
    </div>

    <div class="row" style="margin-bottom: 10px; padding-bottom: 20px;">
        <div class="col-md-12" style="text-align: center; font-size: 10pt;">

            {!! Html::link("register", "DAFTAR YUK",
                [
                    "style" => "color: #FEBD3D;",
                    "class" => "button-black-white col-xs-12"
                ])
            !!}

        </div>
    </div>

    {!! Form::close() !!}


    <script type="text/javascript">
        $(document).ready(function() {
            $("input[name=email]").focus();
        });
    </script>

@endsection
