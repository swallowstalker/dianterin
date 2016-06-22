

@if (! empty($popupMessage))

    <a href="#user-info-popup" class="user-info-popup-button open-popup" style="visibility: hidden;"></a>
    <div id="user-info-popup" class="white-popup mfp-hide" style="max-width: 300px; text-align: center;">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-12 popup-header popup-element-header" style="font-size: 16pt;">
                Info
            </div>
        </div>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-12" id="user-info-message">
                {!! $popupMessage->message->content !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 right">
                <button class="button-orange-black" id="user-info-yes-confirm">OK</button>
                <input type="hidden" name="user-info-popup-notification-id" value="" />
            </div>
        </div>
    </div>

    <script type="text/javascript">

        var validationMessage = "{!! session("errorMessage", "") !!}";

        $(document).ready(function () {

            // register triggers for magnific popup validation-error balance
            $(".user-info-popup-button").magnificPopup({
                mainClass: 'mfp-fade',
                callbacks: {
                    close: function () {
                        sendMessageDismissRequest($("#user-info-popup"));
                    }
                }
            });

            $(".user-info-popup-button").click();

            $("#user-info-popup #user-info-yes-confirm").click(function () {
                $.magnificPopup.close();
            });
        });

    </script>

@endif