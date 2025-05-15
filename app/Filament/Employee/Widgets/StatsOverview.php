<?php

namespace App\Filament\Employee\Widgets;

use App\Models\Attendance;
use App\Models\HRPolicySetting;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Attendances', Attendance::where('user_id', Auth::user()->id)->count()),
            Stat::make('Total Late Arrivals', Attendance::where('user_id', Auth::user()->id)->where('start_time', '>', HRPolicySetting::first()->start_hour)->count()),
            Stat::make('Peformance Rate', number_format(Attendance::where('user_id', Auth::user()->id)->average('pefomance_rate'), 2, ',', '.') . ' %'),
        ];
    }
}
