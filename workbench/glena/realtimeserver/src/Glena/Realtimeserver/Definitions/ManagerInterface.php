<?php
/**
 * Created by PhpStorm.
 * UserConnection: german
 * Date: 7/20/14
 * Time: 10:41 AM
 */

namespace Glena\Realtimeserver\Definitions;

use Evenement\EventEmitterInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

interface ManagerInterface extends MessageComponentInterface
{
    public function getUserBySocket(ConnectionInterface $socket);
    public function getUsers();
    public function getEmitter();
    public function setEmitter(EventEmitterInterface $emitter);
}