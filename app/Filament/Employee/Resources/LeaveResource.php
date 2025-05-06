<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\LeaveResource\Pages;
use App\Filament\Employee\Resources\LeaveResource\RelationManagers;
use App\Models\Leave;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->default(Auth::user()->id)
                    ->options(function (User $user) {
                        return $user->where('id', Auth::id())->pluck('name', 'id')->toArray();
                    })
                    ->required(),
                Forms\Components\Select::make('leave_type_id')
                    ->relationship('leaveType', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->required()
                    ->default(now()->addDays(1)),
                Forms\Components\TextInput::make('duration')
                    ->required()
                    ->minValue(1)
                    ->default(1)
                    ->numeric(),
                Forms\Components\FileUpload::make('attachment')
                    ->acceptedFileTypes(['application/pdf'])
                    ->mimeTypeMap([
                        'pdf' => 'application/pdf'
                    ])
                    ->maxSize(2048)
                    ->default(null)->columnSpanFull(),
                Hidden::make('status')
                    ->default('submission')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('user_id', Auth::id()))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('leaveType.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attachment')
                    ->searchable()
                    ->default('submission'),
                Tables\Columns\TextColumn::make('status'),
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
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}
