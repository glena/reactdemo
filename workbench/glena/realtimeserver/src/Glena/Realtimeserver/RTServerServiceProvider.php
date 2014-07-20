<?php

namespace Glena\Realtimeserver;

use Evenement\EventEmitter;
use Glena\Realtimeserver\Implementations\Manager;
use Illuminate\Support\ServiceProvider;

class RTServerServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind("rtserver.emitter", function()
        {
            return new EventEmitter();
        });
        $this->app->bind("rtserver.manager", function()
        {
            return new Manager(
                $this->app->make("rtserver.emitter")
            );
        });
        $this->app->bind("rtserver.command.listener", function()
        {
            return new Command\Listener(
                $this->app->make("rtserver.manager")
            );
        });
        $this->commands("rtserver.command.listener");
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
        return [
            "rtserver.manager",
            "rtserver.command.listener"
        ];
	}

}
