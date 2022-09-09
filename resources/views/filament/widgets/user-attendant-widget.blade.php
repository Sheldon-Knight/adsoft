<x-filament::widget class="filament-account-widget">
    <x-filament::card>
        @php
            $user = \Filament\Facades\Filament::auth()->user();
        @endphp

        <div class="h-12 flex items-center space-x-4 rtl:space-x-reverse">
            <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center"
                style="background-image: url('{{ \Filament\Facades\Filament::getUserAvatarUrl($user) }}')"></div>

            <div>
                <h2 class="text-lg sm:text-xl font-bold tracking-tight">
                    {{ __('filament::widgets/account-widget.welcome', ['user' => \Filament\Facades\Filament::getUserName($user)]) }}
                    @if ($attendance)

                        @if ($attendance->time_in == null)
                            Remember To Check In
                        @endif

                        @if ($attendance->time_out == null)
                            Remember To Check Out
                        @endif

                        @if ($attendance->time_in != null and $attendance->time_out != null)
                        
                        @endif

                    @endif


                </h2>

                <form action="{{ route('filament.auth.logout') }}" method="post" class="text-sm">
                    @csrf

                    <button type="submit" @class([
                        'text-gray-600 hover:text-primary-500 focus:outline-none focus:underline',
                        'dark:text-gray-300 dark:hover:text-primary-500' => config(
                            'filament.dark_mode'
                        ),
                    ])>
                        {{ __('filament::widgets/account-widget.buttons.logout.label') }}
                    </button>
                </form>
            </div>
        </div>

        <x-filament::card>

            @if ($attendance)
                @if ($attendance->time_out == null)
                    <form wire:submit.prevent="checkOut">
                        {{ $this->checkoutForm }}

                        <br>

                        <x-filament::button type="submit" wire:poll.10000ms.visible="now">
                            Check Out: {{ $now }}
                        </x-filament::button>
                    </form>
                @endif
                @if ($attendance->time_in != null and $attendance->time_out != null)
                    <form>
                        {{ $this->checkinoutForm }}

                        <br>
                    </form>
                @endif
            @else
                <form wire:submit.prevent="checkIn">
                    {{ $this->checkinForm }}
                    <br>

                    <x-filament::button type="submit" wire:poll.10000ms.visible="now">
                        Check In: {{ $now }}
                    </x-filament::button>
                </form>
            @endif
        </x-filament::card>
    </x-filament::card>
</x-filament::widget>
