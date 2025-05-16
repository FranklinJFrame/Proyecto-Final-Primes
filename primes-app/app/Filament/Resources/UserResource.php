<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers\PedidosRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        // Panel Principal - Información Personal
                        Forms\Components\Section::make('Información Personal')
                            ->description('Datos principales del usuario')
                            ->icon('heroicon-o-user')
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->placeholder('Nombre completo')
                                    ->maxLength(255)
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('ejemplo@correo.com')
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\DateTimePicker::make('email_verified_at')
                                    ->label('Verificación de Email')
                                    ->default(now())
                                    ->displayFormat('d/m/Y H:i')
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\TextInput::make('password')
                                    ->label('Contraseña')
                                    ->password()
                                    ->dehydrated(fn($state) => filled($state))
                                    ->required(fn ($livewire): bool => $livewire instanceof Pages\CreateUser)
                                    ->minLength(8)
                                    ->same('passwordConfirmation')
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\TextInput::make('passwordConfirmation')
                                    ->label('Confirmar Contraseña')
                                    ->password()
                                    ->dehydrated(false)
                                    ->required(fn ($livewire): bool => $livewire instanceof Pages\CreateUser)
                                    ->minLength(8)
                                    ->columnSpan(['md' => 2]),
                            ])
                            ->columns(['md' => 4])
                            ->columnSpan(['lg' => 2]),

                        // Panel Lateral - Estadísticas
                        Forms\Components\Section::make('Estadísticas')
                            ->description('Información general de la cuenta')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Fecha de Registro')
                                    ->content(fn (?User $record): string => $record ? $record->created_at->format('d/m/Y H:i') : '-'),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Última Actualización')
                                    ->content(fn (?User $record): string => $record ? $record->updated_at->format('d/m/Y H:i') : '-'),

                                Forms\Components\Placeholder::make('pedidos_count')
                                    ->label('Total de Pedidos')
                                    ->content(fn (?User $record): string => $record ? $record->pedidos()->count() : '0'),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(['lg' => 3]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verificado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('pedidos_count')
                    ->label('Pedidos')
                    ->counts('pedidos')
                    ->sortable()
                    ->alignCenter()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Verificación')
                    ->placeholder('Todos los usuarios')
                    ->trueLabel('Usuarios verificados')
                    ->falseLabel('Usuarios no verificados')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('email_verified_at'),
                        false: fn (Builder $query) => $query->whereNull('email_verified_at'),
                    ),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('desde')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.'),
                ])
                ->link()
                ->label('Acciones'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('¿Estás seguro de que deseas eliminar los usuarios seleccionados? Esta acción no se puede deshacer.'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear Usuario'),
            ])
            ->emptyStateDescription('No hay usuarios registrados aún. ¡Comienza creando uno!');
    }

    public static function getRelations(): array
    {
        return [
            PedidosRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes(): array
{
    return ['name', 'email'];
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
