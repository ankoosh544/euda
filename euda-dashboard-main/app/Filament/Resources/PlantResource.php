<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlantResource\Pages;
use App\Filament\Resources\PlantResource\RelationManagers;
use App\Models\Plant;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Tables\Actions\Action;

class PlantResource extends Resource
{
    protected static ?string $model = Plant::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';
    protected static ?string $modelLabel = 'Plant';
    protected static ?string $pluralModelLabel = 'Plants list';
    protected static ?string $navigationLabel = 'Plants list';
    protected static ?string $recordTitleAttribute = 'Plants list';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'topic', 'datastore', 'city', 'state', 'address', 'cap'];
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Name' => $record->name,
            'Address' => $record->getCompleteAddress(),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (Auth::User()->is_admin) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->where('owner_id', Auth::user()->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tecnical data')->schema([
                    Group::make()->schema([
                        TextInput::make('name')->label('Name')->required()->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('slug', Str::slug($state));
                            $set('topic', Str::slug($state));
                            $set('datastore', Str::slug($state));
                        }),
                        TextInput::make('slug')->label('Slug')->required(),
                    ])->columns(2),
                    Group::make()->schema([
                        Select::make('owner_id')
                            ->label('Owner')
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable()->required(),
                        TextInput::make('topic')->label('Topic')->required(),
                        TextInput::make('datastore')->label('Datastore')->required(),
                    ])->columns(3),
                ]),
                Section::make('Address info')->schema([
                    TextInput::make('state')->label('State')->required(),
                    TextInput::make('city')->label('City')->required(),
                    TextInput::make('address')->label('Address')->required(),
                    TextInput::make('cap')->label('CAP')->required(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        $actions[] = Action::make('Open')
            ->url(fn (Plant $record): string => route('filament.resources.plants.view', $record))
            ->icon('heroicon-o-external-link');

        if(Auth::user()->is_admin)
            $actions[] = Tables\Actions\EditAction::make();

        return $table
            ->columns([
                TextColumn::make('name')->label('Name')->sortable()->searchable(),
                TextColumn::make('owner.name')->label('Owner')->sortable()->searchable(),
                TextColumn::make('state')->label('State')->sortable()->searchable(),
                TextColumn::make('city')->label('City')->sortable()->searchable(),
                TextColumn::make('address')->label('Address')->sortable()->searchable(),
                TextColumn::make('cap')->label('CAP')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions($actions)
            ->bulkActions(
                Auth::user()->is_admin ?
            [Tables\Actions\DeleteBulkAction::make()] : []);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return Auth::user()->is_admin;
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->is_admin;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()->is_admin;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlants::route('/'),
            'create' => Pages\CreatePlant::route('/create'),
            'edit' => Pages\EditPlant::route('/{record}/edit'),
            'view' => Pages\ViewPlant::route('/{record}/view'),
        ];
    }
}