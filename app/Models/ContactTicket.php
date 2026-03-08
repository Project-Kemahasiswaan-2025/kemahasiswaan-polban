<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactTicket extends Model
{
    const STATUS_ISSUED = 'issued';
    const STATUS_FOLLOW_UP = 'follow_up';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'ticket_code',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_note',
    ];

    /**
     * Generate a unique ticket code: {PREFIX}YYYYMMDDXXXX
     */
    public static function generateTicketCode()
    {
        $prefix = config('contact.ticket_prefix', 'ISS');
        $date = now()->format('Ymd');

        $lastTicket = self::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastTicket) {
            $lastCode = $lastTicket->ticket_code;
            $lastPart = str_replace($prefix, '', $lastCode); // YYYYMMDDXXXX
            $lastSeq = substr($lastPart, 8);
            $sequence = (int)$lastSeq + 1;
        }

        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getWhatsAppLinkAttribute()
    {
        if (!$this->phone) return null;

        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $message = "Halo {$this->name}, ini dari Kemahasiswaan Polban terkai tiket #{$this->ticket_code} ({$this->subject}).";
        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    public function getMailtoLinkAttribute()
    {
        $subject = "Follow Up Tiket #{$this->ticket_code}: {$this->subject}";
        $body = "Halo {$this->name},\n\nKami menghubungi Anda terkait tiket bantuan nomor #{$this->ticket_code}.";
        return "mailto:{$this->email}?subject=" . urlencode($subject) . "&body=" . urlencode($body);
    }
}
