<?php

namespace App\Listeners;

use App\Events\BookCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogBookCreation
{
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
    public function handle(BookCreated $event): void
    {
        Log::info('Book created:', [
            'id' => $event->book->id,
            'title' => $event->book->title,
            'user_id' => $event->book->user_id
        ]);
    }
}
