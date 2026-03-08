<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactTicket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        $contactSettings = \App\Models\ContactSetting::getSettings();
        return view('pages.contact.index', compact('contactSettings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'g-recaptcha-response' => 'nullable|string',
        ]);

        // Optional Recaptcha Validation
        $secretKey = config('contact.recaptcha.secret_key');
        if ($secretKey) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            if (!$response->successful() || !$response->json('success')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verifikasi reCAPTCHA gagal. Silakan muat ulang halaman.',
                ], 422);
            }
        }

        try {
            $ticket = ContactTicket::create([
                'ticket_code' => ContactTicket::generateTicketCode(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => ContactTicket::STATUS_ISSUED,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda telah berhasil dikirim. Nomor tiket Anda: ' . $ticket->ticket_code,
                'ticket_code' => $ticket->ticket_code,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save contact ticket: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan. Silakan coba lagi nanti.',
            ], 500);
        }
    }
}
