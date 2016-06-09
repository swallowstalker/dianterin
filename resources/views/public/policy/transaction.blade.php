@extends("layouts.policy")

@section("policy-content")

    <ol>

        <li class="policy-element">Demi keamanan para pengguna Dianter.in, setiap pembayaran wajib dilakukan melalui
            sistem pembayaran Dianter.in, yaitu melalui deposit. Deposit dapat di setor tunai kepada administrator
            Dianter.in.
        </li>
        <li class="policy-element">Setelah pesanan tiba, biaya total pemesanan akan dikurangi dari deposit saat pengguna
            melakukan konfirmasi “sudah terima”, atau secara otomatis jika pengguna belum melakukan konfirmasi hingga 2
            jam sejak pesanan diterima.
        </li>
        <li class="policy-element">Jika pengguna belum menerima pesanan tetapi telah diberikan notifikasi pesanan sudah
            tiba, pengguna wajib melakukan konfirmasi “belum terima”. Tanpa konfirmasi “belum terima”, pesanan akan
            dianggap sudah diterima, dan deposit pengguna akan dikurangi sesuai biaya total pemesanan.
        </li>

    </ol>

@endsection