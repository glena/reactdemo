<?php
/**
 * Created by PhpStorm.
 * UserConnection: german
 * Date: 7/20/14
 * Time: 12:00 PM
 */

namespace Glena\Realtimeserver\Implementations;


use Glena\Realtimeserver\Definitions\UserConnectionInterface;

abstract class UserStatus {

    protected $user;

    public function __construct(UserConnectionInterface $user)
    {
        $this->user = $user;
    }

    public function register($data)
    {
        $this->user->setName($data->name);
        $this->user->setStatus(new UserStatusRegistered($this->user));
    }

    public function send($data)
    {
        $this->user->getSocket()->send(json_encode($data));
    }

    public function isRegistered()
    {
        return false;
    }

} 