<section class="section" id="cof_followupSection">
	<div class="container">
		<div class="row">
			<div class="col order-success d-none">
				<h1>Bedankt {{ $order->entry['first_name'] }}</h1>
				<p>Bedankt voor uw bestelling. Uw bestelling wordt zo klaargemaakt. U krijgt zodadelijk per e-mail nog een bevestiging. Heeft u nog vragen? Dan kan u ons steeds contacteren!</p>
			</div>
			<div class="col order-canceled d-none">
				<h1>Oops {{ $order->entry['first_name'] }}</h1>
				<p>Er is helaas iets misgegaan met uw betaling. Wilt u het nog eens proberen?</p>
				<p><a href="{{ route('cof.pay', ['order_number' => $order->entry['order_number']]) }}">Klik hier om het nog eens te proberen.</a></p>
				<p>Heeft u nog vragen over uw bestelling of betaling? Dan kan u ons steeds contacteren!</p>
			</div>
		</div>
		<input type="hidden" name="order_number" value="{{ $order->entry['order_number'] }}">
	</div>
</section>