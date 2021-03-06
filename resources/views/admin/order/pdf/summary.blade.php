@extends("layouts.flat")

@section("content")

    Travel {{ $travel }}, tanggal {!! date("Y-m-d") !!}

    <table style="margin-top: 20px;">
        <tr>

        @foreach($orderElementByPriorityAndRestaurant as $priority => $orderElementByPriority)
            <td width="50%" style="border-left: 1px solid black; padding-left: 10px; vertical-align: top;">
                PRIORITAS {{ $priority + 1 }} ------------------------- <br/><br/>

                @foreach($orderElementByPriority as $restaurant => $orderElementByRestaurant)

                    <b>{{ $orderElementByRestaurant[0]->restaurantObject->name }}</b><br/>

                    @foreach($orderElementByRestaurant as $orderElement)

                        &bull; (#{{ $orderElement->order->id }}) {{ $orderElement->menuObject->name }}
                        ({{ $orderElement->amount }} buah) {{ $orderElement->preference }}
                        [ {{ $orderElement->order->user->name }} ]<br/>

                    @endforeach

                    <br/>

                @endforeach

                <br/>
                ##############################
            </td>
        @endforeach
        </tr>
    </table>

@endsection