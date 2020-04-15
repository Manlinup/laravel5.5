<?php

namespace App\Events;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Cache;
use Sak\Core\Traits\CacheKeys;

class UpdateCache
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $repository;

    protected $attributes;

    /**
     * UpdateCache constructor.
     * @param RepositoryInterface $repository
     * @param $attributes
     */
    public function __construct(RepositoryInterface $repository, $attributes)
    {
        $this->repository = $repository;

        $this->attributes = $attributes;
    }

    public function updateCache()
    {
        $cacheKeys = CacheKeys::getKeys(get_class($this->repository));

        if (is_array($cacheKeys)) {
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
