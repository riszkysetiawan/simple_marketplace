<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Product $product
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => '⚠️ Low Stock Alert',
            'body' => "Product '{$this->product->name}' is running low on stock ({$this->product->stock} remaining)",
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'stock' => $this->product->stock,
            'icon' => 'heroicon-o-exclamation-triangle',
            'icon_color' => 'warning',
            'actions' => [
                [
                    'label' => 'View Product',
                    'url' => route('filament.admin.resources.products.edit', ['record' => $this->product->id]),
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
