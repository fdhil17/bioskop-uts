<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWelcomeNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $memberName;
    protected $memberEmail;

    /**
     * Create a new job instance.
     */
    public function __construct($memberName, $memberEmail)
    {
        $this->memberName = $memberName;
        $this->memberEmail = $memberEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simulasi proses background: kirim notifikasi welcome ke member baru
        // Job ini dijalankan secara async lewat Redis queue, terpisah dari
        // request HTTP utama, jadi response API tidak menunggu proses ini selesai.

        sleep(2); // simulasi delay proses (misal call ke email service)

        Log::info("Welcome notification sent to {$this->memberName} ({$this->memberEmail}) via Redis Queue Job");
    }
}