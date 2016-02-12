<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Dianterin</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
          content="Enjoy Indonesia street food at your home.
          Let other people take your order.
          Be a food agent to get more cash.
          Pay online or cashless.">

    <meta name="keyword" content="dianterin, food, delivery, restaurant, antar, makanan">

    <link rel="icon" href="<?php echo img_url(); ?>favicon.ico" />
    <!-- Custom Fonts -->
    <link href="<?php echo plugin_url(); ?>sb-admin-2/bower_components/font-awesome/css/font-awesome.min.css"
          rel="stylesheet" type="text/css">

    <script type="text/javascript"
            src="<?php echo bower_url(); ?>jquery/dist/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css"
          href="<?php echo bower_url(); ?>bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css"
          href="<?php echo bower_url(); ?>bootstrap/dist/css/bootstrap-theme.min.css">

    <script type="text/javascript"
            src="<?php echo bower_url(); ?>bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- User CSS -->
    <?php echo css("general.css"); ?>
    <?php echo css("login.css"); ?>
</head>
<body class="login-wrapper">
<div class="container-fluid">

    @yield("content")

    <div class="row footer-guide">
        <div class="col-md-12" style="border-top: 2px dashed white;">
            <div class="row" style="padding-top: 20px;">
                <div class="col-md-2 footer-guide-element footer-guide-header">
                    <b>GIMANA CARANYA?</b>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-3">

                            <div class="row">
                                <div class="col-md-4 footer-guide-number">
                                    01
                                </div>

                                <div class="col-md-8 footer-guide-element">
                                    <b>MASUK DULU</b>
                                    <br/>
                                <span class="footer-guide-text-small">
                                    kalo kamu udah punya akun, langsung masuk aja.
                                    Kalo belum daftar dulu ya.
                                </span>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3">

                            <div class="row">
                                <div class="col-md-4 footer-guide-number">
                                    02
                                </div>

                                <div class="col-md-8 footer-guide-element">
                                    <b>DEPOSIT</b>
                                    <br/>
                                <span class="footer-guide-text-small">
                                    pastiin deposit kamu melebihi harga menu tertinggi yang kamu pesan.
                                </span>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3">

                            <div class="row">
                                <div class="col-md-4 footer-guide-number">
                                    03
                                </div>

                                <div class="col-md-8 footer-guide-element">
                                    <b>PILIH MENU</b>
                                    <br/>
                                <span class="footer-guide-text-small">
                                    deposit aman, lanjut pilih menu yang kamu suka beserta backupnya ya
                                </span>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3">

                            <div class="row">
                                <div class="col-md-4 footer-guide-number">
                                    04
                                </div>

                                <div class="col-md-8 footer-guide-element">
                                    <b>DUDUK MANIS</b>
                                    <br/>
                                <span class="footer-guide-text-small">
                                    insya Allah dalam setengah jam pesanan kamu akan sampai dengan selamat.
                                </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function() {

            // find out maximum footer guide element height.
            var maxFooterGuideHeight = 0;
            $(".footer-guide-number").each(function() {

                var elementHeight = $(this).height();
                if (elementHeight > maxFooterGuideHeight) {
                    maxFooterGuideHeight = elementHeight;
                }
            });

            $(".footer-guide-element").each(function() {

                var elementHeight = $(this).height();
                if (elementHeight > maxFooterGuideHeight) {
                    maxFooterGuideHeight = elementHeight;
                }
            });

            // center the footer guide header
            $(".footer-guide-number, .footer-guide-header").css("line-height", maxFooterGuideHeight.toString() + "px");

            // center the footer guide element
            $(".footer-guide-element").each(function() {

                var elementHeight = $(this).height();
                var paddingTop = (maxFooterGuideHeight - elementHeight) / 2;

                if (paddingTop >= 0) {
                    $(this).css("padding-top", paddingTop + "px");
                }
            });

            $("body").css("padding-bottom", $(".footer-guide").height().toString() + "px");

            // adjust padding bottom of body if footer guide is resized.
            $(window).resize(function() {
                $("body").css("padding-bottom", $(".footer-guide").height().toString() + "px");
            });
        });
    </script>

</div>
</body>
</html>