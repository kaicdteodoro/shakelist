<?php

namespace App\Jobs;

use App\Services\QueueService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePlaylist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $queue_id;
    private QueueService $service;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $queue_id)
    {
        $this->queue_id = $queue_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $success = QueueService::generatePlaylist($this->queue_id);
    }
}
