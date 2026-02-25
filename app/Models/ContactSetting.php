<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'address',
        'email',
        'phone',
        'instagram_url',
        'facebook_url',
        'twitter_url',
        'youtube_url',
        'maps_url',
    ];

    /**
     * Get the first record or create default
     */
    public static function getSettings()
    {
        return self::first() ?: self::create([
            'address' => 'Jl. Gegerkalong Hilir, Ciwaruga, Kec. Parongpong, Kabupaten Bandung Barat, Jawa Barat 40559',
            'email' => 'kemahasiswaan@polban.ac.id',
            'phone' => '(022) 2013789',
            'instagram_url' => null,
            'facebook_url' => null,
            'twitter_url' => null,
            'youtube_url' => null,
            'maps_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15845.548232479201!2d107.57551068715822!3d-6.843653199999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6900f023f03%3A0xc3b5cb4e315e9a44!2sPoliteknik%20Negeri%20Bandung!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid',
        ]);
    }
}
