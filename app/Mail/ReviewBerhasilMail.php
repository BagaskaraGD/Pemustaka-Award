<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewBerhasilMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dataReview; // <-- BUAT PROPERTY PUBLIC UNTUK MENYIMPAN DATA

    /**
     * Create a new message instance.
     *
     * @param array $dataReview Data yang dikirim dari controller
     */
    public function __construct(array $dataReview) // <-- UBAH INI: Terima data
    {
        $this->dataReview = $dataReview; // <-- UBAH INI: Simpan data ke property
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notifikasi: Review Buku Baru Telah Ditambahkan', // <-- UBAH INI (Subject lebih baik)
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.review-berhasil', // <-- UBAH INI: Gunakan view yang benar
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
