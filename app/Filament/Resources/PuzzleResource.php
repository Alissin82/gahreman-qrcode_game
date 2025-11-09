<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PuzzleResource\Pages;
use App\Filament\Resources\PuzzleResource\RelationManagers;
use App\Models\Puzzle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PuzzleResource extends Resource
{
    protected static ?string $model = Puzzle::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationLabel = 'پازل‌ها';
    protected static ?string $pluralLabel = 'پازل‌ها';
    protected static ?string $modelLabel = 'پازل';

    protected static ?string $navigationGroup = 'عمومی';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام پازل')
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('puzzles'),
                Forms\Components\Repeater::make('piece')
                    ->label('قطعات پازل')
                    ->relationship('piece')
                    ->schema([
                        Forms\Components\TextInput::make('piece_data')
                            ->label('اطلاعات قطعه پازل')
                            ->required(),
                    ])
                    ->minItems(6)
                    ->maxItems(6)
                    ->defaultItems(6)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPuzzles::route('/'),
            'create' => Pages\CreatePuzzle::route('/create'),
            'edit' => Pages\EditPuzzle::route('/{record}/edit'),
        ];
    }
}
