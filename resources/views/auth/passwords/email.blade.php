@extends("layouts.auth")

@section("content")

    {!! Form::open(["url" => "password/email"]) !!}

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

            {!! Form::submit("SEND EMAIL TO RESET", ["name" => "reset", "class" => "col-xs-12 button-yellow-white" ]) !!}

        </div>
    </div>

    {!! Form::close() !!}


    <script type="text/javascript">
        $(document).ready(function() {
            $("input[name=email]").focus();
        });
    </script>

@endsection
