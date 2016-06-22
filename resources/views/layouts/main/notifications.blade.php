
<div class="row">
    <div class="col-md-12">

        {{-- comes from view composer --}}
        @foreach($notificationBarMessages as $messageOwnedByUser)

            <div class="notification-element">
                <div style="" class="alert alert-info alert-dismissible notification-row">
                    <div class="alert-icon notification-icon">
                        <i class="fa fa-bell"></i>
                    </div>
                    <div class="notification-content">
                        {!! $messageOwnedByUser->message->content !!}
                    </div>
                    <div>
                        <button type="button" class="close notification-dismiss"
                                data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {!! Form::hidden("user-info-popup-notification-id", $messageOwnedByUser->id) !!}
                    </div>
                </div>
            </div>

        @endforeach

        <script type="text/javascript">

            var messageDismissURL = "{{ route("user.message.dismiss") }}";
            var csrfHash = '{!! csrf_token() !!}';

            // if user dismiss the notif, system will make it disappear.
            $(".notification-dismiss").click(function () {
                sendMessageDismissRequest($(".notification-element"));
            });
        </script>

    </div>
</div>