<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use App\Models\Kategoria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationLabel = 'GYIK kérdések';
    protected static ?string $modelLabel = 'GYIK';
    protected static ?string $pluralModelLabel = 'GYIK kérdések';
    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kategoria_id')
                    ->label('Szolgáltatás')
                    ->options(Kategoria::where('szolgaltatas', true)->pluck('nev', 'id'))
                    ->required()
                    ->searchable(),

                Forms\Components\TextInput::make('sorrend')
                    ->label('Sorrend')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(255)
                    ->helperText('Kisebb szám = előrébb jelenik meg.'),

                Forms\Components\TextInput::make('kerdes')
                    ->label('Kérdés')
                    ->required()
                    ->maxLength(500)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('valasz')
                    ->label('Válasz')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kategoria.nev')
                    ->label('Szolgáltatás')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kerdes')
                    ->label('Kérdés')
                    ->limit(80)
                    ->searchable(),

                Tables\Columns\TextColumn::make('sorrend')
                    ->label('Sorrend')
                    ->sortable(),
            ])
            ->defaultSort('kategoria_id')
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
            'index'  => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit'   => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
