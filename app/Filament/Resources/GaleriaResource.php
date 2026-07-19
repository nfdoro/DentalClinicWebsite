<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GaleriaResource\Pages;
use App\Models\Galeria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GaleriaResource extends Resource
{
    protected static ?string $model = Galeria::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Galéria';

    protected static ?string $modelLabel = 'Kép';

    protected static ?string $pluralModelLabel = 'Galéria képek';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kategoria_id')
                    ->label('Kategória')
                    ->relationship('kategoria', 'nev')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\FileUpload::make('fajlnev')
                    ->label('Kép')
                    ->image()
                    ->disk('kepek')
                    ->directory('images/galeria')
                    ->visibility('public')
                    ->imagePreviewHeight('180')
                    ->helperText('Húzd ide a képet, vagy kattints a feltöltéshez.'),
                Forms\Components\TextInput::make('rovidleiras')
                    ->label('Rövid leírás')
                    ->required()
                    ->placeholder('Pl.: Fogfehérítés előtt / után'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('fajlnev')
                    ->label('Kép')
                    ->disk('kepek'),
                Tables\Columns\TextColumn::make('kategoria.nev')
                    ->label('Kategória')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('rovidleiras')
                    ->label('Leírás')
                    ->searchable(),
            ])
            ->defaultSort('kategoria.nev')
            ->filters([
                Tables\Filters\SelectFilter::make('kategoria')
                    ->relationship('kategoria', 'nev')
                    ->label('Kategória'),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalerias::route('/'),
            'create' => Pages\CreateGaleria::route('/create'),
            'edit' => Pages\EditGaleria::route('/{record}/edit'),
        ];
    }
}
