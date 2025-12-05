<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Transaction $transaction
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => '🛒 New Order Received',
            'body' => "New order #{$this->transaction->order_number} from {$this->transaction->user->name} (Rp " . number_format($this->transaction->total_amount, 0, ',', '.') . ")",
            'transaction_id' => $this->transaction->id,
            'order_number' => $this->transaction->order_number,
            'customer_name' => $this->transaction->user->name,
            'total_amount' => $this->transaction->total_amount,
            'icon' => 'heroicon-o-shopping-cart',
            'icon_color' => 'success',
            'actions' => [
                [
                    'label' => 'View Order',
                    'url' => route('filament.admin.resources.transactions.view', ['record' => $this->transaction->id]),
                ],
            ],
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
