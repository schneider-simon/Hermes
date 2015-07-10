<?php namespace Triggerdesign\Hermes;

use Illuminate\Support\ServiceProvider;

class HermesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
        app()->bind('messaging', function()
        {
            return new \Triggerdesign\Hermes\Models\ConversationManager;
        });

        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('hermes.php'),
            __DIR__.'/../../migrations/' => database_path('/migrations'),

        ]);


        app()->bind('hermes::command.migration', function(){
            return new \MigrationCommand();
        });

        //Use the published migration instead of a command (the documented Laravel 5.1 way)
        //$this->commands(['hermes::command.migration']);

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
