<?php

namespace App\Filament\Employee\Resources\SalaryResource\Pages;

use App\Filament\Employee\Resources\SalaryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSalary extends CreateRecord
{
    protected static string $resource = SalaryResource::class;
}
