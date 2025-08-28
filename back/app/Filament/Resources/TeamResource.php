<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = ' تیم‌ها';
    protected static ?string $pluralLabel = 'تیم‌ها';
    protected static ?string $modelLabel = 'تیم';

    protected static ?string $navigationGroup = 'عمومی';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('content')
                    ->label('عکس پروفایل')
                    ->avatar()
                    ->directory('profiles'),
                Forms\Components\TextInput::make('name')->label('نام')
                    ->required(),
                Forms\Components\TextInput::make('bio')->label('شعار')
                    ->required(),
                Forms\Components\ColorPicker::make('color')->label('رنگ')->default('#ff0000'),

                Forms\Components\Select::make('gender')
                    ->label('جنسیت')
                    ->options([
                        1 => 'پسر',
                        0 => 'دختر',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('score')->label('امتیاز')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('coin')->label('سکه')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('نام')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')->label('رنگ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('score')->label('امتیاز')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coin')->label('سکه')
                    ->numeric()
                    ->sortable(),
                ViewColumn::make('hash_qr')
                    ->label('QR Code')
                    ->view('filament.tables.columns.qr-code'),
                Tables\Columns\TextColumn::make('total_mission_score')->label('مجموع امتیازات توکن‌ها')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('تاریخ ایجاد')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('بروزرسانی شده در')
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
