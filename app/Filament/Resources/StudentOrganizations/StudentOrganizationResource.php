<?php

namespace App\Filament\Resources\StudentOrganizations;

use App\Filament\Resources\StudentOrganizations\Pages as ResourcePages;
use App\Filament\Resources\StudentOrganizations\Schemas as ResourceSchemas;
use App\Filament\Resources\StudentOrganizations\Tables as ResourceTables;
use App\Models\StudentOrganization;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Str;

class StudentOrganizationResource extends Resource
{
    protected static ?string $model = StudentOrganization::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return __('menu.nav_label_student_organizations');
    }

    protected static ?int $navigationSort = 30;

    public static function getLabel(): string
    {
        return 'Ormawa';
    }

    public static function getPluralLabel(): string
    {
        return 'Ormawa';
    }

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $recordUrlAttribute = 'slug';

    public static function form(Schema $schema): Schema
    {
        return ResourceSchemas\StudentOrganizationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ResourceSchemas\StudentOrganizationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResourceTables\StudentOrganizationsTable::configure($table)
            ->modifyQueryUsing(function ($query) {
                $request = request();

                // 1. If the query is already targeting a specific record (e.g. for an Action),
                // we MUST skip our scoping to avoid "Record not found" errors.
                $wheres = collect($query->getQuery()->wheres);
                $isSpecificRecord = $wheres->contains(function ($where) {
                    $column = $where['column'] ?? '';
                    return in_array($column, ['id', 'student_organizations.id']) ||
                        (isset($where['type']) && in_array($where['type'], ['In', 'InRaw']) && in_array($column, ['id', 'student_organizations.id']));
                });

                if ($isSpecificRecord) {
                    return;
                }

                // 2. Robust parentId detection from all possible Livewire & URL vectors
                $parentId = $request->query('parent_id')
                    ?? data_get($request->query('tableFilters', []), 'parent_id.value')
                    ?? data_get($request->query('tableFilters', []), 'parent_id_state.value')
                    ?? data_get($request->all(), 'tableFilters.parent_id_state.value')
                    ?? data_get($request->all(), 'tableFilters.parent_id.value')
                    ?? data_get($request->all(), 'components.0.snapshot.memo.data.tableFilters.parent_id_state.value')
                    ?? data_get($request->all(), 'components.0.snapshot.memo.data.tableFilters.parent_id.value')
                    ?? data_get($request->all(), 'components.0.updates.tableFilters.parent_id_state.value')
                    ?? data_get($request->all(), 'components.0.updates.tableFilters.parent_id.value')
                    ?? data_get($request->all(), 'components.0.calls.0.params.0.tableFilters.parent_id_state.value')
                    ?? data_get($request->all(), 'components.0.calls.0.params.0.tableFilters.parent_id.value')
                    ?? data_get($request->all(), 'components.0.snapshot.memo.data.record');

                // Fail-safe: Try parsing Referer if it's a Livewire update
                if (!filled($parentId) && $request->isMethod('post')) {
                    $referer = $request->header('referer');
                    if ($referer) {
                        $urlQuery = parse_url($referer, PHP_URL_QUERY);
                        if ($urlQuery) {
                            parse_str($urlQuery, $queryParams);
                            $parentId = $queryParams['parent_id']
                                ?? data_get($queryParams, 'tableFilters.parent_id.value')
                                ?? data_get($queryParams, 'tableFilters.parent_id_state.value');
                        }
                    }
                }

                // 3. Strict Scoping
                if (filled($parentId)) {
                    $query->where('parent_id', (int) $parentId);
                } else {
                    $query->whereNull('parent_id');
                }
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ResourcePages\ListStudentOrganizations::route('/'),
            'create' => ResourcePages\CreateStudentOrganization::route('/create'),
            'view' => ResourcePages\ViewStudentOrganization::route('/{record}'),
            'edit' => ResourcePages\EditStudentOrganization::route('/{record}/edit'),
        ];
    }
}
