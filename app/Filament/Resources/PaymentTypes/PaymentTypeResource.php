<?php

namespace App\Filament\Resources\PaymentTypes;

use App\Filament\Resources\PaymentTypes\Pages\ManagePaymentTypes;
use App\Models\PaymentType;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentTypeResource extends Resource
{
    protected static ?string $model = PaymentType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->unique()
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('index')
                    ->label('SL#')
                    ->width('1%')
                    ->rowIndex()
                    ->fontFamily(FontFamily::Mono)
                    ->alignment(Alignment::End),
                TextColumn::make('name')
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label('Status'),
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
            ->defaultSort(fn (Builder $query): Builder => $query->orderByDesc('is_active')->orderByDesc('created_at'))
            ->recordAction(null)
            ->recordUrl(null)
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit record')
                    ->modalWidth(Width::Medium),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePaymentTypes::route('/'),
        ];
    }
}
