<?php

namespace Corbinjurgens\QForm;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

use Corbinjurgens\QForm\Components\Error;
use Corbinjurgens\QForm\Components\Input;
use Corbinjurgens\QForm\Components\Submit;
use Corbinjurgens\QForm\Components\Form;

use Illuminate\Support\Facades\Blade;

use Corbinjurgens\QForm\QForm;

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
	   
      Blade::directive('QFormTemplate', function ($template) {
        $class = QForm::class;
        return "<?php $class::setGlobalTemplate($template); ?>";
      });

      Blade::directive('QFormData', function ($data) {
        $class = QForm::class;
        return "<?php $class::data($data); ?>";
      });

      Blade::directive('QFormPrefix', function ($prefix = 'null') {
        $class = QForm::class;
        return "<?php $class::prefix($prefix); ?>";
      });

      Blade::directive('QFormPrefixIn', function ($prefix) {
        $class = QForm::class;
        return "<?php $class::prefixIn($prefix); ?>";
      });

      Blade::directive('QFormPrefixOut', function () {
        $class = QForm::class;
        return "<?php $class::prefixOut(); ?>";
      });
	 
		
    }
}
