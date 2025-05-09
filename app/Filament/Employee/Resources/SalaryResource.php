<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\SalaryResource\Pages;
use App\Filament\Employee\Resources\SalaryResource\RelationManagers;
use App\Models\Salary;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

use Filament\Resources\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\BadgeEntry;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Infolist as InfolistBase;
use Filament\Notifications\Notification;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action as ActionInfolist;
use Filament\Tables\Actions\Action as ActionsAction;

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

                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('salary')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tax')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('deduction')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('final_salary')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => Salary::where('user_id', Auth::user()->id))
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
                Tables\Actions\ViewAction::make(),
                ActionsAction::make('confirmPayroll')
                    ->label('Konfirmasi Pembayaran')
                    ->color('success')
                    ->icon('heroicon-m-check-badge')
                    ->visible(fn($record) => $record->status !== 'confirmed')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pembayaran Gaji')
                    ->modalSubheading(fn($record) => 'Apakah Anda yakin ingin mengonfirmasi pembayaran gaji bulan ' . \Carbon\Carbon::parse($record->date)->translatedFormat('F Y') . ' ?')

                    ->action(function ($record) {
                        $record->update(['status' => 'confirmed']);

                        Notification::make()
                            ->title('Status berhasil diperbarui')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(InfolistBase $infolist): InfolistBase
    {
        return $infolist
            ->schema([
                Group::make([
                    TextEntry::make('user.name')->label('Nama Karyawan'),
                    TextEntry::make('date')->label('Periode')->date('F Y'),
                    TextEntry::make('salary')->label('Gaji Pokok')->money('IDR', true),
                    TextEntry::make('tax')->label('Pajak')->money('IDR', true),
                    TextEntry::make('deduction')->label('Potongan')->money('IDR', true),
                    TextEntry::make('final_salary')->label('Gaji Bersih')->money('IDR', true),
                ])->columns(2),

                TextEntry::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'warning',
                        'confirmed' => 'success',
                    })
                    ->label('Status Pembayaran'),
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
