<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrontendResource\Pages;
use App\Filament\Resources\FrontendResource\RelationManagers;
use App\Models\Frontend;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FrontendResource extends Resource
{
    protected static ?string $model = Frontend::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('about_us')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('terms')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('privacy')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('footer_info')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('facebook')
                    ->maxLength(193)
                    ->placeholder('https://facebook.com'),
                Forms\Components\TextInput::make('instagram')
                    ->placeholder('https://instagram.com')
                    ->maxLength(193),
                Forms\Components\TextInput::make('twitter(X)')
                    ->placeholder('https://x.com')
                    ->maxLength(193),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('about_us')
                    ->searchable(),
                Tables\Columns\TextColumn::make('terms')
                    ->searchable(),
                Tables\Columns\TextColumn::make('privacy')
                    ->searchable(),
                Tables\Columns\TextColumn::make('footer_info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('facebook')
                    ->searchable(),
                Tables\Columns\TextColumn::make('instagram')
                    ->searchable(),
                Tables\Columns\TextColumn::make('twitter')
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
            'index' => Pages\ListFrontends::route('/'),
            'create' => Pages\CreateFrontend::route('/create'),
            'edit' => Pages\EditFrontend::route('/{record}/edit'),
        ];
    }
}
