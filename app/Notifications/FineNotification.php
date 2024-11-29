<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FineNotification extends Notification
{
    use Queueable;

    protected $fine;

    public function __construct($fine)
    {
        $this->fine = $fine;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $fineTypes = [
            'late' => 'keterlambatan',
            'broken' => 'kerusakan',
            'lost' => 'kehilangan'
        ];

        $fineType = $fineTypes[$this->fine->fine_type] ?? $this->fine->fine_type;

        return [
            'message' => "Anda dikenakan denda Rp " . number_format($this->fine->amount, 0, ',', '.') . " untuk {$fineType} buku {$this->fine->borrow->book->judul}.",
            'fine_id' => $this->fine->id,
            'amount' => $this->fine->amount,
            'fine_type' => $this->fine->fine_type,
            'type' => 'fine'
        ];
    }
}
