<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialCasinoResource\Pages;
use App\Models\SocialCasino;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SocialCasinoResource extends Resource
{
    protected static ?string $navigationGroup = 'Passive';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $label = 'Social Casino';

    protected static ?string $pluralModelLabel = 'Social Casinos';

    protected static ?int $navigationSort = 1;

    protected static ?string $model = SocialCasino::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('logo')
                            ->collection('logo')
                            ->columnSpanFull(),
                        Forms\Components\ToggleButtons::make('tier')
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                            ])
                            ->required()
                            ->inline()
                            ->default(1),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->required()
                            ->url()
                            ->maxLength(500),
                        Forms\Components\TextInput::make('referral_url')
                            ->label('Referral URL')
                            ->url()
                            ->nullable()
                            ->maxLength(255),
                    ])->columns(),
                Forms\Components\Section::make('Financial Information')
                    ->schema([
                        Forms\Components\TextInput::make('daily_bonus')
                            ->label('Daily Bonus')
                            ->prefix('$')
                            ->numeric(),
                        Forms\Components\RichEditor::make('daily_location')
                            ->label('Daily Bonus Location')
                            ->hint('How to find the daily bonus after logging in.'),
                        Forms\Components\TextInput::make('signup_bonus')
                            ->label('Signup Bonus')
                            ->maxLength(500),
                        Forms\Components\TextInput::make('referral_bonus')
                            ->label('Referral Bonus')
                            ->maxLength(500),
                        Forms\Components\TextInput::make('minimum_redemption')
                            ->label('Minimum Redemption')
                            ->required(),
                        Forms\Components\TextInput::make('token_type')
                            ->label('Token Type')
                            ->required()
                            ->maxLength(10)
                            ->default('SC'),
                        Forms\Components\TextInput::make('token_amount_per_dollar')
                            ->label('Token Amount Per Dollar')
                            ->required()
                            ->numeric()
                            ->default(1),
                        Forms\Components\Toggle::make('real_money_payout')
                            ->label('Real Money Payout?')
                            ->required(),
                    ])
                    ->columns(),
                Forms\Components\Section::make('Casino Information')
                    ->schema([
                        Forms\Components\Toggle::make('usa_allowed')
                            ->label('USA Allowed?'),
                        Forms\Components\Toggle::make('canada_allowed')
                            ->label('Canada Allowed?'),
                        Forms\Components\Toggle::make('must_play_before_redemption'),
                        Forms\Components\TagsInput::make('usa_excluded')
                            ->label('USA Excluded Territories'),
                        Forms\Components\TagsInput::make('canada_excluded')
                            ->label('Canada Excluded Territories'),
                        Forms\Components\TextInput::make('redemption_time')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('terms_url')
                            ->label('Terms URL')
                            ->maxLength(500)
                            ->url(),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Misc')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('best_playthrough_game')
                                    ->label('Best Playthrough Game')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('best_playthrough_game_url')
                                    ->label('Best Playthrough Game URL')
                                    ->maxLength(500)
                                    ->url(),
                            ])
                            ->columns(),
                        Forms\Components\Toggle::make('sweeps_extension_eligible')
                            ->label('Sweeps Extension Eligible?'),
                        Forms\Components\RichEditor::make('notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logo'),
                Tables\Columns\TextColumn::make('tier')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialCasinos::route('/'),
            'create' => Pages\CreateSocialCasino::route('/create'),
            'edit' => Pages\EditSocialCasino::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
