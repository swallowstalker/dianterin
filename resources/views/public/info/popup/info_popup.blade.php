
<a href="#user-info-popup" class="user-info-popup-button open-popup" style="visibility: hidden;"></a>
<div id="user-info-popup" class="white-popup mfp-hide" style="max-width: 300px; text-align: center;">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12 popup-header popup-element-header" style="font-size: 16pt;">
            Info
        </div>
    </div>
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12" id="user-info-message">

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

        var messageURL = "{{ route("user.message.last") }}";
        var messageDismissURL = "{{ route("user.message.dismiss") }}";
        var csrfHash = "{!! csrf_token() !!}";

        // register triggers for magnific popup validation-error balance
        $(".user-info-popup-button").magnificPopup({
            mainClass: 'mfp-fade',
            callbacks: {
                close: function () {

                    // dismissing popup notification
                    var notifID = $("#user-info-popup")
                            .find("input[name=user-info-popup-notification-id]")
                            .val();

                    $.ajax({
                        url: messageDismissURL,
                        type: "POST",
                        data: {
                            _token: csrfHash,
                            id: notifID
                        }
                    });

                }
            }
        });

        $.ajax({
            url: messageURL,
            type: "GET",
            success: function (data) {

                if (data.message != null) {
                    $("#user-info-message").html(data.message);
                    $("#user-info-popup")
                            .find("input[name=user-info-popup-notification-id]")
                            .val(data.id);

                    $(".user-info-popup-button").click();
                }
            }
        });

        $("#user-info-popup #user-info-yes-confirm").click(function () {
            $.magnificPopup.close();
        });
    });

</script>