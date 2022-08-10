<?php

namespace App\Filament\Pages;

use App\Models\InvoiceBasicInfo;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Livewire\TemporaryUploadedFile;




class InvoiceSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.invoice-settings';

    protected static ?string $title = 'Invoice & Quote Settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 5;

    public InvoiceBasicInfo $invoiceBasicInfo;

    public $oms_company_name;
    public $oms_company_address;
    public $oms_company_tel;
    public $oms_company_email;
    public $oms_company_vat;
    public $oms_company_registration;
    public $invoice_notes;
    public $invoice_logo;
    public $logo;
    public $date_format;
    public $series;
    public $data;
    public $is_quote = false;

    public function mount()
    {

        $this->invoiceBasicInfo = InvoiceBasicInfo::find(1);

        $this->form->fill([
            'oms_company_name' => $this->invoiceBasicInfo->oms_company_name,
            'oms_company_address' => $this->invoiceBasicInfo->oms_company_address,
            'oms_company_tel' => $this->invoiceBasicInfo->oms_company_tel,
            'oms_company_email' => $this->invoiceBasicInfo->oms_company_email,
            'oms_company_vat' => $this->invoiceBasicInfo->oms_company_vat,
            'oms_company_registration' => $this->invoiceBasicInfo->oms_company_registration,
            'invoice_notes' => $this->invoiceBasicInfo->invoice_notes,
            'date_format' => $this->invoiceBasicInfo->date_format,
            'series' => $this->invoiceBasicInfo->series,
            'invoice_logo' => $this->invoiceBasicInfo->invoice_logo,
        ]);
    }

    public function getCancelButtonUrlProperty()
    {
        return static::getUrl();
    }

    protected function getFormModel(): InvoiceBasicInfo
    {
        return $this->invoiceBasicInfo;
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'invoice-settings',
        ];
    }

    public function submit(): void
    {
        $this->invoiceBasicInfo->update($this->form->getState());

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Templates')
                ->columns(2)
                ->schema([
                    TextInput::make('oms_company_name')
                        ->label('Company Name')
                        ->required(),

                    TextInput::make('oms_company_vat')
                        ->label('Vat')
                        ->required(),

                    TextInput::make('oms_company_registration')
                        ->label('Registration Number')
                        ->required(),
                    TextInput::make('date_format')
                        ->label('Date Format')
                        ->required(),
                    TextInput::make('series')
                        ->label('Series')
                        ->required(),

                    FileUpload::make('invoice_logo')
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
                        ->label('Notes')
                        ->required()
                        ->rows(3)
                        ->cols(3),
                ]),
        ];
    }
}
