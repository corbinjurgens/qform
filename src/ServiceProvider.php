<?php

namespace Corbinjurgens\QForm;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

use Corbinjurgens\QForm\Components\Error;
use Corbinjurgens\QForm\Components\Input;
use Corbinjurgens\QForm\Components\Submit;
use Corbinjurgens\QForm\Components\Form;

use Illuminate\Support\Facades\Blade;

use Illuminate\Container\Container;
use Illuminate\View\DynamicComponent;

use Corbinjurgens\QForm\QForm;
use Corbinjurgens\QForm\Concerns\BladeCompiler;
use Corbinjurgens\QForm\Concerns\ComponentTagCompiler;

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
      $this->app->singleton('blade.qformcompiler', function ($app){
        return tap(new BladeCompiler($app['files'], $app['config']['view.compiled']), function ($blade) {
            $blade->component('dynamic-component', DynamicComponent::class);
        });
      });

      $this->app->singleton('blade.qformtagcompiler', function ($app){
        if (version_compare($app->version(), '8.0.0') >= 0){
          $res = new ComponentTagCompiler(
            $app->make('blade.compiler')->getClassComponentAliases(),
            $app->make('blade.compiler')->getClassComponentNamespaces(),
            $app->make('blade.compiler')
          );
        }else if (version_compare($app->version(), '7.9.2') >= 0){
          $res = new ComponentTagCompiler(
            $app->make('blade.compiler')->getClassComponentAliases(),
            $app->make('blade.compiler')
          );
        }else if (version_compare($app->version(), '7.9.0') >= 0){
          $res = new ComponentTagCompiler(
            $app->make('blade.compiler'),
            $app->make('blade.compiler')->getClassComponentAliases()
          );
        }else{
          $res = new ComponentTagCompiler(
            $app->make('blade.compiler')->getClassComponentAliases()
          );
        }
        return $res;
      });
		
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

      Blade::directive('QFormInput', function ($attributes) {
        $attributes = QForm::arrayStringtoArray($attributes);
        $compiled = Container::getInstance()->make('blade.qformtagcompiler')->customCompile('qform-input', $attributes);
        return Container::getInstance()->make('blade.qformcompiler')->parseTokens($compiled);
      });

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
