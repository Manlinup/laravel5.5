<?php

namespace App\Listeners;

use App\Events\UpdateCache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateCacheListener
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
     * @param  UpdateCache  $event
     * @return void
     */
    public function handle(UpdateCache $event)
    {
        $event->updateCache();
    }
}
