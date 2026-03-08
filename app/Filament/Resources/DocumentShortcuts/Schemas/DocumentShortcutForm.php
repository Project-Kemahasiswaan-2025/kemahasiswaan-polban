<?php

namespace App\Filament\Resources\DocumentShortcuts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class DocumentShortcutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('menu')
                    ->options([
                        'profile' => 'Dropdown Profil',
                        'services' => 'Dropdown Layanan',
                        'ormawa' => 'Dropdown Ormawa',
                        'achievements' => 'Dropdown Prestasi',
                    ])
                    ->required()
                    ->native(false),
                TextInput::make('title')
                    ->label('Nama Sub Menu')
                    ->required()
                    ->maxLength(255),
                Select::make('category_id')
                    ->label('Kategori Unduhan')
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->where('type', 'download')->active()->orderBy('sort_order')
                    )
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn(Set $set) => $set('download_id', null))
                    ->nullable()
                    ->helperText('Halaman unduhan akan difilter berdasarkan kategori ini. Jika kosong, akan menampilkan seluruhan.'),
                Select::make('download_id')
                    ->label('Pilih Dokumen (Opsional)')
                    ->relationship(
                        name: 'download',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query, Get $get) => $query
                            ->where('is_active', true)
                            ->when($get('category_id'), fn($query, $categoryId) => $query->where('category_id', $categoryId))
                            ->orderBy('sort_order')
                    )
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText('Jika dipilih, link akan langsung menuju ke detail dokumen. Jika kosong, akan menuju list kategori.'),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
