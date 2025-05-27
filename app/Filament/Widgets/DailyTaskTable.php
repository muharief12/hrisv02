<?php

namespace App\Filament\Widgets;

use App\Models\DailyTask;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Database\Query\Builder;

class DailyTaskTable extends BaseWidget
{
    protected static ?string $heading = 'Daily Task (On Progress)';
    protected static ?int $sort = 3;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                DailyTask::where('status', false)->orderBy('created_at', 'ASC')
            )
            ->columns([
                Tables\Columns\TextColumn::make('attendance.user.name'),
                Tables\Columns\TextColumn::make('task')->wrap(true),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ]);
    }
}
