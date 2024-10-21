<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneralSettingsResource\Pages;
use App\Filament\Resources\GeneralSettingsResource\RelationManagers;
use App\Models\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GeneralSettingsResource extends Resource
{
    protected static ?string $model = GeneralSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('main_logo')
                    ->image(),
                Forms\Components\FileUpload::make('footer_logo')
                    ->image(),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->numeric(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(193),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(193),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_logo'),
                Tables\Columns\ImageColumn::make('footer_logo'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
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
            'index' => Pages\ListGeneralSettings::route('/'),
            'create' => Pages\CreateGeneralSettings::route('/create'),
            'edit' => Pages\EditGeneralSettings::route('/{record}/edit'),
        ];
    }
}
