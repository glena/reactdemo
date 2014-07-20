<?php
/**
 * Created by PhpStorm.
 * UserConnection: german
 * Date: 7/20/14
 * Time: 10:37 AM
 */

namespace Glena\Realtimeserver\Implementations;

use Evenement\EventEmitterInterface;
use Exception;
use Glena\Realtimeserver\Definitions\ManagerInterface;
use Glena\Realtimeserver\Definitions\UserConnectionInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

class Manager implements ManagerInterface{

    protected $users;
    protected $emitter;
    protected $id = 1;

    public function __construct(EventEmitterInterface $emitter)
    {
        $this->emitter = $emitter;
        $this->users   = new SplObjectStorage();
    }

    public function getEmitter()
    {
        return $this->emitter;
    }

    public function setEmitter(EventEmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * Returns the user related to the socket
     * @param ConnectionInterface $socket
     * @return UserConnectionInterface
     */
    public function getUserBySocket(ConnectionInterface $socket)
    {
        foreach ($this->users as $next) {
            if ($next->getSocket() === $socket) {
                return $next;
            }
        }
        return null;
    }

    /**
     * Returns the connected user array filtered.
     * Excludes the unregistered users and all that are not the one passed by param
     * @param UserConnectionInterface[] $userFiltered
     * @return UserConnectionInterface[]
     */
    public function filterUsers($userFiltered)
    {
        $array = [];
        foreach ($this->users as $user)
        {
            if ($user->getId() != $userFiltered->getId() && $user->isRegistered())
            {
                $array[] = $user;
            }
        }
        return $array;
    }

    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Connection event.
     * Creates the user and attach to the users array.
     * @param ConnectionInterface $socket
     */
    public function onOpen(ConnectionInterface $socket)
    {
        $user = new UserConnection();
        $user->setStatus(new UserStatusNew($user));
        $user->setId($this->id++);
        $user->setSocket($socket);

        $this->users->attach($user);

        $this->emitter->emit("open", [$user]);
    }

    /**
     * Message received event. Validates if it is a user registration or a new message.
     * @param ConnectionInterface $socket
     * @param string $message Frame received. It should be a json
     */
    public function onMessage(ConnectionInterface $socket,$message)
    {
        //Get the user who send it
        $user = $this->getUserBySocket($socket);
        //Get the rest of the users
        $users = $this->filterUsers($user);

        $message = json_decode($message);

        switch ($message->action)
        {
            case 'register':
                //update the user
                $user->register($message);
                //notify a new registration to registered users
                $this->notifyConnection([$user], $users);
                //sends the complete user list to the new user
                $this->notifyConnection($users,[$user]);
                break;
            case 'message':
                //resend the message to all the users
                $this->notifyMessage($user, $message->message, $users);
                break;
        }

        $this->emitter->emit("message", [$user, $message]);
    }

    /**
     * Creates the frame to me sent for new messages
     * @param UserConnectionInterface $originUser
     * @param $data Message
     * @param UserConnectionInterface[] $users
     */
    public function notifyMessage(UserConnectionInterface $originUser, $data, $users)
    {
        $message = [
            'action' => 'message',
            'user' => [
                'id' => $originUser->getId(),
                'name' => $originUser->getName()
            ],
            'message' => $data
        ];
        $this->notify($message, $users);
    }

    /**
     * Creates the frame to me sent for new connections
     * @param UserConnectionInterface[] $connectedUsers
     * @param UserConnectionInterface[] $users
     */
    public function notifyConnection($connectedUsers, $users)
    {
        $message = [
            'action' => 'connection',
            'users' => []
        ];

        foreach ($connectedUsers as $user)
        {
            $message['users'][] = [
                'name' => $user->getName(),
                'id' => $user->getId()
            ];
        }

        $this->notify($message, $users);
    }

    /**
     * Creates the frame to me sent for disconnections
     * @param UserConnectionInterface $user
     * @param UserConnectionInterface[] $users
     */
    public function notifyDisconnection(UserConnectionInterface $user, $users)
    {
        $message = [
            'action' => 'disconnection',
            'user' => [
                'name' => $user->getName(),
                'id' => $user->getId()
            ]
        ];
        $this->notify($message, $users);
    }

    /**
     * Send the frame
     * @param $message
     * @param UserConnectionInterface[] $users
     */
    public function notify($message, $users)
    {
        foreach ($users as $user)
        {
            try{
                $user->send($message);
                $this->emitter->emit("send", [$user, $message]);
            }
            catch (\Exception $exception)
            {
                $this->emitter->emit("error", [$user, $exception]);
            }
        }
    }

    /**
     * Disconnection event.
     * Removes the user from the array and notifies users
     * @param ConnectionInterface $socket
     */
    public function onClose(ConnectionInterface $socket)
    {
        $user = $this->getUserBySocket($socket);

        $this->emitter->emit("close", [$user]);

        if ($user) {
            $this->users->detach($user);

            $this->notifyDisconnection($user, $this->users);
        }
    }

    /**
     * Error event
     * @param ConnectionInterface $socket
     * @param Exception $exception
     */
    public function onError(ConnectionInterface $socket,Exception $exception)
    {
        $user = $this->getUserBySocket($socket);

        $this->emitter->emit("error", [$user, $exception]);

        if ($user) {
            $user->getSocket()->close();
        }
    }

} 