@extends("layouts.admin")

@section("style")

    <!--Summernote CSS-->
    <link href="{!! asset("/") !!}bower_components/summernote/dist/summernote.css" rel="stylesheet">

@endsection


@section("javascript")

    <!-- Summernote JavaScript -->
    <script src="{!! asset("/") !!}bower_components/summernote/dist/summernote.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("textarea[name=message]").summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']]
                ]
            });


        });
    </script>


@endsection


@section("content")

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Broadcast Message</h2>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Broadcast Message
                    </div>
                    <div class="panel-body">

                        @if (! empty($errors))
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-6">
                                {!! implode("<br/>", $errors->all()) !!}
                            </div>
                        </div>
                        @endif

                        {!! Form::open(["url" => "admin/message/broadcast"]) !!}

                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-md-6">
                                    {!! Form::select("type", [0 => "Notification Bar", 1 => "Popup"], 0,
                                        ["class" => "col-md-12"]) !!}
                                </div>
                            </div>

                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-md-6">
                                    {!! Form::textarea("message", "",
                                        ["class" => "col-md-12", "style" => "height: 500px;"]) !!}
                                </div>
                            </div>


                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-md-6">

                                    {!! Form::submit("Broadcast", ["class" => "col-md-3 pull-right button-blue-white"]) !!}

                                </div>
                            </div>

                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection