<?php

namespace App\Filament\Employee\Widgets;

use App\Models\Attendance;
use App\Models\HRPolicySetting;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AttendanceChart extends ChartWidget
{

    protected static ?string $heading = 'Attendance Chart';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '210px';


    protected function getData(): array
    {
        //Menghitung jumlah member yang aktif
        $presencesCount = Attendance::where('user_id', Auth::user()->id)->where('start_time', '<=', HRPolicySetting::first()->start_hour)->count() / Attendance::where('user_id', Auth::user()->id)->count() * 100;
        //Menghitung jumlah member yang tidak aktif
        $latesCount = Attendance::where('user_id', Auth::user()->id)->where('start_time', '>', HRPolicySetting::first()->start_hour)->count() / Attendance::where('user_id', Auth::user()->id)->count() * 100;

        return [
            'labels' => ['Arrived Early in %', 'Arrived Late in %'],
            'datasets' => [
                [
                    'data' => [$presencesCount, $latesCount],
                    'backgroundColor' => ['#36A2EB', '#FF6384'], // Warna untuk masing-masing bagian
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
