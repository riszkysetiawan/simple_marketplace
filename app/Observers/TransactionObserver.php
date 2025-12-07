<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Notifications\OrderStatusChangedNotification;
use App\Notifications\OrderStatusUpdated;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        // ✅ Kirim notifikasi ke semua super admin saat order baru dibuat
        $superAdmins = User::whereHas('roles', function ($query) {
            $query->where('name', 'super_admin');
        })->get();

        foreach ($superAdmins as $admin) {
            $admin->notify(new NewOrderNotification($transaction));
        }
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // ✅ Cek apakah status berubah
        if ($transaction->isDirty('status')) {
            $oldStatus = $transaction->getOriginal('status');
            $newStatus = $transaction->status;

            // 1. Kirim notifikasi ke admin
            $superAdmins = User::whereHas('roles', function ($query) {
                $query->where('name', 'super_admin');
            })->get();

            foreach ($superAdmins as $admin) {
                $admin->notify(new OrderStatusChangedNotification(
                    $transaction,
                    $oldStatus,
                    $newStatus
                ));
            }

            // 2. Kirim email ke customer
            if ($transaction->user) {
                $transaction->user->notify(new OrderStatusUpdated(
                    $transaction,
                    $oldStatus
                ));
            }
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
