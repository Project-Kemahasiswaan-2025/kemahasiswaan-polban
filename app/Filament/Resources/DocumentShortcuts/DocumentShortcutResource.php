<?php

namespace App\Filament\Resources\DocumentShortcuts;

use App\Filament\Resources\DocumentShortcuts\Pages\ManageDocumentShortcuts;
use App\Models\DocumentShortcut;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentShortcutResource extends Resource
{
    protected static ?string $model = DocumentShortcut::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group_downloads');
    }

    public static function getNavigationLabel(): string
    {
        return __('menu.nav_label_document_shortcuts');
    }

    protected static ?int $navigationSort = 46;

    public static function getLabel(): string
    {
        return __('menu.nav_label_document_shortcuts');
    }

    public static function getPluralLabel(): string
    {
        return __('menu.nav_label_document_shortcuts');
    }

    public static function form(Schema $schema): Schema
    {
        return Schemas\DocumentShortcutForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return Tables\DocumentShortcutsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDocumentShortcuts::route('/'),
        ];
    }
}
