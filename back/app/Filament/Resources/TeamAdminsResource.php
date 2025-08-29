<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamAdminsResource\Pages;
use App\Filament\Resources\TeamAdminsResource\RelationManagers;
use App\Models\TeamAdmins;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamAdminsResource extends Resource
{
    protected static ?string $model = TeamAdmins::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'مربی‌ها ';
    protected static ?string $pluralLabel = 'مربی‌ها';
    protected static ?string $modelLabel = 'مربی';

    protected static ?string $navigationGroup = 'تیم';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name')
                    ->label('شناسه تیم')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('نام')
                    ->required(),
                Forms\Components\TextInput::make('family')
                    ->label('نام خانوادگی')
                    ->required(),
                Forms\Components\TextInput::make('gender')
                    ->label('جنسیت'),
                Forms\Components\DateTimePicker::make('start')
                    ->label('تاریخ شروع'),
                Forms\Components\TextInput::make('national_code')
                    ->label('کدملی'),
                Forms\Components\TextInput::make('phone')
                    ->label('تلفن')
                    ->tel(),
                Forms\Components\TextInput::make('history')
                    ->label('سابقه'),
                Forms\Components\TextInput::make('description')
                    ->label('توضیحات'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('team_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('family')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('national_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('history')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
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
            'index' => Pages\ListTeamAdmins::route('/'),
            'create' => Pages\CreateTeamAdmins::route('/create'),
            'edit' => Pages\EditTeamAdmins::route('/{record}/edit'),
        ];
    }
}
