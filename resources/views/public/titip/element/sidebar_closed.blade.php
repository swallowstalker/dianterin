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
                            Rp <span class="expected-income">Expected Income</span>
                        </h4>

                        <h5>Perkiraan Total Pembelian Makanan</h5>
                        <h4>
                            Rp <span class="expected-subtotal">Perkiraan Total Dibayar</span>
                        </h4>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-12">

            <button class="button-red-white finish-travel col-xs-12" style="font-size: 11pt;">
                <b>SELESAIKAN PENITIPAN &nbsp;&nbsp;  <i class="fa fa-arrow-right"></i></b>
            </button>

        </div>
    </div>


</div>


<script type="text/javascript">

    var baseURL = "{{ url("/") }}";

    $(document).ready(function () {
        $("button.finish-travel").click(function () {
            $("form#finish-form-travel").submit();
        });
    });
</script>