<?php

namespace App\Filament\Resources\HRPolicySettingResource\Pages;

use App\Filament\Resources\HRPolicySettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHRPolicySettings extends ListRecords
{
    protected static string $resource = HRPolicySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
