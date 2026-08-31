<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotSetting extends Model
{
    protected $fillable = [
        'welcome_message',
    ];

    /**
     * Get or create default chatbot setting instance.
     */
    public static function getSettings(): self
    {
        return self::first() ?: self::create([
            'welcome_message' => "Halo! Selamat datang di Pusat Layanan Kemahasiswaan POLBAN. Ada yang bisa kami bantu? Silakan pilih topik di bawah ini:",
        ]);
    }

    /**
     * Get a random welcome message variation.
     */
    public static function getRandomWelcomeMessage(): string
    {
        $setting = self::getSettings();
        $raw = $setting->welcome_message;

        if (empty($raw)) {
            return 'Halo! Selamat datang di Pusat Layanan Kemahasiswaan POLBAN. Ada yang bisa kami bantu? Silakan pilih topik di bawah ini:';
        }

        $parts = preg_split('/\n?\s*---\s*\n?/', $raw);
        $clean = array_values(array_filter(array_map('trim', $parts), fn($v) => $v !== ''));

        if (empty($clean)) {
            return $raw;
        }

        return $clean[array_rand($clean)];
    }

    /**
     * Get all welcome message variations as array.
     */
    public static function getWelcomeVariations(): array
    {
        $setting = self::getSettings();
        $raw = $setting->welcome_message;

        if (empty($raw)) {
            return ['Halo! Selamat datang di Pusat Layanan Kemahasiswaan POLBAN. Ada yang bisa kami bantu? Silakan pilih topik di bawah ini:'];
        }

        $parts = preg_split('/\n?\s*---\s*\n?/', $raw);
        $clean = array_values(array_filter(array_map('trim', $parts), fn($v) => $v !== ''));

        return empty($clean) ? [$raw] : $clean;
    }
}
