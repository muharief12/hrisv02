<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HRPolicySettingResource\Pages;
use App\Filament\Resources\HRPolicySettingResource\RelationManagers;
use App\Models\HRPolicySetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HRPolicySettingResource extends Resource
{
    protected static ?string $model = HRPolicySetting::class;
    protected static ?string $navigationLabel = 'HR Policy & Settings';
    protected static ?string $breadcrumb = 'HR Policy & Settings';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tax')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('start_hour')
                    ->required(),
                Forms\Components\TextInput::make('end_hour')
                    ->required(),
                Forms\Components\TextInput::make('late_punishment')
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('logo')
                    ->directory('image')
                    ->default(null),
                Forms\Components\FileUpload::make('signature')
                    ->directory('image')
                    ->default(null),
                Forms\Components\TextInput::make('account_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('account_number')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tax')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_hour'),
                Tables\Columns\TextColumn::make('end_hour'),
                Tables\Columns\TextColumn::make('late_punishment')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('logo'),
                Tables\Columns\ImageColumn::make('signature'),
                Tables\Columns\TextColumn::make('account_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_number')
                    ->searchable(),
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
            'index' => Pages\ListHRPolicySettings::route('/'),
            'create' => Pages\CreateHRPolicySetting::route('/create'),
            'edit' => Pages\EditHRPolicySetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return HRPolicySetting::count() < 1;
    }
}
