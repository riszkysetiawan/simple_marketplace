<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Transaction $transaction,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $icons = [
            'pending' => 'heroicon-o-clock',
            'paid' => 'heroicon-o-check-circle',
            'processing' => 'heroicon-o-cog',
            'shipped' => 'heroicon-o-truck',
            'completed' => 'heroicon-o-check-badge',
            'cancelled' => 'heroicon-o-x-circle',
        ];

        return [
            'title' => '📦 Order Status Updated',
            'body' => "Order #{$this->transaction->order_number} status changed from {$this->oldStatus} to {$this->newStatus}",
            'transaction_id' => $this->transaction->id,
            'order_number' => $this->transaction->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'icon' => $icons[$this->newStatus] ?? 'heroicon-o-bell',
            'icon_color' => match ($this->newStatus) {
                'completed' => 'success',
                'cancelled' => 'danger',
                'shipped' => 'info',
                default => 'warning'
            },
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
