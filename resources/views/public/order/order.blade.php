@extends("layouts.main")


@section("style")

    <!-- DataTables CSS -->
    <link href="{!! asset("/") !!}bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{!! asset("/") !!}bower_components/datatables-responsive/css/responsive.dataTables.css" rel="stylesheet">

    <!-- DataTables TableTools CSS -->
    <link href="{!! asset("/") !!}bower_components/datatables-tabletools/css/dataTables.tableTools.css" rel="stylesheet">


    <!-- JQuery-UI Theme CSS -->
    <link href="{!! asset("/") !!}bower_components/jquery-ui/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">

@endsection


@section("javascript")

    <!-- DataTables JavaScript -->
    <script src="{!! asset("/") !!}bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="{!! asset("/") !!}bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="{!! asset("/") !!}bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>


    <!-- JQuery-UI JavaScript -->
    <script src="{!! asset("/") !!}bower_components/jquery-ui/jquery-ui.min.js"></script>

@endsection


@section("content")

    <div class="row">
        <div class="col-md-9" style="margin-bottom: 20px; font-size: 14pt; color: #797979;">
            <div class="row">
                <div class="col-md-12">
                    {!! Html::image("img/ic_grey.png") !!}
                    Menu pesanan
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding: 10px; background-color: white;">


                    @foreach($restaurantList as $key => $restaurant)

                        @if ($key % 2 == 0)
                            <div class="row">
                        @endif

                                <div class="col-md-6" style="margin-bottom: 10px;">
                                    <div style="border: 1px solid #FFC335;">
                                        <div class="row">
                                            <div class="col-sm-4">

                                                {!! Html::image(
                                                    env("DEFAULT_PATH") ."img/". $restaurant->image,
                                                    $restaurant->name,
                                                    ["style" => "width: 100%;"]
                                                    ) !!}
                                            </div>
                                            <div class="col-sm-8" style="padding-top: 10px; padding-bottom: 10px; padding-left: 20px;">
                                                <div class="row">
                                                    <div class="col-xs-12" style="color: black;">
                                                        <b>
                                                            {{ $restaurant->name }}
                                                        </b>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12" style="font-size: 10pt;">
                                                        Rp {!! number_format($restaurant->min_price, 0, ",", ".") !!}
                                                        &nbsp;-&nbsp;
                                                        Rp {!! number_format($restaurant->max_price, 0, ",", ".") !!}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12" style="font-size: 10pt; color: black;">
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-xs-3" style="font-size: 10pt;">
                                                        <a href="#food-popup" class="food-popup-button open-popup">
                                                            <button class="button-orange-black">
                                                                Menu
                                                            </button>
                                                        </a>
                                                        <input type="hidden" value="{{ $restaurant->id }}" />
                                                        <input type="hidden" value="{{ $restaurant->name }}" />
                                                    </div>
                                                    <div class="col-xs-9" style="font-size: 10pt;">

                                                        @if ($restaurant->open_status)

                                                            <div style="color: green;">
                                                                <i class="fa fa-home"></i> Restoran Buka
                                                            </div>

                                                        @else

                                                            <div style="color: lightgrey;">
                                                                <i class="fa fa-home"></i> Restoran Tutup
                                                            </div>

                                                        @endif

                                                        @if (in_array($restaurant->id, $restaurantWhoseCourierIsAvailable))
                                                            <div style="color: green; margin-top: 3px;">
                                                                <i class="fa fa-user"></i> Pengantar Tersedia
                                                            </div>
                                                        @else
                                                            <div style="color: lightgrey; margin-top: 3px;">
                                                                <i class="fa fa-user"></i> Pengantar Tidak Ada
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        @if($key % 2 == 1 || $key == count($restaurantList) - 1)
                            </div>
                        @endif

                    @endforeach

                </div>
            </div>
        </div>


        {{-- sidebar order list --}}
        @include("public.order.sidebar_ordered_menu")

    </div>

    @include("public.order.popup.food_popup")

    @include("public.order.popup.feedback_popup")

    @include("public.order.popup.backup_popup")

    @include("public.order.popup.error_popup")


    <script type="text/javascript">

        var validationError = '{{ session("errorFlag", 0) }}';
        validationError = parseInt(validationError);
        var orderForm = $("form#new-order");

        $(document).ready(function () {

            if (validationError) {

                $(".validation-error-popup-button").click();

            } else {

                showBackupPopup();
            }
        });
    </script>

@endsection