<?php

namespace App\Filament\Employee\Resources\LeaveResource\Pages;

use App\Filament\Employee\Resources\LeaveResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;
}
