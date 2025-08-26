<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtraScoreResource\Pages;
use App\Filament\Resources\ExtraScoreResource\RelationManagers;
use App\Models\ExtraScore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExtraScoreResource extends Resource
{
    protected static ?string $model = ExtraScore::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = ' امتیاز‌ها';
    protected static ?string $pluralLabel = 'امتیاز‌ها';
    protected static ?string $modelLabel = 'امتیاز';

    protected static ?string $navigationGroup = 'عمومی';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('team_id')
                    ->required()
                    ->label('شناسه تیم')
                    ->relationship('team', 'name')
                    ->preload()
                    ->searchable(),
                Forms\Components\Field::make('team_qr')
                    ->label('شناسه تیم')
                    ->view('livewire.team-qr-scanner'),
                Forms\Components\TextInput::make('score')
                    ->label('امتیاز اضافی')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('reason')
                    ->label('دلیل امتیاز اضافی')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('team_id')
                    ->numeric()
                    ->label('شناسه تیم')
                    ->sortable(),
                Tables\Columns\TextColumn::make('score')
                    ->numeric()
                    ->label('امتیاز اضافی')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->label('دلیل امتیاز اضافی')
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



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExtraScores::route('/'),
            'create' => Pages\CreateExtraScore::route('/create'),
            'edit' => Pages\EditExtraScore::route('/{record}/edit'),
        ];
    }
}
