<?php

namespace App\Filament\Pages;

use App\Models\OmsSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use LucasDotVin\Soulbscription\Models\Plan;

class OmsSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.oms-settings';

    protected static ?string $title = 'Oms Settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    public OmsSetting $omsSetting;

    public $data;

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('change application settings');
    }

    public function mount()
    {
        abort_unless(auth()->user()->can('change application settings'), 403);

        $this->omsSetting = OmsSetting::find(1);

        $this->form->fill([
            'oms_name' => $this->omsSetting->oms_name,
            'oms_company_name' => $this->omsSetting->oms_company_name,
            'oms_email' => $this->omsSetting->oms_email,
            'oms_company_tel' => $this->omsSetting->oms_company_tel,
            'oms_company_address' => $this->omsSetting->oms_company_address,
            'oms_company_vat' => $this->omsSetting->oms_company_vat,
            'oms_company_registration' => $this->omsSetting->oms_company_registration,
            'oms_logo' => $this->omsSetting->oms_logo,
            'date_format' => $this->omsSetting->date_format,
            'invoice_series' => $this->omsSetting->invoice_series,
            'quote_series' => $this->omsSetting->quote_series,
            'invoice_notes' => $this->omsSetting->invoice_notes,
            'quote_notes' => $this->omsSetting->quote_notes,
        ]);
    }

    public function getCancelButtonUrlProperty()
    {
        return static::getUrl();
    }

    protected function getFormModel(): OmsSetting
    {
        return $this->omsSetting;
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'oms-settings',
        ];
    }

    public function submit()
    {
        cache()->forget('oms_name');

        $this->omsSetting->update($this->form->getState());

        cache()->forever('oms_name', OmsSetting::first()->oms_name);

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();

        return redirect()->to('admin/oms-settings');
    }

    protected function getFormSchema(): array
    {
        $dateFormats = [
            'Y/m/d' => today()->format('Y/m/d'),
            'Y-m-d' => today()->format('Y-m-d'),
            'Y/d/m' => today()->format('Y/d/m'),
            'Y-d-m' => today()->format('Y-d-m'),
            'd/m/Y' => today()->format('d/m/Y'),
            'm/d/Y' => today()->format('m/d/Y'),
            'd-m-y' => today()->format('d-m-y'),
            'm-d-y' => today()->format('m-d-y'),
        ];

        return [
            Section::make('Set Up')
                ->columns(2)
                ->schema([
                    TextInput::make('oms_name')
                        ->label('Office Management System Name')
                        ->required(),

                    TextInput::make('oms_company_name')
                        ->label('Company Name')
                        ->required(),

                    TextInput::make('oms_email')
                        ->label('Company Email')
                        ->required(),

                    TextInput::make('oms_company_vat')
                        ->label('Vat')
                        ->required(),

                    TextInput::make('oms_company_registration')
                        ->label('Registration Number')
                        ->required(),

                    Select::make('date_format')
                        ->label('Date Format')
                        ->options($dateFormats)
                        ->required(),

                    TextInput::make('invoice_series')
                        ->label('Invoice Series')
                        ->required(),

                    TextInput::make('quote_series')
                        ->label('Quote Series')
                        ->required(),

                    FileUpload::make('oms_logo')
                        ->label('Logo')->image()->required()
                        ->enableOpen()
                        ->enableDownload()
                        ->panelAspectRatio('16:4')
                        ->preserveFilenames(),

                    Textarea::make('oms_company_address')
                        ->label('Address')
                        ->required()
                        ->rows(3)
                        ->cols(3),

                    Textarea::make('invoice_notes')
                        ->label('Invoice Notes')
                        ->required()
                        ->rows(3)
                        ->cols(3),

                    Textarea::make('quote_notes')
                        ->label(' Quote Notes')
                        ->required()
                        ->rows(3)
                        ->cols(3),
                ]),
        ];
    }

    public function subscribeTo($planId)
    {
        $plan = Plan::find($planId);

        $subscription = OmsSetting::first();

        if ($subscription->hasExpired()) {
            DB::table('subscriptions')->where('subscriber_id', $subscription->id)->delete();
        }

        $subscription->subscribeTo($plan);

        cache()->forget('subscription');

        cache()->forget('current_plan');

        cache()->forget('hasExpired');

        cache()->forever('subscription', OmsSetting::first()->subscription);

        cache()->forever('current_plan', OmsSetting::first()->subscription->plan->name);

        Notification::make()
            ->title('Your Subscription Has Been Added')
            ->success()
            ->send();

        return redirect()->to('/admin/oms-settings');
    }

    public function renew()
    {
        $subscription = OmsSetting::first();

        $subscription->subscription->renew();

        cache()->forget('subscription');

        cache()->forget('current_plan');

        cache()->forget('hasExpired');

        cache()->forever('subscription', OmsSetting::first()->subscription);

        cache()->forever('current_plan', OmsSetting::first()->subscription->plan->name);

        Notification::make()
            ->title('Your Subscription Has Been Renewed')
            ->success()
            ->send();

        return redirect()->to('/admin/oms-settings');
    }
}
