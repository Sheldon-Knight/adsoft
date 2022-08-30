<x-filament::page>
    @php
        $subscription = cache()->get('subscription');
        $plan = cache()->get('current_plan');
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
                    <x-filament::button type="submit" color="{{ $plan === 'basic' ? 'danger' : 'primary' }}">

                        {{ $plan === 'basic' ? 'Renew Plan' : 'Choose Plan' }}
                    </x-filament::button>
                    @if ($plan === 'basic')
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
                        <p><del>Comment System</del></p>
                    </li>

                    <li class="flex space-x-6 ">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="#21B531" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p><del>Banking Manager</del></p>
                    </li>

                </ul>

                <x-filament::hr />

                <div class="text-center">
                    <x-filament::button type="submit" color="{{ $plan === 'premium' ? 'danger' : 'primary' }}">

                        {{ $plan === 'premium' ? 'Renew Plan' : 'Choose Plan' }}
                    </x-filament::button>
                    @if ($plan === 'premium')
                        <p>Expires In {{ now()->diffInDays(cache()->get('subscription')->expired_at) }} Days</p>
                    @endif
                </div>

            </x-filament::card>

            {{-- <x-filament::card class="w-full">

                <h2 class="text-xl text-center font-semibold tracking-tight filament-card-heading">
                    Plan 3
                </h2>
                <P class="text-sm text-center">
                    Plan Description
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
                    <x-filament::button type="submit">
                        Choose Plan
                    </x-filament::button>
                </div>

            </x-filament::card> --}}

        </div>
    </div>

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

</x-filament::page>
