<?php

namespace App\Filament\Resources\Suppliers;

use App\Enums\Gender;
use App\Filament\Resources\Suppliers\Pages\ManageSuppliers;
use App\Models\Supplier;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        $nameField = TextInput::make('name')
            ->maxLength(255)
            ->required();
        $emailField = TextInput::make('email')
            ->email()
            ->unique();
        $phoneField = TextInput::make('phone')
            ->tel()
            ->required();
        $genderField = Select::make('gender')
            ->options(Gender::class);
        $imageField = FileUpload::make('image')
            ->hiddenLabel()
            ->image()
            ->avatar()
            ->imageEditor()
            ->imageEditorAspectRatioOptions(['1:1']);

        $layout = [
            Section::make([
                $imageField->alignCenter()->columnSpanFull(),
                Grid::make(3)
                    ->schema([
                        $nameField->columnSpan(2),
                        $phoneField->columnSpan(1),
                        $emailField->columnSpan(2),
                        $genderField->columnSpan(1),
                    ]),
            ])->columnSpanFull()
        ];

        return $schema->dense()->components($layout);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('phone'),
                TextEntry::make('phone_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                ImageEntry::make('image')
                    ->placeholder('-'),
                TextEntry::make('gender')
                    ->badge()
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('entry_by')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
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
                ImageColumn::make('image')
                    ->label('')
                    ->width('1%')
                    ->circular()
                    ->defaultImageUrl(fn (Model $record): string => $record->getFilamentAvatarUrl()),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->placeholder('--')
                    ->searchable(),
                TextColumn::make('gender')
                    ->placeholder('--')
                    ->sortable(),
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
            ->filters([
                //
            ])
            ->defaultSort(fn (Builder $query): Builder => $query->orderByDesc('is_active')->orderByDesc('created_at'))
            ->recordActions([
                ViewAction::make()
                    ->modalHeading('View record')
                    ->modalWidth(Width::ThreeExtraLarge)
                    ->modalCancelAction(false),
                EditAction::make()
                    ->modalHeading('Edit record')
                    ->modalWidth(Width::ThreeExtraLarge),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSuppliers::route('/'),
        ];
    }
}
