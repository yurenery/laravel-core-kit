<?php

namespace AttractCores\LaravelCoreKit\Events;

use \Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserPasswordChanged
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Authenticatable
     */
    public Authenticatable $user;

    /**
     * Applied password.
     *
     * @var string
     */
    public string $password;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $password
     */
    public function __construct(Authenticatable $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

}
