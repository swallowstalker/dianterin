@extends("layouts.auth")

@section("content")

    {!! Form::open(["url" => "register"]) !!}

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">

            {!! Form::text("name", old("name", ""), [
                "class" => "col-md-12 input-transparent-white",
                "placeholder" => "Nama",
                "style" => "width: 98%;"
            ]) !!}

        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">

            {!! Form::email("email", old("email", ""), [
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

            {!! Form::text("twitter", old("twitter", ""), [
                "class" => "col-md-12 input-transparent-white",
                "placeholder" => "Twitter",
                "style" => "width: 98%;"
            ]) !!}

        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">

            {!! Form::text("phone", old("phone", ""), [
                "class" => "col-md-12 input-transparent-white",
                "placeholder" => "Phone",
                "style" => "width: 98%;"
            ]) !!}

        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12" style="text-align: center;">

            {!! Recaptcha::render() !!}

        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">

            {!! Form::submit("DAFTAR!", ["name" => "register", "class" => "col-xs-12 button-yellow-white" ]) !!}

        </div>
    </div>

    {!! Form::close() !!}


    <script type="text/javascript">
        $(document).ready(function() {
            $("input[name=name]").focus();
        });
    </script>

@endsection