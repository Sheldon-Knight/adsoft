<div>

    <div class="overlay" id="yoco-spinner-custom" style="display:none">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>


    <x-filament::page>
        <header>
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
        </header>

        @php
            $subscription = cache()->get('subscription');
            $plan = cache()->get('current_plan');
            $hasExpired = cache()->get('hasExpired');
        @endphp



        <div class="container mx-auto px-4">
            <div class="flex flex-row gap-3 items-center justify-between">

                <x-filament::card class="w-full">

                    <h2 class="text-xl text-center font-semibold tracking-tight filament-card-heading">
                        Basic Plan
                    </h2>
                    <P class="text-sm text-center">
                        The Very Basic To Get You Started
                    </P>

                    <x-filament::hr />
                    <h4 class="text-md text-center font-semibold tracking-tight filament-card-heading">
                        <b><u>Features</u></b>
                    </h4>
                    <ul class="text-md text-center">

                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Users Roles & Permissions</p>
                        </li>

                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Jobs & Instructions</p>
                        </li>

                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Invoices & Quotes</p>
                        </li>

                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Attendance</p>
                        </li>

                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Leave Applications</p>
                        </li>


                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#E11D48" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <p><del>Comment System</del></p>
                        </li>

                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#E11D48" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <p><del>Banking Manager</del></p>
                        </li>

                    </ul>

                    <x-filament::hr />

                    <div class="text-center">
                        <button type="submit"
                            class="inline-flex items-center justify-center py-1 gap-1
                                font-medium rounded-lg border 
                                transition-colors focus:outline-none 
                                focus:ring-offset-2 focus:ring-2 
                                focus:ring-inset filament-button 
                                dark:focus:ring-offset-0 min-h-[2.25rem]
                                px-4 text-sm text-white 
                                shadow focus:ring-white
                                border-transparent
                               {{ $plan === 'Basic' ? 'bg-danger-600 hover:bg-danger-500 focus:bg-danger-700 focus:ring-offset-danger-700' : 'bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700' }}"
                            id="{{ $plan === 'Basic' ? 'renew' : 'subscribe-to-basic-button' }}">

                            <span class="flex items-center gap-1">
                                <span class="">
                                    {{ $plan === 'Basic' ? 'Renew Plan' : 'Choose Plan' }}
                                </span>
                            </span>

                        </button>
                        @if ($plan === 'Basic')
                            <p>Expires In {{ now()->diffInDays(cache()->get('subscription')->expired_at) }} Days</p>
                        @endif
                    </div>


                </x-filament::card>

                <x-filament::card class="w-full">
                    <h2 class="text-xl text-center font-semibold tracking-tight filament-card-heading">
                        Premium
                    </h2>
                    <P class="text-sm text-center">
                        Everything You Need For A Full Office Management System
                    </P>

                    <x-filament::hr />

                    <h4 class="text-md text-center font-semibold tracking-tight filament-card-heading">
                        <b><u>Features</u></b>
                    </h4>
                    <ul class="text-md text-center">

                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Users Roles & Permissions</p>
                        </li>

                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Jobs & Instructions</p>
                        </li>

                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Invoices & Quotes</p>
                        </li>
                        
                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Attendance</p>
                        </li>

                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Leave Applications</p>
                        </li>




                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Comment System</p>
                        </li>

                        <li class="flex space-x-6 ">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Banking Manager</p>
                        </li>

                    </ul>

                    <x-filament::hr />

                    <div class="text-center">
                        <button type="submit"
                            class="inline-flex items-center justify-center py-1 gap-1
                                font-medium rounded-lg border 
                                transition-colors focus:outline-none 
                                focus:ring-offset-2 focus:ring-2 
                                focus:ring-inset filament-button 
                                dark:focus:ring-offset-0 min-h-[2.25rem]
                                px-4 text-sm text-white 
                                shadow focus:ring-white
                                border-transparent
                               {{ $plan === 'Premium' ? 'bg-danger-600 hover:bg-danger-500 focus:bg-danger-700 focus:ring-offset-danger-700' : 'bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700' }}"
                            id="{{ $plan === 'Premium' ? 'renew' : 'subscribe-to-premium-button' }}">

                            <span class="flex items-center gap-1">
                                <span class="">
                                    {{ $plan === 'Premium' ? 'Renew Plan' : 'Choose Plan' }}
                                </span>
                            </span>

                        </button>
                        @if ($plan === 'Premium')
                            <p>Expires In {{ now()->diffInDays(cache()->get('subscription')->expired_at) }} Days</p>
                        @endif
                    </div>

                </x-filament::card>

            </div>
        </div>

        <x-filament::hr />
        @if (cache()->get('hasExpired') == false)
            <form wire:submit.prevent="submit" class="space-y-6">
                {{ $this->form }}

                <div class="flex flex-wrap items-center gap-4 justify-start">
                    <x-filament::button type="submit">
                        Update Settings
                    </x-filament::button>
                </div>

            </form>

            <form action="{{ Route('invoice-settings.downloadPdf') }}" class="space-y-6">

                <div class="flex flex-wrap items-center gap-4 justify-start">
                    <x-filament::button type="submit" color="secondary">
                        Download Preview
                    </x-filament::button>
                </div>
            </form>
        @endif
        @php
            $currenPlan = cache()->get('current_plan') ?? null;
        @endphp

        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
            integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @if ($currenPlan != null)
            <script>
                function log_activity($log) {
                    console.log($log);
                }

                $("#yoco-spinner-custom").css('display', 'none');

                $('#renew')
                    .css('display', 'unset')
                    .on('click', function() {
                        $('#renew')
                            .css('display', 'none');

                        $('#subscribe-to-premium-button')
                            .css('display', 'none');

                        $('#subscribe-to-basic-button')
                            .css('display', 'none');

                        var currentPlan = "{{ $currenPlan }}";

                        var price = 0;

                        if (currentPlan === "Basic") {
                            price = 1000;
                        } else {
                            price = 2000;
                        }

                        // the currency (must be ZAR)
                        var currency = 'ZAR';
                        // the name at the top of the popup (either shop or product)
                        var product_title = "Renew Your Current Plan";
                        // the description of the purchase (product or product description)
                        var product_description = "Renewal Of Plan";
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
                                        "&metadata[method]=" + "renew",
                                    beforeSend: function() {
                                        $("#yoco-spinner-custom").css('display', 'unset');
                                    },
                                    'success': function(data) {
                                        $("#yoco-spinner-custom").css('display', 'none');
                                        swal({
                                            title: "Purchase successful",
                                            text: "Your Plan Has Been Renewd",
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
                                $('#renew')
                                    .css('display', 'unset');

                                $('#subscribe-to-premium-button')
                                    .css('display', 'unset');

                                $('#subscribe-to-basic-button')
                                    .css('display', 'unset');

                                $("#yoco-spinner-custom").css('display', 'none');
                            }
                        });
                    });
            </script>
        @endif


        <script>
            function log_activity($log) {
                console.log($log);
            }

            $("#yoco-spinner-custom").css('display', 'none');

            $('#subscribe-to-basic-button')
                .css('display', 'unset')
                .on('click', function() {
                    $('#subscribe-to-basic-button')
                        .css('display', 'none');

                    $('#subscribe-to-premium-button')
                        .css('display', 'none');

                    $('#renew')
                        .css('display', 'none');


                    var price = 1000;
                    // the currency (must be ZAR)
                    var currency = 'ZAR';
                    // the name at the top of the popup (either shop or product)
                    var product_title = "Basic Plan";
                    // the description of the purchase (product or product description)
                    var product_description =
                        "Subribing To The Basic Plan";

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
                                    "&metadata[method]=" + "subscribe-to-basic-plan",
                                beforeSend: function() {
                                    $("#yoco-spinner-custom").css('display', 'unset');
                                },
                                'success': function(data) {
                                    $("#yoco-spinner-custom").css('display', 'none');
                                    swal({
                                        title: "Purchase successful",
                                        text: "You Are Now On The Basic Plan",
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
                            $('#subscribe-to-basic-button')
                                .css('display', 'unset');

                            $('#subscribe-to-premium-button')
                                .css('display', 'unset');

                            $('#renew')
                                .css('display', 'unset');
                            $("#yoco-spinner-custom").css('display', 'none');

                        }
                    });
                });

            $("#yoco-spinner-custom").css('display', 'none');

            $('#subscribe-to-premium-button')
                .css('display', 'unset')
                .on('click', function() {
                    $('#subscribe-to-basic-button')
                        .css('display', 'none');

                    $('#renew')
                        .css('display', 'none');

                    $('#subscribe-to-premium-button')
                        .css('display', 'none');



                    var price = 2000;
                    // the currency (must be ZAR)
                    var currency = 'ZAR';
                    // the name at the top of the popup (either shop or product)
                    var product_title = "Premium Plan";
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
                                    "&metadata[method]=" + "subscribe-to-premium-plan",
                                beforeSend: function() {
                                    $("#yoco-spinner-custom").css('display', 'unset');
                                },
                                'success': function(data) {
                                    $("#yoco-spinner-custom").css('display', 'none');
                                    swal({
                                        title: "Purchase successful",
                                        text: "You Are Now On The Premium Plan",
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
                            $('#subscribe-to-premium-button')
                                .css('display', 'unset');

                            $('#subscribe-to-basic-button')
                                .css('display', 'unset');

                            $('#renew')
                                .css('display', 'unset');
                            $("#yoco-spinner-custom").css('display', 'none');

                        }
                    });
                });
        </script>



    </x-filament::page>
</div>
