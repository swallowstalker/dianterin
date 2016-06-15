@extends("layouts.main")


@section("style")

    <!-- JQuery-UI Theme CSS -->
    <link href="{!! asset("/") !!}bower_components/jquery-ui/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">

    <link href="{!! asset("/") !!}css/public/titip.css" rel="stylesheet" type="text/css">

@endsection


@section("javascript")

    <!-- JQuery-UI JavaScript -->
    <script src="{!! asset("/") !!}bower_components/jquery-ui/jquery-ui.min.js"></script>

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

            @foreach($pendingTransactionGroupedByRestaurant as $restaurantName => $pendingTransactionList)

                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-12" style="background: white; padding: 10px; font-size: 12pt;">

                        <section class="head-area">
                            <span style="font-weight: bold; font-size: 15pt;">
                                {{ $restaurantName }}
                            </span>
                        </section>

                        <section class="order-area" style="font-size: 12pt;">

                            @foreach($pendingTransactionList as $pendingTransaction)

                                <section class="order-element">

                                    <div>

                                        {{ $pendingTransaction->menu }}<br/>
                                        @if (! empty($pendingTransaction->adjustment))
                                            tambahan: Rp {{ number_format($pendingTransaction->adjustment, 0, ",", ".") }}
                                            {{ $pendingTransaction->adjustment_info }}
                                        @endif


                                        <span class="pull-right">
                                            Rp {{ number_format($pendingTransaction->price + $pendingTransaction->adjustment, 0, ",", ".") }}
                                        </span>

                                    </div>

                                    <div style="font-size: 11pt;">
                                        {{ $pendingTransaction->user->name }}
                                    </div>
                                </section>

                            @endforeach

                        </section>

                    </div>
                </div>

            @endforeach

        </div>

        {{-- sidebar order list --}}
        @include("public.titip.element.sidebar_finished")

    </div>

@endsection