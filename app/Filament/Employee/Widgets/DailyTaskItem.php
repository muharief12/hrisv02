<?php

namespace App\Filament\Employee\Widgets;

use App\Models\DailyTask;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DailyTaskItem extends BaseWidget
{
    protected static ?string $heading = 'Daily Task Chart';
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DailyTask::query()->whereHas('attendance', function (Builder $query) {
                    $query->where('user_id', Auth::user()->id);
                })
                    ->where('status', false)
                    ->orderBy('created_at', 'ASC')
            )
            ->columns([
                Tables\Columns\TextColumn::make('task')->wrap(true),
                Tables\Columns\ToggleColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ]);
    }
}
