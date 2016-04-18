<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="Enjoy Indonesia street food at your home.
          Let other people take your order.
          Be a food agent to get more cash.
          Pay online or cashless.">

    <meta name="keyword" content="dianterin, food, delivery, restaurant, antar, makanan">
    <meta name="author" content="">

    <link rel="icon" href="{!! asset("/") !!}img/favicon.ico" />

    <title>Dianterin</title>

    <!-- Bootstrap Core CSS -->
    <link href="{!! asset("/") !!}plugins/sb-admin-2/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{!! asset("/") !!}plugins/sb-admin-2/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="{!! asset("/") !!}plugins/sb-admin-2/dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{!! asset("/") !!}plugins/sb-admin-2/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{!! asset("/") !!}plugins/sb-admin-2/bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{!! asset("/") !!}plugins/sb-admin-2/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


    <link href="{!! asset("/") !!}css/general.css" rel="stylesheet" type="text/css">
    <link href="{!! asset("/") !!}css/public.css" rel="stylesheet" type="text/css">
    <link href="{!! asset("/") !!}css/public-override.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery -->
    <script src="{!! asset("/") !!}plugins/jquery-1.11.1.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{!! asset("/") !!}plugins/sb-admin-2/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{!! asset("/") !!}plugins/sb-admin-2/bower_components/metisMenu/dist/metisMenu.min.js"></script>


    {{-- JS init for all js used in this layout. --}}
    <script type="text/javascript">
        var baseURL = '{{ url("/") }}';
    </script>


    @yield("style")


</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

        @include("layouts.main.navigation_top")

    </nav>

    <!-- JQuery-UI Theme CSS -->
    <link href="{!! asset("/") !!}bower_components/jquery-ui/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">

    <!-- Magnific Popup CSS -->
    <link href="{!! asset("/") !!}bower_components/magnific-popup/dist/magnific-popup.css" rel="stylesheet" type="text/css">

    <!-- JQuery-UI JavaScript -->
    <script src="{!! asset("/") !!}bower_components/jquery-ui/jquery-ui.min.js"></script>

    <!-- Magnific Popup JavaScript -->
    <script src="{!! asset("/") !!}bower_components/magnific-popup/dist/jquery.magnific-popup.min.js"></script>


    <div class="profile-background">
        {!! Html::image(
            "img/img_bg_profile.png",
            "Dianterin",
            ["style" => "width: 100%;"])
        !!}
    </div>

    <div class="container-fluid content">
        <div class="row" style="padding-top: 7%; margin-bottom: 80px;">
            <div class="col-sm-10" style="margin-bottom: 30px;">
                <span style="color: #FFC335;">Silahkan pesan,</span> <br/>
                <span style="color: white; font-size: 20pt;">
                    {!! Auth::user()->name !!}
                </span>
            </div>
            <div class="col-sm-2 deposit-placeholder">
                Sisa deposit <br/>
                <span style="font-size: 16pt;">
                    Rp {{ number_format(Auth::user()->balance, 0, ",", ".") }}
                </span>
            </div>
        </div>

        {{-- notification list --}}
        @include("layouts.main.notifications")

        @yield("content")

    </div>
    <!-- /#container-fluid -->


    <div class="container-fluid footer">
        <div class="row">
            <div class="col-md-12" style="text-align: center;">
                Copyright &copy; 2014 - {!! date("Y") !!} Dianterin
            </div>
        </div>
    </div>

</div>
<!-- /#wrapper -->

<script type="text/javascript">
    $("form").one("submit", function(event) {
        event.preventDefault();
        $(this).find("input[type=submit]").prop("disabled", "disabled");
        $(this).submit();
    });
</script>

<!-- Custom Theme JavaScript -->
<script src="{!! asset("/") !!}plugins/sb-admin-2/dist/js/sb-admin-2.js"></script>


@yield("javascript")


</body>

</html>