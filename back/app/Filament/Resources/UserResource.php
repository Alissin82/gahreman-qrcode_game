<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = ' کاربر‌ها';
    protected static ?string $pluralLabel = 'کاربر‌ها';
    protected static ?string $modelLabel = 'کاربر';

    protected static ?string $navigationGroup = 'سیستم';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('نام')->required(),
                Forms\Components\TextInput::make('phone')->label('شماره تلفن')->tel()->required(),
                Forms\Components\TextInput::make('email')->label('ایمیل')->email(),
                Forms\Components\DateTimePicker::make('email_verified_at')->label('تاریخ تایید ایمیل')->default(now())->visible(false),
                Forms\Components\TextInput::make('age')->label('سن')->required()->numeric()->default(12),
                Forms\Components\Select::make('gender')
                    ->label('جنسیت')
                    ->options([
                        1 => 'پسر',
                        0 => 'دختر',
                    ])
                    ->required(),
                Forms\Components\Select::make('team_id')->label('تیم')->relationship('team', 'name')->preload()->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('age')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('gender')
                    ->boolean(),
                Tables\Columns\TextColumn::make('teams_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('otp')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
