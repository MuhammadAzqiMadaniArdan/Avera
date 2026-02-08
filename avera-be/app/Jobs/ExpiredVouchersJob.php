<?php

namespace App\Jobs;

use App\Modules\Voucher\Models\UserVoucher;
use App\Modules\Voucher\Services\UserVoucherService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExpiredVouchersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(UserVoucherService $userVoucherService): void
    {
        $userVoucherService->expiredVoucher();
    }
}
