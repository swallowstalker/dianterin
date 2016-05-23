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
                        <h3>Ringkasan</h3>

                        <h5>Perkiraan Total Ongkos</h5>
                        <h4>
                            Rp {{ number_format($expectedIncome, 0, ",", ".") }}
                        </h4>

                        <h5>Perkiraan Total Pembelian Makanan</h5>
                        <h4>
                            Rp {{ number_format($totalPaymentWhichUserBorrow, 0, ",", ".") }}
                        </h4>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">

            <a href="{!! route("user.titip.close") !!}" class="button-red-white publish-travel col-xs-12" style="font-size: 11pt;">
                <b>TUTUP PENITIPAN &nbsp;&nbsp;  <i class="fa fa-arrow-right"></i></b>
            </a>

        </div>
    </div>


</div>


<script type="text/javascript">

    var baseURL = "{{ url("/") }}";

    $(document).ready(function () {
    });
</script>