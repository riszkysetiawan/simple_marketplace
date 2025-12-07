<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;

class ProductObserver
{
    public function updated(Product $product)
    {
        if ($product->isDirty('stock') && $product->stock <= 10 && $product->stock > 0) {
            $this->sendLowStockNotification($product);
        }
    }

    public function created(Product $product)
    {
        if ($product->stock <= 10 && $product->stock > 0) {
            $this->sendLowStockNotification($product);
        }
    }

    private function sendLowStockNotification(Product $product)
    {
        // Cara 1: Menggunakan whereHas
        $superAdmins = User::whereHas('roles', function ($query) {
            $query->where('name', 'super_admin');
        })->get();

        foreach ($superAdmins as $admin) {
            $admin->notify(new LowStockNotification($product));
        }
    }
}
