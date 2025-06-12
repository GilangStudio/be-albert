<?php

namespace App\Listeners;

use App\Models\Voucher;
use App\Models\UserVoucher;
use App\Events\UserRegisteredEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GiveNewUserVoucher implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegisteredEvent $event): void
    {
        $user = $event->user;

        // Cari voucher khusus pengguna baru yang aktif
        $voucher = Voucher::whereIn('type', ['NEW_USER', 'GENERAL'])
                        //   ->where('is_active', true)
                          ->first();

                          if ($voucher) {
            // Berikan voucher kepada pengguna baru
            UserVoucher::updateOrCreate(
                ['user_id' => $user->id, 'voucher_id' => $voucher->id],
                ['is_redeemed' => false]
            );
        }
    }
}
