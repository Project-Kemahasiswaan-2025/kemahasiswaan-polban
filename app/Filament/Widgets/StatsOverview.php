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
            Stat::make('Total Organisasi', StudentOrganization::where('is_group', false)->count())
                ->description('Organisasi & Unit Kegiatan')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('success'),
            Stat::make('Total Unduhan', Download::whereNotNull('category_id')->count())
                ->description('Dokumen di Pusat Unduhan')
                ->descriptionIcon('heroicon-m-document-arrow-down')
                ->color('info'),
            Stat::make('Layanan Aktif', Service::where('is_active', true)->where('parent_id', null)->count())
                ->description('Layanan Kemahasiswaan')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('warning'),
            Stat::make('Tiket Baru', ContactTicket::where('status', 'issued')->count())
                ->description('Perlu tindak lanjut')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('danger'),
        ];
    }
}
