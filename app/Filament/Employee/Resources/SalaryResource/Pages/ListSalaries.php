<?php

namespace App\Filament\Employee\Resources\SalaryResource\Pages;

use App\Filament\Employee\Resources\SalaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalaries extends ListRecords
{
    protected static string $resource = SalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
