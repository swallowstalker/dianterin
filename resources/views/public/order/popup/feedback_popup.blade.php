<a href="#feedback-popup" class="feedback-popup-button open-popup" style="visibility: hidden;"></a>
<div id="feedback-popup" class="white-popup mfp-hide" style="max-width: 600px;">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12 popup-header popup-element-header" style="font-size: 16pt;">
            Tanggapan
        </div>
    </div>

    <?php echo form_open("order/received", array("id" => "feedback")); ?>


    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">
            Tanggapan dari anda sangat berarti untuk membuat pelayanan kami semakin baik.
            Jika anda berkenan, silakan isi form tanggapan di bawah ini.
        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">

            <?php
            echo form_textarea(
                    array(
                            "name" => "feedback",
                            "style" => 'width: 100%; resize: none;',
                            "rows" => 2,
                            "placeholder" => "Tanggapan"
                    )
            );
            ?>

            <?php echo form_hidden("order_id"); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
            echo form_submit(
                    array(
                            "name" => "save",
                            "class" => "button-yellow-white pull-right"
                    ),
                    "Kirim Feedback"
            );
            ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>