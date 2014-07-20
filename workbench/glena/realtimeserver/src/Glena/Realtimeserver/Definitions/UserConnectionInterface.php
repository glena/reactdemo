<?php
/**
 * Created by PhpStorm.
 * UserConnection: german
 * Date: 7/20/14
 * Time: 10:56 AM
 */

namespace Glena\Realtimeserver\Definitions;

use Glena\Realtimeserver\Implementations\UserStatus;
use Ratchet\ConnectionInterface;

interface UserConnectionInterface {

    public function getSocket();
    public function setSocket(ConnectionInterface $socket);
    public function getId();
    public function setId($id);
    public function getName();
    public function setName($name);
    public function setStatus(UserStatus $status);

    public function register($data);
    public function send($data);
    public function isRegistered();
}