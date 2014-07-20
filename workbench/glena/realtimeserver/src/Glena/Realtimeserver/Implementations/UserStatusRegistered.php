<?php
/**
 * Created by PhpStorm.
 * UserConnection: german
 * Date: 7/20/14
 * Time: 12:04 PM
 */

namespace Glena\Realtimeserver\Implementations;

class UserStatusRegistered extends UserStatus{

    /**
     * Registered users can't re-register
     * @param $data
     * @throws \Exception
     */
    public function register($data){
        throw new \Exception('Invalid status');
    }

    public function isRegistered()
    {
        return true;
    }

} 