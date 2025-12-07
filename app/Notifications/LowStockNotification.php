<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Product $product
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Low Stock Alert: ' . $this->product->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is an alert that one of your products is running low on stock.')
            ->line('**Product Details:**')
            ->line('• Name: **' . $this->product->name . '**')
            ->line('• SKU: ' . $this->product->sku)
            ->line('• Current Stock: **' . $this->product->stock . ' units**')
            ->line('• Price: Rp ' . number_format($this->product->price, 0, ',', '.'))
            ->action('View Product', url('/admin/products/' . $this->product->id . '/edit'))
            ->line('Please restock this product as soon as possible.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => '⚠️ Low Stock Alert',
            'body' => "Product '{$this->product->name}' is running low on stock ({$this->product->stock} remaining)",
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'stock' => $this->product->stock,
        ];
    }
}
