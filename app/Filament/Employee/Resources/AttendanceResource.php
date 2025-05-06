<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\AttendanceResource\Pages;
use App\Filament\Employee\Resources\AttendanceResource\RelationManagers;
use App\Filament\Employee\Resources\AttendanceResource\RelationManagers\DailyTasksRelationManager;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name', modifyQueryUsing: fn($query) => $query->where('id', Auth::id()))
                    ->default(Auth::user()->id)
                    ->required()
                    ->disabled(),
                Forms\Components\DatePicker::make('date')
                    ->default(now())
                    ->required()
                    ->disabled(),
                Forms\Components\TImePicker::make('start_time')
                    ->native(false)
                    ->hoursStep(2)
                    ->minutesStep(15)
                    ->secondsStep(10)
                    ->required()
                    ->disabled(),
                Forms\Components\TImePicker::make('end_time')
                    ->native(false)
                    ->hoursStep(2)
                    ->minutesStep(15)
                    ->default('00:00:00')
                    ->secondsStep(10)
                    ->disabled(),
                Forms\Components\TextInput::make('pefomance_rate')
                    ->disabled()
                    ->live()
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->where('user_id', Auth::id()) // filter sesuai user login
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->default('-'),
                Tables\Columns\TextColumn::make('end_time')
                    ->default('-'),
                Tables\Columns\TextColumn::make('pefomance_rate')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // ->emptyStateHeading('Belum ada data absensi')
            // ->emptyStateDescription('Silakan isi data terlebih dahulu.')
            // ->emptyStateIcon('heroicon-o-calendar')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('View'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function view(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Data attendance
                TextEntry::make('user.name'),
                TextEntry::make('date'),
                TextEntry::make('start_time'),
                TextEntry::make('end_time'),

                // Tambahkan daftar tugas
                RepeatableEntry::make('dailyTasks')
                    ->label('Tugas Harian')
                    ->schema([
                        TextEntry::make('task')->label('Tugas'),
                        IconEntry::make('status')
                            ->boolean()
                            ->label('Selesai'),
                    ])
                    ->columnSpanFull(),
            ]);
    }


    protected function updatePerformanceRate(Attendance $attendance)
    {
        $totalTasks = $attendance->dailyTasks()->count();
        $completedTasks = $attendance->dailyTasks()->where('status', true)->count();
        $performanceRate = ($completedTasks / $totalTasks) * 100;
        $attendance->pefomance_rate = $performanceRate;
        $attendance->save();
    }


    public static function getRelations(): array
    {
        return [
            DailyTasksRelationManager::class,

            // RelationManager::make(['dailyTasks'])
            //     ->afterSave(function ($record) {
            //         $this->updatePerformanceRate($record->attendance);
            //     }),

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }


    // public static function query(Builder $query): Builder
    // {
    //     return $query->where('user_id', Auth::user()->id);
    // }
}
