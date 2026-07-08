<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriaResource\Pages;
use App\Models\Kategoria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KategoriaResource extends Resource
{
    protected static ?string $model = Kategoria::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Kategóriák';
    protected static ?string $modelLabel = 'Kategória';
    protected static ?string $pluralModelLabel = 'Kategóriák';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nev')
                    ->label('Név')
                    ->required()
                    ->placeholder('Pl.: Gyökérkezelés'),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->required()
                    ->helperText('Pl.: gyokerkezeles')
                    ->placeholder('Pl.: gyokerkezeles'),
                Forms\Components\Textarea::make('leiras')
                    ->label('Leírás')
                    ->placeholder('Rövid leírás a szolgáltatásról...')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('kiemelt_leiras')
                    ->label('Kiemelt leírás (hosszú, SEO-optimalizált)')
                    ->helperText('Csak a kiemelt kezeléseknél töltsd ki: fogszabályozás, implantátum, fogfehérítés.')
                    ->toolbarButtons([
                        'bold', 'italic', 'underline',
                        'h2', 'h3',
                        'bulletList', 'orderedList',
                        'link', 'blockquote',
                        'undo', 'redo',
                    ])
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('icon')
                    ->label('Ikon (fájl elérési út)')
                    ->helperText('Pl.: images/icons/gyoker.png')
                    ->placeholder('images/icons/gyoker.png'),
                Forms\Components\Toggle::make('szolgaltatas')
                    ->label('Megjelenik a Szolgáltatásaink menüben')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nev')
                    ->label('Név')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                Tables\Columns\IconColumn::make('szolgaltatas')
                    ->label('Szolgáltatás')
                    ->boolean(),
                Tables\Columns\TextColumn::make('arlistaTetelei_count')
                    ->label('Árlista tételek')
                    ->counts('arlistaTetelei'),
                Tables\Columns\TextColumn::make('galeria_count')
                    ->label('Galéria képek')
                    ->counts('galeria'),
            ])
            ->defaultSort('nev')
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
            'index' => Pages\ListKategorias::route('/'),
            'create' => Pages\CreateKategoria::route('/create'),
            'edit' => Pages\EditKategoria::route('/{record}/edit'),
        ];
    }
}
