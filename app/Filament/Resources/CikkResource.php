<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CikkResource\Pages;
use App\Models\Cikk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CikkResource extends Resource
{
    protected static ?string $model = Cikk::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Blog cikkek';
    protected static ?string $modelLabel = 'Cikk';
    protected static ?string $pluralModelLabel = 'Cikkek';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Tartalom')
                    ->schema([
                        Forms\Components\TextInput::make('cim')
                            ->label('Cím')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->unique(Cikk::class, 'slug', ignoreRecord: true)
                            ->helperText('Auto-generált a cím alapján, de szerkeszthető.'),

                        Forms\Components\Textarea::make('bevezeto')
                            ->label('Bevezető')
                            ->required()
                            ->rows(3)
                            ->helperText('Rövid összefoglaló, a blog lista oldalon jelenik meg.')
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('tartalom')
                            ->label('Tartalom')
                            ->required()
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'h2', 'h3',
                                'bulletList', 'orderedList',
                                'link',
                                'blockquote',
                                'undo', 'redo',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Média')
                    ->schema([
                        Forms\Components\FileUpload::make('boritekep')
                            ->label('Borítókép')
                            ->image()
                            ->disk('public')
                            ->directory('images/blog')
                            ->imagePreviewHeight('150')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\Textarea::make('meta_leiras')
                            ->label('Meta leírás')
                            ->maxLength(320)
                            ->rows(2)
                            ->helperText('Max 320 karakter. Ha üres, a bevezető szöveg kerül ide.')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('kulcsszavak')
                            ->label('Kulcsszavak')
                            ->maxLength(500)
                            ->placeholder('pl. invisalign miskolc, fogszabályozás ár')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Közzététel')
                    ->schema([
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Közzététel dátuma')
                            ->helperText('Üresen hagyva a cikk vázlatként marad és nem jelenik meg.')
                            ->native(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cim')
                    ->label('Cím')
                    ->searchable()
                    ->sortable()
                    ->limit(60),

                Tables\Columns\BadgeColumn::make('published_at')
                    ->label('Állapot')
                    ->formatStateUsing(fn ($state) => $state ? 'Közzétett' : 'Vázlat')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Közzétéve')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Létrehozva')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index'  => Pages\ListCikkeks::route('/'),
            'create' => Pages\CreateCikk::route('/create'),
            'edit'   => Pages\EditCikk::route('/{record}/edit'),
        ];
    }
}
