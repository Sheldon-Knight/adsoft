<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use App\Models\Invoice;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        $invoice = Invoice::find($data['invoice_id']);

        $user = User::find($data['user_id']);

        $data['department_id'] = $user->department_id ?? null;

        $data['client_id'] = $invoice->client_id;        

        return $data;
    }

}
