<?php

namespace App\Filament\Resources\A3Resource\Pages;

use App\Filament\Resources\A3Resource;
use Filament\Resources\Pages\Page;

class Settings extends Page
{
    protected static string $resource = A3Resource::class;

    protected static string $view = 'filament.resources.a3-resource.pages.settings';
}
