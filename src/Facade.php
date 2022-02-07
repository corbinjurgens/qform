<?php

namespace Corbinjurgens\QForm;

use Illuminate\Support\Facades\Facade as BaseFacade;

use Corbinjurgens\QForm\ServiceProvider as S;

class Facade extends BaseFacade {
   protected static function getFacadeAccessor() { return S::$name; }
}