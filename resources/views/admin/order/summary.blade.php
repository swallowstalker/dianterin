@extends("layouts.flat")


@section("content")

    {!! Form::open(["url" => "admin/order/summary", "method" => "GET"]) !!}

    Travel
    {!! Form::select("travel", $openTravels, $travel) !!}
    <button type="submit">
        Filter
    </button>

    {!! Form::close() !!}
    <br/>
    <br/>

    @foreach($orderElementByPriorityAndRestaurant as $priority => $orderElementByPriority)

        PRIORITAS {{ $priority + 1 }} ------------------------- <br/><br/>

        @foreach($orderElementByPriority as $restaurant => $orderElementByRestaurant)

            <b>{{ $orderElementByRestaurant[0]->restaurantObject->name }}</b><br/>

            @foreach($orderElementByRestaurant as $pendingTransaction)

                &bull; (#{{ $pendingTransaction->order->id }}) {{ $pendingTransaction->menuObject->name }}
                ({{ $pendingTransaction->amount }} buah) {{ $pendingTransaction->preference }}<br/>

            @endforeach

            <br/>

        @endforeach

        <br/>
        ##############################
        <br/><br/>

    @endforeach

@endsection