<div class="col-md-3" style="margin-bottom: 20px; color: #797979;">

    <div class="row">
        <div class="col-xs-12" style="font-size: 14pt;">
            {!! Html::image("img/ic_grey.png") !!}
            Buka titipan
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div style="background: white; padding: 10px;">

                <form method="POST" class="form-horizontal" action="{{route('user.titip.restaurant.add')}}"
                      enctype="multipart/form-data">

                    <input type="hidden" name="_token" value="{!! csrf_token() !!}" />

                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-xs-12">
                            Pilih restoran
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12">

                            {!! Form::select("restaurant", $restaurantList, null, [
                                "name" => "restaurant",
                                "class" => "col-md-12",
                                "style" => "padding-left: 0%;"
                            ]) !!}

                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-xs-12">

                            Ongkos/restoran
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12">

                            <input type="text" name="cost" class="col-xs-12" value="0" />

                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12">

                            <button type="submit" class="button-orange-black col-xs-12" style="font-size: 11pt;">
                                <i class="fa fa-plus"></i> TAMBAH KE RESTO TUJUAN&nbsp;&nbsp;
                            </button>

                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>


</div>


<script type="text/javascript">

    var baseURL = "{{ url("/") }}";

    $(document).ready(function () {
    });
</script>