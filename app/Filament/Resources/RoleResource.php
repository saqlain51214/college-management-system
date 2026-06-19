<?php

namespace App\Filament\Resources;

use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use BezhanSalleh\FilamentShield\Forms\ShieldSelectAllToggle;
use BezhanSalleh\FilamentShield\Resources\RoleResource as BaseRoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class RoleResource extends BaseRoleResource
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('Role Details')
                            ->schema([
                                Forms\Components\Placeholder::make('permission_ui_hint')
                                    ->label('')
                                    ->content(new HtmlString(
                                        '<div class="text-sm leading-6 text-gray-600">' .
                                        'Only <strong>super_admin</strong> should have full system access. ' .
                                        'Use the top toggle for all permissions, or use each module block checkbox to select and deselect that module quickly.' .
                                        '</div>'
                                    ))
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('name')
                                    ->label(__('filament-shield::filament-shield.field.name'))
                                    ->unique(
                                        ignoreRecord: true,
                                        modifyRuleUsing: fn (Unique $rule) => Utils::isTenancyEnabled()
                                            ? $rule->where(Utils::getTenantModelForeignKey(), Filament::getTenant()?->id)
                                            : $rule
                                    )
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('guard_name')
                                    ->label(__('filament-shield::filament-shield.field.guard_name'))
                                    ->default(Utils::getFilamentAuthGuard())
                                    ->nullable()
                                    ->maxLength(255),

                                Forms\Components\Select::make(config('permission.column_names.team_foreign_key'))
                                    ->label(__('filament-shield::filament-shield.field.team'))
                                    ->placeholder(__('filament-shield::filament-shield.field.team.placeholder'))
                                    ->default([Filament::getTenant()?->id])
                                    ->options(fn (): Arrayable => Utils::getTenantModel() ? Utils::getTenantModel()::pluck('name', 'id') : collect())
                                    ->hidden(fn (): bool => ! (static::shield()->isCentralApp() && Utils::isTenancyEnabled()))
                                    ->dehydrated(fn (): bool => ! (static::shield()->isCentralApp() && Utils::isTenancyEnabled())),

                                Forms\Components\Actions::make([
                                    Action::make('selectAllPermissions')
                                        ->label('Select All Permissions')
                                        ->color('primary')
                                        ->action(function ($set): void {
                                            foreach (static::getAllPermissionFieldOptions() as $field => $options) {
                                                $set($field, array_keys($options));
                                            }
                                        }),
                                    Action::make('clearAllPermissions')
                                        ->label('Clear All Permissions')
                                        ->color('gray')
                                        ->action(function ($set): void {
                                            foreach (static::getAllPermissionFieldOptions() as $field => $options) {
                                                $set($field, []);
                                            }
                                        }),
                                ])
                                    ->key('permission-tools')
                                    ->fullWidth()
                                    ->columnSpanFull(),

                            ])
                            ->columns([
                                'sm' => 2,
                                'lg' => 3,
                            ]),
                    ]),
                static::getShieldFormComponents(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->weight('font-medium')
                    ->label('Role')
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('guard_name')
                    ->badge()
                    ->color('warning')
                    ->label(__('filament-shield::filament-shield.column.guard_name')),
                Tables\Columns\TextColumn::make('permissions_count')
                    ->badge()
                    ->label('Permissions')
                    ->counts('permissions')
                    ->colors(['success']),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-shield::filament-shield.column.updated_at'))
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record): bool => $record->name !== config('filament-shield.super_admin.name')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getResourceEntitiesSchema(): ?array
    {
        return collect(FilamentShield::getResources())
            ->sortBy(fn (array $entity): string => static::formatEntityLabel($entity))
            ->map(function (array $entity) {
                return Forms\Components\Section::make(static::formatEntityLabel($entity))
                    ->description(fn (): HtmlString => new HtmlString(
                        '<div class="space-y-1 text-xs">' .
                        '<div class="text-gray-600">Use the list checkbox toggle in this block to select or deselect the full module.</div>' .
                        '<div class="break-all text-gray-400">' . e(Utils::showModelPath($entity['fqcn'])) . '</div>' .
                        '</div>'
                    ))
                    ->compact()
                    ->schema([
                        static::getCheckBoxListComponentForResource($entity),
                    ])
                    ->columnSpan(static::shield()->getSectionColumnSpan())
                    ->collapsible();
            })
            ->toArray();
    }

    public static function getResourcePermissionOptions(array $entity): array
    {
        return collect(Utils::getResourcePermissionPrefixes($entity['fqcn']))
            ->flatMap(function (string $permission) use ($entity): array {
                $name = $permission . '_' . $entity['resource'];

                return [
                    $name => static::formatPermissionLabel($permission),
                ];
            })
            ->toArray();
    }

    public static function getCheckboxListFormComponent(string $name, array $options, bool $searchable = true, array | int | string | null $columns = null, array | int | string | null $columnSpan = null): Component
    {
        return Forms\Components\CheckboxList::make($name)
            ->label('')
            ->options(fn (): array => $options)
            ->searchable($searchable)
            ->helperText('Use the checkbox toggle above this list to select or clear every permission in this block.')
            ->afterStateHydrated(
                fn (Component $component, string $operation, ?Model $record) => static::setPermissionStateForRecordPermissions(
                    component: $component,
                    operation: $operation,
                    permissions: $options,
                    record: $record
                )
            )
            ->dehydrated()
            ->bulkToggleable()
            ->gridDirection('row')
            ->columns($columns ?? static::shield()->getCheckboxListColumns())
            ->columnSpan($columnSpan ?? static::shield()->getCheckboxListColumnSpan());
    }

    public static function getPages(): array
    {
        return [
            'index' => RoleResource\Pages\ListRoles::route('/'),
            'create' => RoleResource\Pages\CreateRole::route('/create'),
            'view' => RoleResource\Pages\ViewRole::route('/{record}'),
            'edit' => RoleResource\Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    public static function getNavigationLabel(): string
    {
        return 'Roles & Permissions';
    }

    public static function getModelLabel(): string
    {
        return 'Role';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Roles';
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return Utils::getSubNavigationPosition() ?? static::$subNavigationPosition;
    }

    protected static function formatEntityLabel(array $entity): string
    {
        return Str::headline($entity['resource']);
    }

    protected static function getAllPermissionFieldOptions(): array
    {
        $resourcePermissions = collect(FilamentShield::getResources())
            ->mapWithKeys(fn (array $entity): array => [
                $entity['resource'] => static::getResourcePermissionOptions($entity),
            ])
            ->toArray();

        return array_merge(
            $resourcePermissions,
            ['pages_tab' => static::getPageOptions()],
            ['widgets_tab' => static::getWidgetOptions()],
            ['custom_permissions' => static::getCustomPermissionOptions() ?? []],
        );
    }

    protected static function formatPermissionLabel(string $permission): string
    {
        return match ($permission) {
            'view_any' => 'View list',
            'view' => 'View details',
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
            'delete_any' => 'Delete any',
            'restore' => 'Restore',
            'restore_any' => 'Restore any',
            'replicate' => 'Duplicate',
            'reorder' => 'Reorder',
            'force_delete' => 'Force delete',
            'force_delete_any' => 'Force delete any',
            default => Str::headline(str_replace('_', ' ', $permission)),
        };
    }
}
