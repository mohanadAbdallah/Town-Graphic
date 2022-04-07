<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
<script src="https://paytabs.com/express/v4/paytabs-express-checkout.js"></script>
<script type="text/javascript">
    var Config = {
        settings: {
            secret_key: "rcqiNWZaFCmNA4G2fXWkfXq98IfHtS4V4udSqd26QpTrtztIxp3MntWwcbeG3iB8NP0e32kTsdkWNOFkeI7Bc38pem5iEHqswqGz",
            merchant_id: "10072346",
            url_redirect: "{{route('order.callback')}}",
            amount: "{{intval($cart->total_amount) + intval($orderFees->value ?? 0) }}",
            title: "{{$user->name ?? ''}} ) ",
            currency: "SAR",
            product_names: "{{$cart->items}}",
            order_id: "cart-{{$cart->id}}-{{$user->id}}",
            ui_type: "iframe",
            is_popup: "true",
            ui_show_header: "true"
        },
        customer_info: {
            first_name: "{{$user->name}}",
            last_name: "{{$user->name}}",
            phone_number: "{{$user->mobile}}",
            email_address: "{{$user->email}}",
            card_street: "dsdsd",
            country_code: "966"
        }
        ,billing_address: {
            full_address: "Business Bay",
            city: "Juffair",
            state: "Manama",
            country: "BHR",
            postal_code: "12345"
        }
    }
    Paytabs.initWithIframe(document.body,Config);

</script>

</body>
</html>
