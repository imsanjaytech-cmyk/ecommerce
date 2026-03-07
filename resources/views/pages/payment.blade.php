<!DOCTYPE html>
<html>
<head>
    <title>Processing Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>

<form name="razorpayForm" action="{{ route('payment.success') }}" method="POST">
    @csrf
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
</form>

<script>
var options = {
    "key": "{{ config('services.razorpay.key') }}",
    "amount": "{{ $amount }}",
    "currency": "INR",
    "name": "Shanas",
    "description": "Order Payment",
    "order_id": "{{ $order_id }}",

    "handler": function (response){

        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
        document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
        document.getElementById('razorpay_signature').value = response.razorpay_signature;

        document.razorpayForm.submit();
    },

    "theme": {
        "color": "#e63b5c"
    }
};

var rzp1 = new Razorpay(options);

window.onload = function(){
    rzp1.open();
};
</script>

</body>
</html>