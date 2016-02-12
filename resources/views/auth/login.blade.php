@extends("layouts.auth")

@section("content")

<div class="row">
    <div class="
        col-lg-4 col-lg-offset-4
        col-md-4 col-md-offset-4
        col-sm-6 col-sm-offset-3
        col-xs-8 col-xs-offset-2
        login-box">

        {!! Form::open(["url" => "login"]) !!}

        <div class="row" id="main-logo" style="margin-bottom: 10px; padding-top: 100px;">
            <div class="col-md-12" style="text-align: center;">
                <?php echo img("img_logo_new.png", array("style" => "max-width: 250px; height: auto; image-rendering: auto;")); ?>
            </div>
        </div>

        <div class="row" style="margin-bottom: 30px;">
            <div class="col-md-12" style="text-align: center; font-size: 11pt;">
                Bangkitkan Ekonomi Rakyat
            </div>
        </div>

        <?php show_notification(); ?>

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
                <?php
                echo form_input(
                    array(
                        "name" => "email",
                        "class" => "col-lg-12 input-transparent-white",
                        "value" => set_value("email"),
                        "placeholder" => "Email",
                        "style" => "width: 98%;"
                    )
                );
                ?>
            </div>
        </div>

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
                <?php
                echo form_password(
                    array(
                        "name" => "password",
                        "class" => "col-md-12 input-transparent-white",
                        "placeholder" => "Password",
                        "style" => "width: 98%;"
                    )
                );
                ?>
            </div>
        </div>

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12" style="padding-top: 3px; text-align: center; font-size: 10pt;">

                <?php
                echo anchor(
                    base_url() ."password/request",
                    "Lupa password ya?",
                    array("style" => "color: #FEBD3D;")
                );
                ?>

            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
                <?php
                echo form_submit(
                    array(
                        "name" => "login",
                        "class" => "col-xs-12 button-yellow-white",
                        "value" => "YUK MASUK!"
                    )
                );
                ?>
            </div>
        </div>

        <div class="row" style="margin-bottom: 10px; padding-bottom: 20px;">
            <div class="col-md-12" style="text-align: center; font-size: 10pt;">
                <a href="<?php echo base_url() ."login/google"; ?>" class="button-red-white col-xs-12">
                    <i class="fa fa-google-plus" style="font-size: 14pt;"></i>&nbsp;&nbsp;MASUK DENGAN GOOGLE
                </a>

            </div>
        </div>

        <div class="row" style="margin-bottom: 10px; padding-bottom: 20px;">
            <div class="col-md-12" style="text-align: center; font-size: 10pt;">

                <?php
                echo anchor(
                    base_url() ."register",
                    "DAFTAR YUK",
                    array("class" => "button-black-white col-xs-12 ")
                );
                ?>

            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        var divHeight = $(".login-wrapper").height();
        var windowHeight = $(window).height();
        var paddingTopValue = (windowHeight - divHeight) / 2;

        if (paddingTopValue > 0) {
            $("#main-logo").css("padding-top", paddingTopValue.toString() +"px");
        }

        $("input[name=email]").focus();
    });
</script>

@endsection
