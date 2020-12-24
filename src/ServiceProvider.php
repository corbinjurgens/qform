<?php

namespace Corbinjurgens\QForm;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;



class ServiceProvider extends BaseServiceProvider
{
	
	static $name = 'qform';
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
		/*
         // config
		$this->mergeConfigFrom(
			__DIR__.'/config/x.php', 'x'
		);
		*/
		
		$this->app->bind(self::$name, QForm::class);
		
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
		
        $this->loadViewComponentsAs(self::$name, [
			Error::class,
			Input::class,
			Submit::class,
		]);
	   $this->loadViewsFrom(__DIR__.'/resources/views', self::$name);
		    $this->publishes([
				__DIR__.'/resources/views' => resource_path('views/vendor/' . self::$name),
			], self::$name . '-views');
	   
	   
	   
	   
	   
	   /*
			$this->publishes([
				__DIR__.'/config/x.php' => config_path('x.php'),
			], 'x-config');
		
		// db
		$this->loadMigrationsFrom(__DIR__.'/database/migrations');
			$this->publishes([
				__DIR__.'/database/migrations' => database_path('migrations'),
			], 'x-migrations');
		
		// lang
		$this->loadTranslationsFrom(__DIR__.'/resources/lang', 'x');
			$this->publishes([
				__DIR__.'/resources/lang' => resource_path('lang/vendor/x'),
			], 'x-lang');
		
		// views
		$this->loadViewsFrom(__DIR__.'/resources/views', 'x');
		    $this->publishes([
				__DIR__.'/resources/views' => resource_path('views/vendor/x'),
			], 'x-views');
			
		// commands
		if ($this->app->runningInConsole()) {
			$this->commands([
				MailIncoming::class,
			]);
		}
		*/
		
    }
}
