@if (filled($brand = config('filament.brand')))
    @php        
        $cache = cache()->get('oms_name');
        
        if (!$cache) {
            cache()->forever('oms_name', App\Models\OmsSetting::first()->oms_name);
        }      
        
        $brand = $cache ?? config('filament.brand');
    @endphp
    <div @class([
        'text-md font-bold tracking-tight filament-brand',
        'dark:text-white' => config('filament.dark_mode'),
    ])>
        {{ $brand }} 
           <x-filament::hr />                
    </div>
    <small>
       Current Plan : {{ cache()->get('current_plan') }} - Expires In {{ now()->diffInDays(cache()->get('subscription')->expired_at) }} Days
   </small>

@endif
