<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GaleriaResource\Pages;
use App\Models\Galeria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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
                Forms\Components\Placeholder::make('kep_elonezet')
                    ->label('Jelenlegi kép')
                    ->content(fn ($record) => $record?->fajlnev
                        ? new HtmlString('<img src="/' . e($record->fajlnev) . '" style="max-height:220px; border-radius:8px; box-shadow:0 2px 12px rgba(0,0,0,0.12);">')
                        : '—'
                    )
                    ->visibleOn('edit'),
                Forms\Components\TextInput::make('fajlnev')
                    ->label('Fájl elérési út')
                    ->helperText('Pl.: images/galeria/kep.jpg')
                    ->placeholder('images/galeria/kep.jpg'),
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
                    ->disk('public'),
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
