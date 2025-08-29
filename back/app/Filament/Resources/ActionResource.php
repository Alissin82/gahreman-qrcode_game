<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionResource\Pages;
use App\Models\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ActionResource extends Resource
{
    protected static ?string $model = Action::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = ' عملیات‌ها';
    protected static ?string $pluralLabel = 'عملیات‌ها';
    protected static ?string $modelLabel = 'عملیات';

    protected static ?string $navigationGroup = 'عمومی';
    public static function form(Form $form): Form
    {
        $tasks = [
            Forms\Components\Select::make('type')
                ->label('نوع')
                ->options([
                    'scan' => 'پایان ماموریت',
                    'question' => 'سوال چهارگزینه‌ای',
                    'content' => 'نمایش محتوا (تصویر/فیلم)',
                    'message' => 'نمایش پیام',
                    'intrupt' => 'توقف'
                ])
                ->required()
                ->reactive(),

            Forms\Components\TextInput::make('score')
                ->label('امتیاز')
                ->numeric()
                ->default(0)
                ->required(),

            Forms\Components\TextInput::make('duration')
                ->label('مدت زمان')
                ->numeric()
                ->default(0)
                ->required(),
            Forms\Components\TextInput::make('question')
                ->label('متن سوال')
                ->visible(fn(Forms\Get $get) => $get('type') === 'question'),

            Forms\Components\Grid::make(4)
                ->schema([
                    Forms\Components\TextInput::make('option1')
                        ->required()
                        ->label('گزینه یک'),
                    Forms\Components\TextInput::make('option2')
                        ->label('گزینه دو'),
                    Forms\Components\TextInput::make('option3')
                        ->label('گزینه سه'),
                    Forms\Components\TextInput::make('option4')
                        ->label('گزینه چهار')
                ])
                ->visible(fn(Forms\Get $get) => $get('type') === 'question'),

            Forms\Components\Select::make('answer')
                ->label('پاسخ صحیح')
                ->options([
                    1 => 'گزینه ۱',
                    2 => 'گزینه ۲',
                    3 => 'گزینه ۳',
                    4 => 'گزینه ۴',
                ])
                ->default(1)
                ->required()
                ->visible(fn(Forms\Get $get) => $get('type') === 'question'),

            Forms\Components\FileUpload::make('content')
                ->label('فایل محتوا')
                ->directory('contents')
                ->visible(fn(Forms\Get $get) => $get('type') === 'content'),

            Forms\Components\Textarea::make('text')
                ->label('متن محتوا')
                ->visible(fn(Forms\Get $get) => $get('type') === 'message'),

            Forms\Components\Toggle::make('need_review')
                ->label('نیاز به بازبینی')
                ->default(false)
        ];

        $missions = [
            Forms\Components\TextInput::make('title')
                ->label('عنوان')
                ->required(),

            Forms\Components\Repeater::make('tasks')
                ->label('وظیفه‌ها')
                ->relationship('tasks')
                ->schema($tasks)
                ->reorderable()
                ->orderColumn('order')
                ->minItems(1),
        ];

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام عملیات')
                    ->required(),

                Forms\Components\DateTimePicker::make('release')
                    ->default(now())
                    ->jalali()
                    ->seconds(false)
                    ->label('تارخ انتشار')
                    ->required(),

                Forms\Components\Select::make('region_id')
                    ->label('منطقه')
                    ->relationship('region', 'name')
                    ->searchable()
                    ->preload(),

                Forms\Components\Repeater::make('missions')
                    ->label('ماموریت‌ها')
                    ->relationship('missions')
                    ->schema($missions)
                    ->reorderable()
                    ->orderColumn('order')
                    ->minItems(1),

                Forms\Components\Repeater::make('dependency')
                    ->label('پیشنیاز ها')
                    ->relationship('dependency')
                    ->schema([
                        Forms\Components\Select::make('depends_on_action_id')
                            ->required()
                            ->label('شناسه عملیات')
                            ->relationship('action', 'name')
                            ->preload()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->searchable(),
                    ])->default([])
                    ->reorderable()
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('نام عملیات')->searchable(),
                Tables\Columns\TextColumn::make('missions_count')
                    ->counts('missions')
                    ->label('تعداد مأموریت‌ها')
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('print_qr')
                    ->label('چاپ QR')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('QR Code ها')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->color('gray')
                    ->modalWidth('sm')
                    ->modalContent(function (Action $record) {
                        $items = [];

                        $ActionPayload = [
                            'type' => 'action_start',
                            'id' => $record->id,
                            'missions' => $record->missions->pluck('id')->values()->all(),
                        ];

                        $json = json_encode($ActionPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                        $png = QrCode::format('png')
                            ->encoding('UTF-8')
                            ->size(100)
                            ->margin(2)
                            ->generate($json);

                        $items[] = [
                            'label' => "شروع عملیات - " . $record->name,
                            'src'   => 'data:image/png;base64,' . base64_encode($png),
                        ];

                        $ActionPayload = [
                            'type' => 'action_end',
                            'id' => $record->id,
                            'missions' => $record->missions->pluck('id')->values()->all(),
                        ];

                        $json = json_encode($ActionPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                        $png = QrCode::format('png')
                            ->encoding('UTF-8')
                            ->size(100)
                            ->margin(2)
                            ->generate($json);

                        $items[] = [
                            'label' => "پایان عملیات - " . $record->name,
                            'src'   => 'data:image/png;base64,' . base64_encode($png),
                        ];

                        // QR جدا برای هر mission
                        foreach ($record->missions as $mission) {
                            $payload = [
                                'type' => 'mission_start',
                                'id' => $mission->id,
                                'action_id' => $record->id,
                            ];

                            $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                            $png = QrCode::format('png')
                                ->encoding('UTF-8')
                                ->size(100)
                                ->margin(2)
                                ->generate($json);

                            $items[] = [
                                'label' => "شروع ماموریت - " . $mission->title,
                                'src'   => 'data:image/png;base64,' . base64_encode($png),
                            ];
                        }

                        foreach ($record->missions as $mission) {
                            $payload = [
                                'type' => 'mission_end',
                                'id' => $mission->id,
                                'action_id' => $record->id,
                            ];

                            $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                            $png = QrCode::format('png')
                                ->encoding('UTF-8')
                                ->size(100)
                                ->margin(2)
                                ->generate($json);

                            $items[] = [
                                'label' => "پایان ماموریت - " . $mission->title,
                                'src'   => 'data:image/png;base64,' . base64_encode($png),
                            ];
                        }
                        return view('filament.actions.qr-grid', ['items' => $items]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActions::route('/'),
            'create' => Pages\CreateAction::route('/create'),
            'edit' => Pages\EditAction::route('/{record}/edit'),
        ];
    }
}
