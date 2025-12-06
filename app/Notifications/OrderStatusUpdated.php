<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $transaction;
    public $oldStatus;

    public function __construct(Transaction $transaction, $oldStatus)
    {
        $this->transaction = $transaction->load(['items.product', 'user']);
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Status Updated - ' . $this->transaction->order_number)
            ->view('emails.order-status-updated', [
                'transaction' => $this->transaction,
                'oldStatus' => $this->oldStatus
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'transaction_id' => $this->transaction->id,
            'order_number' => $this->transaction->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->transaction->status,
            'message' => "Order {$this->transaction->order_number} status changed from {$this->oldStatus} to {$this->transaction->status}",
        ];
    }
}
