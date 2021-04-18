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
			Form::class,
		]);
		$this->loadViewsFrom(__DIR__.'/resources/views', self::$name);
	   
		$this->publishes([
			__DIR__.'/resources/views' => resource_path('views/vendor/' . self::$name),
		], self::$name . '-views');
	   
	   
	 
		
    }
}
