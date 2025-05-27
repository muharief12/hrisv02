<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\HRPolicySetting;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Workers', User::count()),
            Stat::make('Total Attendance (Today)', Attendance::where('created_at', \Carbon\Carbon::now())->count()),
            Stat::make('Avg Peformance Rate', number_format(Attendance::average('pefomance_rate'), 2, ',', '.') . ' %'),
        ];
    }
}
