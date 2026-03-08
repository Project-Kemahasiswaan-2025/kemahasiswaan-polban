<?php

namespace App\Filament\Resources\Competitions;

use App\Filament\Resources\Competitions\Pages as ResourcePages;
use App\Filament\Resources\Competitions\Schemas as ResourceSchemas;
use App\Filament\Resources\Competitions\Tables as ResourceTables;
use App\Models\Competition;
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

class CompetitionResource extends Resource
{
    protected static ?string $model = Competition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.resources.competition.nav_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.competition.nav_label');
    }

    protected static ?int $navigationSort = 40;

    public static function getLabel(): string
    {
        return __('filament.resources.competition.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.resources.competition.plural_label');
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ResourceSchemas\CompetitionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ResourceSchemas\CompetitionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResourceTables\CompetitionsTable::configure($table)
            ->modifyQueryUsing(function ($query) {
                $request = request();

                $wheres = collect($query->getQuery()->wheres);
                $isSpecificRecord = $wheres->contains(function ($where) {
                    $column = $where['column'] ?? '';
                    return in_array($column, ['id', 'competitions.id']) ||
                        (isset($where['type']) && in_array($where['type'], ['In', 'InRaw']) && in_array($column, ['id', 'competitions.id']));
                });

                if ($isSpecificRecord) {
                    return;
                }

                $parentId = $request->query('parent_id');

                if (!filled($parentId) && $request->isMethod('post')) {
                    $referer = $request->header('referer');
                    if ($referer) {
                        $urlQuery = parse_url($referer, PHP_URL_QUERY);
                        if ($urlQuery) {
                            parse_str($urlQuery, $queryParams);
                            $parentId = $queryParams['parent_id'] ?? null;
                        }
                    }
                }

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
            'index'  => ResourcePages\ListCompetitions::route('/'),
            'create' => ResourcePages\CreateCompetition::route('/create'),
            'view'   => ResourcePages\ViewCompetition::route('/{record}'),
            'edit'   => ResourcePages\EditCompetition::route('/{record}/edit'),
        ];
    }
}
