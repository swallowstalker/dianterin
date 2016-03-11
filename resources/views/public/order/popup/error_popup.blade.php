
<a href="#validation-error-popup" class="validation-error-popup-button open-popup" style="visibility: hidden;"></a>
<div id="validation-error-popup" class="white-popup mfp-hide" style="max-width: 300px; text-align: center;">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12 popup-header popup-element-header" style="font-size: 16pt;">
            Pemberitahuan
        </div>
    </div>
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12" id="validation-message">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 right">
            <button class="button-orange-black yes-confirm">OK</button>
        </div>
    </div>
</div>

<script type="text/javascript">

    var validationMessage = "{!! session("errorMessage", "") !!}";

    $(document).ready(function () {

        // register triggers for magnific popup validation-error balance
        $(".validation-error-popup-button").magnificPopup({
            mainClass: 'mfp-fade',
            callbacks: {
                beforeOpen: function() {
                    $("#validation-error-popup #validation-message").html(validationMessage);
                }
            }
        });

        $("#validation-error-popup .yes-confirm").click(function () {
            $.magnificPopup.close();
        });
    });

</script>