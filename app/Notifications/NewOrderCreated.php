<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewOrderCreated extends Notification
{
    use Queueable;

    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;

        // ✅ Debug log
        Log::info('NewOrderCreated notification created', [
            'order_number' => $transaction->order_number,
            'user_email' => $transaction->user->email
        ]);
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        // ✅ Debug log
        Log::info('Sending email to: ' . $notifiable->email);

        try {
            return (new MailMessage)
                ->subject('New Order Created - ' . $this->transaction->order_number)
                ->view('emails.order-created', ['transaction' => $this->transaction]);
        } catch (\Exception $e) {
            Log::error('Email error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function toArray($notifiable): array
    {
        return [
            'transaction_id' => $this->transaction->id,
            'order_number' => $this->transaction->order_number,
            'total_amount' => $this->transaction->total_amount,
            'message' => 'New order created: ' . $this->transaction->order_number,
        ];
    }
}
