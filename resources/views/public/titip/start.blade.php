@extends("layouts.main")


@section("style")

    <!-- JQuery-UI Theme CSS -->
    <link href="{!! asset("/") !!}bower_components/jquery-ui/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">

    <!-- Magnific Popup CSS -->
    <link href="{!! asset("/") !!}bower_components/magnific-popup/dist/magnific-popup.css" rel="stylesheet" type="text/css">

@endsection


@section("javascript")

    <!-- JQuery-UI JavaScript -->
    <script src="{!! asset("/") !!}bower_components/jquery-ui/jquery-ui.min.js"></script>

    <!-- Magnific Popup JavaScript -->
    <script src="{!! asset("/") !!}bower_components/magnific-popup/dist/jquery.magnific-popup.min.js"></script>

@endsection


@section("content")

    <div class="row">
        <div class="col-md-9" style="margin-bottom: 20px; font-size: 14pt; color: #797979;">

            <div class="row">
                <div class="col-md-12" style="font-size: 14pt;">
                    {!! Html::image("img/ic_grey.png") !!}
                    Daftar restoran
                </div>
            </div>


            @if(session()->has("titipRestaurantList"))

                @foreach(session()->get("titipRestaurantList") as $restaurant)

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12" style="background: white; padding: 10px; font-size: 12pt;">

                            <section class="head-area">
                                <span style="font-weight: bold;">
                                    {{ $restaurant->data->name }}
                                </span>
                                <span class="pull-right">
                                    Ongkos Rp {{ number_format($restaurant->cost, 0, ",", ".") }}
                                </span>
                            </section>

                        </div>
                    </div>

                @endforeach

            @else

                <div class="row">
                    <div class="col-md-12" style="background: white; padding: 10px; font-size: 12pt;">
                        Silakan tambah restoran yang ingin anda kunjungi
                    </div>
                </div>

            @endif


        </div>


        {{-- sidebar order list --}}
        @include("public.titip.element.sidebar_start")

    </div>


    <script type="text/javascript">

    </script>

@endsection