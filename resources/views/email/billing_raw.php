<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Pesanan Anda</title>
<link href="/css/email.css" media="all" rel="stylesheet" type="text/css" />
</head>

<body itemscope itemtype="http://schema.org/EmailMessage">

<table class="body-wrap">
	<tr>
		<td></td>
		<td class="container" width="600">
			<div class="content">
				<table class="main" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="content-wrap aligncenter">
							<table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block aligncenter">
										<img src="/img/img_logo_new_black.png" style="width: 50%;" />
                                    </td>
                                </tr>
								<tr>
									<td class="content-block">
										<h1 class="aligncenter">
											Rp {{ $total }}
										</h1><br/>
										akan dibayar dari deposit anda.
									</td>
								</tr>
								<tr>
									<td class="content-block">
										<h2 class="aligncenter">Terima kasih telah menggunakan jasa dianter.in</h2>
									</td>
								</tr>
								<tr>
									<td class="content-block aligncenter">
										<table class="invoice">
											<tr>
												<td>
													{{ $user->name }}<br>Invoice #{{ implode(",", $transactionList->pluck("id")->all()) }}<br>
                                                    {{ $transactionList->first()->created_at }}
												</td>
											</tr>
											<tr>
												<td>
													<table class="invoice-items" cellpadding="0" cellspacing="0">



														<tr class="total" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
															<td class="alignright" width="80%" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top">Total</td>
															<td class="alignright" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top">
																Rp {{ $total }}</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>

								@if (! empty($notFoundOrderList))
								<tr>
									<td style="padding-bottom: 30px;">
										<p>
											Pesanan berikut tidak dapat kami antarkan karena tidak ada:
										</p>
										<table>
											@foreach($notFoundOrderList as $notFoundOrder)
											<tr>
												<td>
													{{ $notFoundOrder->elements()->first()->restaurantObject->name }},
													{{ $notFoundOrder->elements()->first()->menuObject->name }}
												</td>
											</tr>
											@endforeach
										</table>
									</td>
								</tr>
								@endif

								<tr>
									<td class="content-block aligncenter">
										Silakan lakukan konfirmasi apabila anda belum menerima pesanan
										di website <a href="https://dianter.in">dianter.in</a>
									</td>
								</tr>

								<tr>
									<td class="content-block aligncenter">
										Selamat menikmati pesanan anda
										<span style="font-size: 18pt; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; margin: 0;">☺</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div class="footer">
					<table width="100%">
						<tr>
							<td class="aligncenter content-block" style="padding-bottom: 20px;">
								Mohon beritahu kami bila muncul permasalahan<br/>
								ke Customer Service kami di <a href="mailto:nganterin@dianter.in">nganterin@dianter.in</a>
							</td>
						</tr>
						<tr>
							<td class="aligncenter content-block" style="padding-bottom: 20px;">
								Copyright &copy; 2014 - {{ date("Y") }} Dianterin
							</td>
						</tr>
					</table>
				</div>
			</div>
		</td>
		<td></td>
	</tr>
</table>

</body>
</html>
