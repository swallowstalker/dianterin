
<div class="row">
    <div class="col-md-12">

        @foreach($notifications as $notification)

            <div class="notification-element">
                <div style="" class="alert alert-info alert-dismissible notification-row">
                    <div class="alert-icon notification-icon">
                        <i class="fa fa-bell"></i>
                    </div>
                    <div class="notification-content">
                        {!! $notification->message !!}
                    </div>
                    <div>
                        <button type="button" class="close notification-dismiss"
                                data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {!! Form::hidden("notification_id", $notification->id) !!}
                    </div>
                </div>
            </div>

        @endforeach

        <script type="text/javascript">

            var baseURL = '{!! url("/") !!}';
            var csrfHash = '{!! csrf_token() !!}';

            // if user dismiss the notif, system will make it disappear.
            $(".notification-dismiss").click(function () {

                var notifID = $(this).next().val();
                console.log(notifID);

                $.ajax({
                    url: baseURL + '/notification/dismiss',
                    type: "POST",
                    data: {
                        _token: csrfHash,
                        id: notifID
                    },
                    success: function (data) {

                    }
                });
            });
        </script>

    </div>
</div>