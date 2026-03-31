<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArlistaTetelResource\Pages;
use App\Models\ArlistaTetel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArlistaTetelResource extends Resource
{
    protected static ?string $model = ArlistaTetel::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Árlista';
    protected static ?string $modelLabel = 'Árlista tétel';
    protected static ?string $pluralModelLabel = 'Árlista tételek';
    protected static ?int $navigationSort = 2;

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
                Forms\Components\TextInput::make('muveletnev')
                    ->label('Beavatkozás neve')
                    ->required(),
                Forms\Components\TextInput::make('ar')
                    ->label('Ár (Ft)')
                    ->required()
                    ->suffix('Ft')
                    ->helperText('Csak a számot írja be, pl.: 25000')
                    ->afterStateHydrated(function ($state, $set) {
                        // Ha a tárolt érték szöveges (pl. "3.000 Ft"), kiszedjük a számot
                        if ($state && !is_numeric($state)) {
                            $cleaned = preg_replace('/[^0-9]/', '', $state);
                            $set('ar', $cleaned);
                        }
                    }),
                Forms\Components\TextInput::make('kiegeszites')
                    ->label('Kiegészítés')
                    ->placeholder('Pl.: max. 10 perc, / fogív, stb.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kategoria.nev')
                    ->label('Kategória')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('muveletnev')
                    ->label('Beavatkozás')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ar')
                    ->label('Ár')
                    ->formatStateUsing(fn ($state) => number_format((float)$state, 0, ',', '.') . ' Ft')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kiegeszites')
                    ->label('Kiegészítés')
                    ->placeholder('—'),
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
            'index' => Pages\ListArlistaTetels::route('/'),
            'create' => Pages\CreateArlistaTetel::route('/create'),
            'edit' => Pages\EditArlistaTetel::route('/{record}/edit'),
        ];
    }
}
