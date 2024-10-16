<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Filament\Resources\PropertyResource\RelationManagers;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Information 1')
                    ->schema([
                        Forms\Components\Select::make('user')
                            ->relationship(name: 'user', titleAttribute: 'firstname')
                            ->required()
                            ->preload()
                            ->searchable(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(193),
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(193),
                        Forms\Components\TextInput::make('state')
                            ->required()
                            ->maxLength(193),
                        Forms\Components\TextInput::make('city')
                            ->required()
                            ->maxLength(193),

                    ])->columns(2),
                Forms\Components\Section::make('Information 2')
                    ->schema([

                        Forms\Components\FileUpload::make('image')
                            ->image(),
                        Forms\Components\TextInput::make('video')
                            ->maxLength(193),
                        Forms\Components\TextInput::make('additional_charge')
                            ->numeric(),
                        Forms\Components\Textarea::make('landmarks')
                            ->nullable(),
                        Forms\Components\Textarea::make('amenities')
                            ->nullable(),
                        Forms\Components\TextInput::make('Property size')
                            ->maxLength(193),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('video')
                    ->searchable(),
                Tables\Columns\TextColumn::make('additional_charge')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('size')
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
