<a href="#feedback-popup" class="feedback-popup-button open-popup" style="visibility: hidden;"></a>
<div id="feedback-popup" class="white-popup mfp-hide" style="max-width: 600px;">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12 popup-header popup-element-header" style="font-size: 16pt;">
            Tanggapan
        </div>
    </div>

    {{ Form::open(["url" => "order/received", "id" => "feedback"]) }}


    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">
            Tanggapan dari anda sangat berarti untuk membuat pelayanan kami semakin baik.
            Jika anda berkenan, silakan isi form tanggapan di bawah ini.
        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">

            {{ Form::textarea(
                "feedback",
                "",
                [
                    "style" => 'width: 100%; resize: none;',
                    "rows" => 2,
                    "placeholder" => "Tanggapan"
                ]) }}

            {{ Form::hidden("id") }}

        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">

            {{ Form::submit("Kirim Feedback", [
                "name" => "save",
                "class" => "button-yellow-white pull-right"
                ]) }}
        </div>
    </div>

    {{ Form::close() }}

</div>

<script type="text/javascript">

    $(document).ready(function () {

        var currentOrder = 0;

        $(".feedback-popup-button").click(function () {
            currentOrder = $(this).next().val();
        });

        // register triggers for magnific popup
        $(".feedback-popup-button").magnificPopup({
            closeOnBgClick: false,
            mainClass: 'mfp-fade',
            callbacks: {
                beforeOpen: function () {
                    $("form#feedback").find("input[name=id]").val(currentOrder);
                },
                close: function () {

                }
            }
        });
    });
</script>