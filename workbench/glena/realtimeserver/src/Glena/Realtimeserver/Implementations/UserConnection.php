<?php
/**
 * Created by PhpStorm.
 * UserConnection: german
 * Date: 7/20/14
 * Time: 10:55 AM
 */

namespace Glena\Realtimeserver\Implementations;

use Glena\Realtimeserver\Definitions\UserConnectionInterface;
use Ratchet\ConnectionInterface;

class UserConnection implements UserConnectionInterface
{
    protected $socket;
    protected $id;
    protected $name;

    /**
     * Applies the State Pattern to manage the connection status
     * @var UserStatus
     */
    protected $status;

    public function setStatus(UserStatus $status)
    {
        $this->status = $status;
    }

    public function getSocket()
    {
        return $this->socket;
    }
    public function setSocket(ConnectionInterface $socket)
    {
        $this->socket = $socket;
        return $this;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function register($data)
    {
        $this->status->register($data);
    }

    public function send($data)
    {
        $this->status->send($data);
    }

    public function isRegistered()
    {
        return $this->status->isRegistered();
    }
}