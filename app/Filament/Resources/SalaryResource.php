<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalaryResource\Pages;
use App\Filament\Resources\SalaryResource\RelationManagers;
use App\Models\Attendance;
use App\Models\HRPolicySetting;
use App\Models\Salary;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        // Reset nilai saat user berubah
                        $user = User::find($state);
                        $policy = HRPolicySetting::first();

                        if ($user) {
                            $set('salary', $user->salary);

                            // Hitung tax
                            if ($policy) {
                                $tax = $user->salary * $policy->tax / 100;
                                $set('tax', $tax);
                            }

                            // Hitung deduction (jika date sudah diisi)
                            $date = $get('date');
                            if ($date && $policy) {
                                $month = Carbon::parse($date)->month;
                                $year = Carbon::parse($date)->year;

                                $startHourLimit = $policy->start_hour; // format: HH:mm:ss

                                $lateCount = Attendance::where('user_id', $state)
                                    ->whereMonth('date', $month)
                                    ->whereYear('date', $year)
                                    ->whereTime('start_time', '>', $startHourLimit)
                                    ->count();

                                $deductionAmount = $lateCount * $policy->deduction_per_late ?? 0;
                                $set('deduction', $deductionAmount);

                                // Hitung final salary
                                $finalSalary = $user->salary - ($tax ?? 0) - $deductionAmount;
                                $set('final_salary', $finalSalary);
                            }
                        } else {
                            $set('salary', null);
                            $set('tax', null);
                            $set('deduction', null);
                            $set('final_salary', null);
                        }
                    }),
                Forms\Components\DatePicker::make('date')
                    ->displayFormat('F Y')
                    // ->format('Y-m')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $userId = $get('user_id');
                        $user = User::find($userId);
                        $policy = HRPolicySetting::first();

                        if ($user && $policy) {
                            $salary = $user->salary;
                            $tax = $salary * $policy->tax / 100;

                            $month = Carbon::parse($state)->month;
                            $year = Carbon::parse($state)->year;
                            $startHourLimit = $policy->start_hour;

                            $lateCount = Attendance::where('user_id', $userId)
                                ->whereMonth('date', $month)
                                ->whereYear('date', $year)
                                ->whereTime('start_time', '>', $startHourLimit)
                                ->count();

                            $deductionAmount = $lateCount * $policy->deduction_per_late ?? 0;
                            $finalSalary = $salary - $tax - $deductionAmount;

                            $set('tax', $tax);
                            $set('deduction', $deductionAmount);
                            $set('final_salary', $finalSalary);
                        }
                    }),
                Forms\Components\TextInput::make('salary')
                    ->readOnly()
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tax')
                    ->readOnly()
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('deduction')
                    ->readOnly()
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('final_salary')
                    ->readOnly()
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->options([
                        'paid' => 'Paid',
                        'confirmed' => 'Confirmed'
                    ])
                    ->default('paid')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('salary')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('deduction')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_salary')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'warning',
                        'confirmed' => 'success',
                    }),
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalaries::route('/'),
            'create' => Pages\CreateSalary::route('/create'),
            'edit' => Pages\EditSalary::route('/{record}/edit'),
        ];
    }
}
