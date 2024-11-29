<?php
// app/Notifications/BookBorrowedNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BookBorrowedNotification extends Notification
{
    use Queueable;

    protected $borrow;

    public function __construct($borrow)
    {
        $this->borrow = $borrow;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Permintaan peminjaman buku {$this->borrow->book->judul} telah {$this->borrow->status}.",
            'borrow_id' => $this->borrow->id,
            'book_title' => $this->borrow->book->judul,
            'status' => $this->borrow->status,
            'type' => 'borrow'
        ];
    }
}