<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScoreCardResource\Pages;
use App\Filament\Resources\ScoreCardResource\RelationManagers;
use App\Models\ScoreCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScoreCardResource extends Resource
{
    protected static ?string $model = ScoreCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift-top';
    protected static ?string $navigationLabel = '  امتیاز‌ها';
    protected static ?string $pluralLabel = ' امتیاز‌ها';
    protected static ?string $modelLabel = ' امتیاز';

    protected static ?string $navigationGroup = 'کارت ها';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام')
                    ->required(),
                Forms\Components\TextInput::make('score')
                    ->label('امتیاز')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('score')
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
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('print_qr')
                    ->label('چاپ QR')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('QR Code ها')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->modalWidth('sm')
                    ->modalContent(function (\App\Models\ScoreCard $record) {
                        $items = [];

                        $ActionPayload = [
                            'type' => 'score',
                            'id' => $record->id,
                        ];

                        $json = json_encode($ActionPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                        $png = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                            ->encoding('UTF-8')
                            ->size(100)
                            ->margin(2)
                            ->generate($json);

                        $items[] = [
                            'label' => "[ {$record->id} ]",
                            'src'   => 'data:image/png;base64,' . base64_encode($png),
                        ];

                        return view('filament.actions.qr-grid', ['items' => $items]);
                    }),
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
            'index' => Pages\ListScoreCards::route('/'),
            'create' => Pages\CreateScoreCard::route('/create'),
            'edit' => Pages\EditScoreCard::route('/{record}/edit'),
        ];
    }
}
