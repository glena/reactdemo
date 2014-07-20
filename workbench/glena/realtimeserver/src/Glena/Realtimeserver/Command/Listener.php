<?php
/**
 * Created by PhpStorm.
 * UserConnection: german
 * Date: 7/20/14
 * Time: 10:36 AM
 */

namespace Glena\Realtimeserver\Command;

use Glena\Realtimeserver\Definitions\ManagerInterface;
use Glena\Realtimeserver\Definitions\UserConnectionInterface;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use \Illuminate\Support\Facades\Config;

class Listener extends Command {
    protected $name        = "rt:listen";
    protected $description = "Starts the real time server! :O.";
    protected $manager;

    /**
     * Logs on stdOutput the events
     * @param ManagerInterface $manager
     */
    public function __construct(ManagerInterface $manager)
    {
        parent::__construct();

        $this->manager = $manager;

        $this->manager->getEmitter()->on("open", function(UserConnectionInterface $user)
        {
            $this->line("
                <info>#{$user->getId()} connected.</info>
            ");
        });

        $this->manager->getEmitter()->on("registered", function(UserConnectionInterface $user)
        {
            $this->line("
                <info>#{$user->getId()} {$user->getName()} registered.</info>
            ");
        });

        $this->manager->getEmitter()->on("close", function(UserConnectionInterface $user)
        {
            $this->line("
                <info>#{$user->getId()} {$user->getName()} disconnected.</info>
            ");
        });

        $this->manager->getEmitter()->on("message", function(UserConnectionInterface $user, $message)
        {
            $message = print_r($message, true);

            $this->line("
                <info>#{$user->getId()} {$user->getName()} sends:</info>
                <comment>$message</comment>
            ");
        });

        $this->manager->getEmitter()->on("send", function(UserConnectionInterface $user, $message)
        {
            $message = print_r($message, true);

            $this->line("
                <info>#{$user->getId()} {$user->getName()} received:</info>
                <comment>$message</comment>
            ");
        });

        $this->manager->getEmitter()->on("error", function($user, \Exception $e)
        {
            $message = $e->getMessage();
            $userdata = "";

            if ($user)
            {
                $userdata = "#{$user->getId()} {$user->getName()}";
            }

            $this->line("
                <info>$userdata ERROR.</info>
                <info>$message</info>
            ");
        });
    }

    /**
     * Initialize the real time server.
     * The port is configured on app.php configuration file
     */
    public function fire()
    {
        $port = (integer) Config::get('app.streams_port');

        /**
         * Initializes the server which manages standard IO connections (SO sockets),
         * wrapped on an HTTPServer wich manages standard HTTP requests,
         * wrapped on WsServer wich manages the Web Sockets connections
         */
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $this->manager
                )
            ),
            $port
        );

        $this->line("
            <info>Listening on port</info>
            <comment>" . $port . "</comment>
            <info>.</info>
        ");

        $server->run();
    }
} 