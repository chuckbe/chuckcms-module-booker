@php
$settings = ChuckSite::module('chuckcms-module-order-form')->settings;
@endphp

<script type="text/javascript">
var followup_url = "{{ route('cof.status') }}";
var a_token = "{{ Session::token() }}";
var payment_upfront = "{{ ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_upfront') }}";

$(document).ready(function() {
	statusOrder();
});

function statusOrder () {

	$.ajax({
        method: 'POST',
        url: followup_url,
        data: { 
        	order_number: $('input[name=order_number]').val(), 
        	_token: a_token
        }
    })
    .done(function(data) {
        if (data.status == "paid" || (data.status == "awaiting" && payment_upfront == '')){
        	$('.order-success').removeClass('d-none');
        } else if (data.status == "canceled" || (data.status == "awaiting" && payment_upfront == '1')){ 
        	$('.order-canceled').removeClass('d-none');
        } 
    });
}

</script>