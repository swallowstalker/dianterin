<div class="navbar-header">
    <a class="navbar-brand" href="{!! url("/") !!}">
        {!! Html::image(
            "img/img_logo_new.png",
            "Dianterin",
            ["style" => "height: 100%;"])
        !!}
    </a>
</div>
<!-- /.navbar-header -->

<ul class="nav navbar-top-links navbar-right" style="text-align: right;">
    <li class="dropdown" style="margin-right: 20px; color: #FFC335;">
        <img src="{!! url("/img") !!}/ic_grey.png" />
        {!! number_format($orderTransactionTotal, 0, ",", ".") !!}
    </li>

    <li class="dropdown" style="margin-right: 20px;">
        {!! Html::link("/", "Pesan Baru", ["style" => 'line-height: 40px;']) !!}
    </li>

    <li class="dropdown" style="margin-right: 20px;">
        {!! Html::link("transaction/history", "Catatan Transaksi", ["style" => 'line-height: 40px;']) !!}
    </li>


    @if(Auth::user()->role == \App\User::ROLE_ADMIN)

        <li class="dropdown" style="margin-right: 20px;">
            {!! Html::link("admin/order", "Panel Admin", ["style" => 'line-height: 40px;']) !!}
        </li>

    @endif


    <li class="dropdown" style="margin-right: 20px;">
        <a href="{!! url("/") !!}/logout" style="line-height: 40px;">
            <i class="fa fa-power-off"></i>
        </a>
    </li>
</ul>
<!-- /.navbar-top-links -->