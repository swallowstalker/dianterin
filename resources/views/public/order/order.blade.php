@extends("layouts.main")

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

                    <div class="row">

                        @foreach($restaurantList as $restaurant)

                            <div class="col-md-6" style="margin-bottom: 10px;">
                                <div style="border: 1px solid #FFC335;">
                                    <div class="row">
                                        <div class="col-sm-4">

                                            {!! Html::image(
                                                "img/restaurant/". $restaurant->image,
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
                                                    <b>{{ $restaurant->total_menu }} menu</b>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-xs-12" style="font-size: 10pt;">
                                                    <a href="#food-popup" class="food-popup-button open-popup button-orange-black">
                                                        Lihat Menu
                                                    </a>
                                                    <input type="hidden" value="{{ $restaurant->id }}" />
                                                    <input type="hidden" value="{{ $restaurant->name }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach

                    </div>

                </div>
            </div>
        </div>


        {{-- sidebar order list --}}
        @include("public.order.ordered_menu")

    </div>

@endsection