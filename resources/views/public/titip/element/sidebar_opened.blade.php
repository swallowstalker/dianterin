<div class="col-md-3" style="margin-bottom: 20px; color: #797979;">

    <div class="row">
        <div class="col-xs-12" style="font-size: 14pt;">
            {!! Html::image("img/ic_grey.png") !!}
            Status
        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">
            <div style="background: white; padding: 10px;">

                <div class="row" style="margin-bottom: 5px;">
                    <div class="col-xs-12">
                        Ringkasan
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">

            <a href="{!! route("user.titip.open") !!}" class="button-red-white publish-travel col-xs-12" style="font-size: 11pt;">
                TUTUP PENITIPAN &nbsp;&nbsp;  <i class="fa fa-arrow-right"></i>
            </a>

        </div>
    </div>


</div>


<script type="text/javascript">

    var baseURL = "{{ url("/") }}";

    $(document).ready(function () {
    });
</script>