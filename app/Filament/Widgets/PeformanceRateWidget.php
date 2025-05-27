<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class PeformanceRateWidget extends ChartWidget
{
    protected static ?string $heading = 'Workers Performance Rate';
    protected static ?int $sort = 2;


    protected function getData(): array
    {
        $users = User::with('attendances')
            ->whereHas('attendances', function ($query) {
                $query->whereNotNull('pefomance_rate');
            })
            ->get()
            ->map(function ($user) {
                $avgRate = $user->attendances->avg('pefomance_rate');
                return [
                    'name' => $user->name,
                    'average_performance_rate' => $avgRate,
                ];
            })
            ->sortByDesc('average_performance_rate')
            ->values();

        return [
            'datasets' => [
                [
                    'label' => 'Average Performance Rate',
                    'data' => $users->pluck('average_performance_rate')->toArray(),
                    'backgroundColor' => '#6366f1', // Tailwind indigo-500
                ],
            ],
            'labels' => $users->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
