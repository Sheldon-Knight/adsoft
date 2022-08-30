<x-filament::page>
    <header>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://js.yoco.com/sdk/v1/yoco-sdk-web.js"></script>
        <script>
            var public_key = '{{ config('yoco.public_key') }}';

            var yoco = new window.YocoSDK({
                publicKey: public_key
            });
        </script>
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


    <script>
        var subscribeToBasicButton = document.querySelector('#subscribe-to-basic-button');
        if(subscribeToBasicButton != null){
            subscribeToBasicButton.addEventListener('click', function() {
            yoco.showPopup({
                amountInCents: 5000,
                currency: 'ZAR',
                name: 'Basic Plan',
                description: 'Subsribe To Basic Plan',
                callback: function(result) {
                    $.ajax({
                        'url': '/yoco/charge',
                        'method': 'POST',
                        'dataType': 'json',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        'data': 'token=' + result.id +
                            '&amountInCents=' + 5000 +
                            "&currency=" + "ZAR" +
                            "&metadata[plan_id]=" + 1 +
                            "&metadata[user_id]=" + {{ auth()->id() }},
                        'success': function(data) {
                            alert("success");
                        },
                        'error': function(result) {
                            alert("something went wrong");
                        },
                    });
                }
            })
        });
        }
    </script>
    <script>
        var subscribeToPremiumButton = document.querySelector('#subscribe-to-premium-button'); 
            if(subscribeToPremiumButton != null){     
        subscribeToPremiumButton.addEventListener('click', function() {
            yoco.showPopup({
                amountInCents: 1000,
                currency: 'ZAR',
                name: 'Premium Plan',
                description: 'Subscribe To Premium Plan',
                callback: function(result) {
                    $.ajax({
                        'url': '/yoco/charge',
                        'method': 'POST',
                        'dataType': 'json',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        'data': 'token=' + result.id +
                            '&amountInCents=' + 1000 +
                            "&currency=" + "ZAR" +
                            "&metadata[plan_id]=" + 2 +
                            "&metadata[user_id]=" + {{ auth()->id() }},
                        'success': function(data) {
                            alert("success");
                        },
                        'error': function(result) {
                            alert("something went wrong");
                        },
                    });
                }
            })
        });
    }
    </script>

    <script>
        var renewButton = document.querySelector('#renew');
           if(renewButton != null){     
        renewButton.addEventListener('click', function() {
            yoco.showPopup({
                amountInCents: 6000,
                currency: 'ZAR',
                name: 'Renew Plan',
                description: 'Renew Plan',
                callback: function(result) {
                    $.ajax({
                        'url': '/yoco/charge',
                        'method': 'POST',
                        'dataType': 'json',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        'data': 'token=' + result.id +
                            '&amountInCents=' + 6000 +
                            "&currency=" + "ZAR" +
                            "&metadata[plan_id]=" + 2 +
                            "&metadata[user_id]=" + {{ auth()->id() }},
                        'success': function(data) {
                            alert("success");
                        },
                        'error': function(result) {
                            alert("something went wrong");
                        },
                    });
                }
            })
        });
    }
    </script>

</x-filament::page>
