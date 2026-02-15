<?php

namespace App\Filament\Resources\StudentOrganizations;

use App\Filament\Resources\StudentOrganizations\Pages\CreateStudentOrganization;
use App\Filament\Resources\StudentOrganizations\Pages\EditStudentOrganization;
use App\Filament\Resources\StudentOrganizations\Pages\ListStudentOrganizations;
use App\Filament\Resources\StudentOrganizations\Pages\ViewStudentOrganization;
use App\Models\StudentOrganization;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

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
        return Schemas\StudentOrganizationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return Schemas\StudentOrganizationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return Tables\StudentOrganizationsTable::configure($table)
            ->modifyQueryUsing(function ($query) {
                $parentId = data_get(request()->query('tableFilters', []), 'parent_id.value');

                return $query->when(
                    filled($parentId),
                    fn($q) => $q->where('parent_id', (int) $parentId),
                    fn($q) => $q->whereNull('parent_id'),
                );
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentOrganizations::route('/'),
            'create' => CreateStudentOrganization::route('/create'),
            'view' => ViewStudentOrganization::route('/{record}'),
            'edit' => EditStudentOrganization::route('/{record}/edit'),
        ];
    }
}
