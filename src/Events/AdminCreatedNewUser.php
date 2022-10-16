<?php

namespace AttractCores\LaravelCoreKit\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminCreatedNewUser
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Authorizable
     */
    public Authorizable $user;

    /**
     * @var string
     */
    public string $password;

    /**
     * Create a new event instance.
     *
     * @param Authorizable $user
     * @param                 $password
     */
    public function __construct(Authorizable $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

}
