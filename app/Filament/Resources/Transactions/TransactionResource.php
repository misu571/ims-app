<?php

namespace App\Filament\Resources\Transactions;

use App\Enums\Status;
use App\Enums\TransactionType;
use App\Filament\Resources\Transactions\Pages\ManageTransactions;
use App\Models\Transaction;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'trx_id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('items')
                    ->relationship()
                    ->table([
                        TableColumn::make('Product')
                            ->width('70%'),
                        TableColumn::make('Quantity'),
                    ])
                    ->compact()
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Model $record): string => "[{$record->code}] {$record->name}")
                            ->searchable(['code', 'name'])
                            ->preload()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->required()
                            ->columnSpan(5),
                        TextInput::make('quantity')
                            ->integer()
                            ->step(1)
                            ->minValue(1)
                            ->required()
                            ->columnSpan(2),
                    ])
                    ->addActionLabel('Add new item')
                    ->addActionAlignment(Alignment::End)
                    ->reorderable(false)
                    ->columns(7)
                    ->columnSpanFull()
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        TextEntry::make('trx_id'),
                        TextEntry::make('trx_date')
                            ->date(),
                        TextEntry::make('trx_type')
                            ->badge(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('validate_by')
                            ->numeric(),
                        TextEntry::make('validate_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('entry_by')
                            ->numeric(),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])->columnSpanFull(),
                RepeatableEntry::make('items')
                    ->table([
                        TableColumn::make('Product')
                            ->width('70%'),
                        TableColumn::make('Quantity')
                            ->alignment(Alignment::End),
                    ])
                    ->schema([
                        TextEntry::make('product.name'),
                        TextEntry::make('quantity')
                            ->alignment(Alignment::End),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->recordTitleAttribute('trx_id')
            ->columns([
                TextColumn::make('index')
                    ->label('SL#')
                    ->width('1%')
                    ->rowIndex()
                    ->fontFamily(FontFamily::Mono)
                    ->alignment(Alignment::End),
                TextColumn::make('trx_id')
                    ->searchable(),
                TextColumn::make('trx_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('trx_type')
                    ->label('Type')
                    ->searchable(),
                TextColumn::make('total_item')
                    ->sortable(),
                TextColumn::make('total_price')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('validateBy.name')
                    ->label('Validate by')
                    ->placeholder('--')
                    ->searchable(),
                TextColumn::make('validate_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('entryBy.name')
                    ->label('Entry by')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordUrl(null)
            ->recordActions([
                ViewAction::make()
                    ->modalHeading('View transaction')
                    ->modalWidth(Width::ThreeExtraLarge),
                EditAction::make()
                    ->hidden(fn (Model $record): bool => $record->trx_type == TransactionType::Sale)
                    ->modalHeading('Edit transaction')
                    ->modalWidth(Width::ThreeExtraLarge)
                    ->databaseTransaction(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTransactions::route('/'),
        ];
    }
}
