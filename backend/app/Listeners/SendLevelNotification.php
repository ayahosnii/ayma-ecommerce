<?php

namespace App\Listeners;

use App\Events\LevelCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class SendLevelNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\LevelCreated  $event
     * @return void
     */
    public function handle(LevelCreated $event)
    {
        Redis::publish('level-created', json_encode($event->level));
    }
}
