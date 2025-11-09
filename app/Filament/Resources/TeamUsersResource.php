<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamUsersResource\Pages;
use App\Filament\Resources\TeamUsersResource\RelationManagers;
use App\Models\TeamUsers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamUsersResource extends Resource
{
    protected static ?string $model = TeamUsers::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'اعضای تیم‌ها ';
    protected static ?string $pluralLabel = 'اعضای تیم‌ها';
    protected static ?string $modelLabel = 'اعضای تیم';

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
                Forms\Components\TextInput::make('national_code')
                    ->default('')
                    ->label('کدملی'),
                Forms\Components\TextInput::make('glevel')
                    ->label('پایه تحصیلی')

                    ->default('')
                    ->numeric(),
                Forms\Components\TextInput::make('school')
                    ->default('')
                    ->label('نام مدرسه'),

                Forms\Components\TextInput::make('reagon')

                    ->default('')
                    ->label('منطقه'),
                Forms\Components\TextInput::make('city')

                    ->default('')
                    ->label('شهر'),
                Forms\Components\TextInput::make('province')

                    ->default('')
                    ->label('استان'),
                Forms\Components\TextInput::make('student_code')

                    ->default('')
                    ->label('کد دانش‌آموزی'),
                Forms\Components\TextInput::make('basij_code')

                    ->default('')
                    ->label('کد بسیج'),
                Forms\Components\TextInput::make('average')

                    ->default('')
                    ->label('معدل')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('family')
                    ->searchable(),
                Tables\Columns\TextColumn::make('national_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('glevel')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('school')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reagon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('province')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('basij_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('average')
                    ->numeric()
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
            'index' => Pages\ListTeamUsers::route('/'),
            'create' => Pages\CreateTeamUsers::route('/create'),
            'edit' => Pages\EditTeamUsers::route('/{record}/edit'),
        ];
    }
}
