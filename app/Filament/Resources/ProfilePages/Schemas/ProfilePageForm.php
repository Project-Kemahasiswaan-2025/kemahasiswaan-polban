<?php

namespace App\Filament\Resources\ProfilePages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProfilePageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi')
                ->schema([
                    Hidden::make('section')
                        ->default('profile')
                        ->dehydrated()
                        ->required(),

                    Select::make('language_id')
                        ->label('Bahasa')
                        ->options(\App\Models\Language::active()->pluck('name', 'id'))
                        ->default(fn() => activeLanguage()?->id)
                        ->native(false)
                        ->nullable()
                        ->columnSpanFull(),

                    Grid::make(12)->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(180)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (filled($get('slug'))) {
                                    return;
                                }
                                $set('slug', Str::slug((string) $state));
                            })
                            ->columnSpan(10),

                        TextInput::make('sort_order')
                            ->label('Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(2),
                    ]),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(200)
                        ->unique(ignoreRecord: true, modifyRuleUsing: fn($rule) => $rule->where('section', 'profile')),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    FileUpload::make('document_path')
                        ->label('Tampilan Dokumen (Gambar / PDF)')
                        ->disk('public')
                        ->directory('pages/docs/profile')
                        ->acceptedFileTypes([
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ])
                        ->maxSize(10240)
                        ->downloadable()
                        ->openable()
                        ->helperText('Opsional. Akan tampil di atas konten.')
                        ->columnSpanFull(),
                ]),

            Section::make('Konten')
                ->schema([
                    RichEditor::make('content')
                        ->label('Konten')
                        ->nullable()
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
