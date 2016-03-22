@extends("layouts.flat")


@section("content")

    @foreach($orderElementByPriorityAndRestaurant as $priority => $orderElementByPriority)

        PRIORITAS {{ $priority + 1 }} ------------------------- <br/><br/>

        @foreach($orderElementByPriority as $restaurant => $orderElementByRestaurant)

            <b>{{ $orderElementByRestaurant[0]->restaurantObject->name }}</b><br/>

            @foreach($orderElementByRestaurant as $orderElement)

                &bull; (#{{ $orderElement->order->id }}) {{ $orderElement->menuObject->name }}
                ({{ $orderElement->amount }} buah) {{ $orderElement->preference }}<br/>

            @endforeach

            <br/>

        @endforeach

        <br/>
        ##############################
        <br/><br/>

    @endforeach

@endsection