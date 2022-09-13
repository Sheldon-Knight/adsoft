<x-filament::page>

    <head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
            integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://js.yoco.com/sdk/v1/yoco-sdk-web.js"></script>
        <script>
            var public_key = '{{ config('yoco.public_key') }}';

            var yoco = new window.YocoSDK({
                publicKey: public_key
            });
        </script>
        <style>
            .overlay {
                opacity: 0.5;
                background: #000;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                position: fixed;

            }

            .overlay__inner {
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                position: absolute;
            }

            .overlay__content {
                left: 50%;
                position: absolute;
                top: 50%;
                transform: translate(-50%, -50%);
            }

            .spinner {
                width: 75px;
                height: 75px;
                display: inline-block;
                border-width: 2px;
                border-color: rgba(255, 255, 255, 0.05);
                border-top-color: rgb(18, 83, 2);
                animation: spin 1s infinite linear;
                border-radius: 100%;
                border-style: solid;
            }

            @keyframes spin {
                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    </head>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <div class="overlay" id="yoco-spinner-custom" style="display:none">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>

    <br>
    <x-filament::button id="checkout-button">
        Pay Now!
    </x-filament::button>
    <form wire:submit.prevent="submit">
        {{ $this->form }}





    </form>

    <script>
        $("#yoco-spinner-custom").css('display', 'none');

        $('#checkout-button')
            .css('display', 'unset')
            .on('click', function() {

                var price = {{ $record->invoice_total }};
                // the currency (must be ZAR)
                var currency = 'ZAR';
                // the name at the top of the popup (either shop or product)
                var product_title = "Invoice Number #" + "{{ $record->invoice_number }}";
                // the description of the purchase (product or product description)
                var product_description = "Subribing To The Premium Plan";

                yoco.showPopup({
                    amountInCents: price * 100,
                    // the currency (eg. ZAR/USD/GBP)
                    currency: currency,
                    // the name at the top of the popup (either shop or product)
                    name: product_title,
                    // the description of the purchase (product or product description)
                    description: product_description,

                    callback: function(chargeToken) {
                        // Pass back the token to the backend for verification
                        $.ajax({
                            // This is the URL to your backend
                            'url': '/yoco/charge',
                            'method': 'POST',
                            'dataType': 'json',
                            'headers': {
                                // necessary for laravel's anti x-site hacking functionality
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            'data': 'token=' + chargeToken.id +
                                '&amountInCents=' + (price * 100) +
                                "&currency=" + currency +
                                "&metadata[method]=" + "client_invoice" +
                                "&metadata[id]=" + "{{ $record->id }}",
                            beforeSend: function() {
                                $("#yoco-spinner-custom").css('display', 'unset');
                            },
                            'success': function(data) {
                                $("#yoco-spinner-custom").css('display', 'none');
                                swal({
                                    title: "Purchase successful",
                                    text: "You Have Paid Your Invoice",
                                    icon: "success",
                                    button: "OK",
                                }).then(function() {
                                    location.reload();
                                });
                            },
                            'error': function(result) {
                                $("#yoco-spinner-custom").css('display', 'none');
                                error = result.responseJSON;
                                if (error) {
                                    if (error.errors) {
                                        log_activity("Failed to charge " + currency + " " +
                                            price + " : " + error.message);
                                        $.each(error.errors, function(key, value) {
                                            log_activity("Validation: " + key + " : " +
                                                value[0]);
                                        });
                                    } else if (error.charge_error) {
                                        log_activity("Failed to charge " + currency + " " +
                                            price + " : " + error.charge_error
                                            .displayMessage);
                                    } else {
                                        log_activity("Failed to charge " + currency + " " +
                                            price + " : Unknown Error");
                                    }
                                } else {
                                    log_activity("Failed to charge " + currency + " " + price +
                                        " : Unknown Error");
                                }
                                console.log(error);
                                // Popup notification
                                swal({
                                    title: "Purchase failed",
                                    text: "Something went wrong and we couldn't get this for you",
                                    icon: "error",
                                    button: "OK",
                                });
                            },
                            'complete': function(result) {
                                $("#yoco-spinner-custom").css('display', 'none');
                                log_activity("Backend server call complete");
                            }
                        });
                    },
                    onCancel: function() {
                        $("#yoco-spinner-custom").css('display', 'none');
                    }
                });
            });
    </script>
</x-filament::page>
