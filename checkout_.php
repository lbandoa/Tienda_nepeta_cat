<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Document</title>

    <script src="https://www.paypal.com/sdk/js?client-id=AXLwkzP1nvRnhBGlMOovoNAjTvS3tA9DM0CENO0LzWAel6JaJC2ermBN2vWZE16nPlQ-8prCmqMD91OP&currency=COP"></script>
</head>
<body>
    <div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({
            style:{
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            }, 
            createOrder: function(data, actions){
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: 1000
                        }
                    }]
                });
            },
            onApprove: function(data, actions){
                actions.order.capture().then(function(detalles){
                    window.location.href="completado.html"
                });
            },
            onCancel: function(data) {
                alert("Pago cancelado");
                console.log(data);
            }
        }).render('#paypal-button-container');
    </script>

</body>   
</html>