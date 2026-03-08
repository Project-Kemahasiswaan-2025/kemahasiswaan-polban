<?php

namespace App\Filament\Widgets;

use App\Models\ContactTicket;
use App\Models\Download;
use App\Models\Service;
use App\Models\StudentOrganization;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make(__('filament.widgets.stats.total_org'), StudentOrganization::where('is_group', false)->count())
                ->description(__('filament.widgets.stats.org_description'))
                ->descriptionIcon('heroicon-m-building-library')
                ->color('success'),
            Stat::make(__('filament.widgets.stats.total_downloads'), Download::whereNotNull('category_id')->count())
                ->description(__('filament.widgets.stats.downloads_description'))
                ->descriptionIcon('heroicon-m-document-arrow-down')
                ->color('info'),
            Stat::make(__('filament.widgets.stats.active_services'), Service::where('is_active', true)->where('parent_id', null)->count())
                ->description(__('filament.widgets.stats.services_description'))
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('warning'),
            Stat::make(__('filament.widgets.stats.new_tickets'), ContactTicket::where('status', 'issued')->count())
                ->description(__('filament.widgets.stats.tickets_description'))
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('danger'),
        ];
    }
}
