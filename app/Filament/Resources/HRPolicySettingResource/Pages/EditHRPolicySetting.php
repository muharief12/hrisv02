<?php

namespace App\Filament\Resources\HRPolicySettingResource\Pages;

use App\Filament\Resources\HRPolicySettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHRPolicySetting extends EditRecord
{
    protected static string $resource = HRPolicySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
