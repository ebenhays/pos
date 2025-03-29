<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Users';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Users';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Users Information')
                    ->icon('heroicon-o-users')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Fullname'),
                        TextInput::make('password')
                            ->password()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->maxLength(255)
                            ->label('Set Password')
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state)),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Select::make('role')
                            ->relationship('roles', 'name')
                            ->options(Role::all()->pluck('name', 'id'))
                            ->searchable()
                            ->label('Select Role')
                            ->required(),
                        CheckboxList::make('permissions')
                            ->relationship('permissions', 'name')
                            ->options(Permission::all()->pluck('name', 'id'))
                            ->columnSpanFull()
                            ->gridDirection('row')
                            ->searchable()
                            ->bulkToggleable()
                            ->label('Select Permissions')
                            ->columns(4)
                            ->required(),

                        Toggle::make('disabled')
                            ->label('Disable User')



                    ])->columns(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('disabled')
                    ->label('Status')
                    ->colors([
                        'danger' => 'true',
                        'success' => 'false',
                    ])
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Disabled' : 'Active';
                    })
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view users');
    }
}
