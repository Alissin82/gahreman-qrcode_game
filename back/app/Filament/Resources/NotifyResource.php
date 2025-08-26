<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotifyResource\Pages;
use App\Filament\Resources\NotifyResource\RelationManagers;
use App\Jobs\SendNotifyJob;
use App\Models\Notify;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotifyResource extends Resource
{
    protected static ?string $model = Notify::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = ' اعلان‌ها';
    protected static ?string $pluralLabel = 'اعلان‌ها';
    protected static ?string $modelLabel = 'اعلان';

    protected static ?string $navigationGroup = 'عمومی';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('عنوان')
                    ->required(),
                Forms\Components\TextInput::make('content')
                    ->label('محتوا')
                    ->required(),
                Forms\Components\Toggle::make('sms')
                    ->required(),
                Forms\Components\Toggle::make('app')
                    ->required(),

                Forms\Components\Repeater::make('teams')
                    ->label('تیم ها')
                    ->relationship('notify_teams')
                    ->schema([
                        Forms\Components\Select::make('team_id')
                            ->required()
                            ->label('شناسه تیم')
                            ->relationship('team', 'name')
                            ->preload()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->searchable(),
                    ])
                    ->unique()
                    ->default([]),
                Forms\Components\DateTimePicker::make('release')
                    ->default(now())
                    ->jalali()
                    ->seconds(false)
                    ->label('تارخ انتشار')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('content')
                    ->searchable(),
                Tables\Columns\IconColumn::make('sms')
                    ->boolean(),
                Tables\Columns\IconColumn::make('app')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->getStateUsing(function ($record) {
                        if ($record->release->isPast()) {
                            return 'منتشر شده';
                        }

                        $diff = $record->release->diffForHumans(Carbon::now(), [
                            'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
                            'short' => false,
                        ]);

                        return "در انتظار ارسال ($diff)";
                    }),
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
    public static function afterCreate($record): void
    {
        SendNotifyJob::dispatch($record)->delay($record->release);
    }

    public static function afterSave($record): void
    {
        SendNotifyJob::dispatch($record)->delay($record->release);
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
            'index' => Pages\ListNotifies::route('/'),
            'create' => Pages\CreateNotify::route('/create'),
            'edit' => Pages\EditNotify::route('/{record}/edit'),
        ];
    }
}
