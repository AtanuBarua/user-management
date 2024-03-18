<?php

namespace App\Listeners;

use App\Events\UserCreatedOrUpdated;
use App\Models\Address;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateOrUpdateUserAddress
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
    public function handle(UserCreatedOrUpdated $event): void
    {
        (new Address())->createOrUpdateAddress($event->user_id, $event->address);
    }
}
