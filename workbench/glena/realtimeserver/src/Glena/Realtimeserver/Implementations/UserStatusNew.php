<?php
/**
 * Created by PhpStorm.
 * UserConnection: german
 * Date: 7/20/14
 * Time: 12:00 PM
 */

namespace Glena\Realtimeserver\Implementations;

class UserStatusNew extends UserStatus {

    /**
     * Unregistered users cant receive messages
     * @param $data
     * @throws \Exception
     */
    public function send($data)
    {
        throw new \Exception('Invalid status');
    }

} 