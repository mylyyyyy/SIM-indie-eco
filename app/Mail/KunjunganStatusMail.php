<?php

namespace App\Mail;

use App\Models\Kunjungan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KunjunganStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $kunjungan;

    public function __construct(Kunjungan $kunjungan)
    {
        $this->kunjungan = $kunjungan;
    }

    public function envelope(): Envelope
    {
        $subject = $this->kunjungan->status === 'approved'
            ? '✅ Pendaftaran Kunjungan Disetujui - Lapas Kelas IIB Jombang'
            : '❌ Pendaftaran Kunjungan Ditolak - Lapas Kelas IIB Jombang';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        // Pastikan file view ada di resources/views/emails/kunjungan-status.blade.php
        return new Content(
            view: 'emails.kunjungan-status',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
