<?php

namespace App\Filament\Employee\Resources\AttendanceResource\RelationManagers;

use App\Models\DailyTask;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'dailyTasks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('task')
                    ->required()
                    ->maxLength(255),
                Toggle::make('status')
                    ->onColor('success')
                    ->offColor('danger')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('task')
            ->columns([
                Tables\Columns\TextColumn::make('task')->wrap(),
                Tables\Columns\ToggleColumn::make('status')
                    ->label('Status')
                    ->afterStateUpdated(function (DailyTask $record, $state, Set $set) {
                        $attendance = $record->attendance;
                        if ($attendance) {
                            $totalTasks = $attendance->dailyTasks()->count();
                            $completedTasks = $attendance->dailyTasks()->where('status', true)->count();
                            $performanceRate = ($completedTasks / $totalTasks) * 100;
                            $attendance->pefomance_rate = $performanceRate;
                            $attendance->save();
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
