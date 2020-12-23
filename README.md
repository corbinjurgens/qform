# Setup
## Manual Installation

Copy the following to main composer.(in the case that the package is added to packages/corbinjurgens/qform)
```
 "autoload": {
	"psr-4": {
		"Corbinjurgens\\QForm\\": "packages/corbinjurgens/qform/src"
	},
},
```
and run 
```
composer dump-autoload
```


Add the following to config/app.php providers
```
Corbinjurgens\QForm\ServiceProvider::class,
```
Add alias to config/app.php alias
```
"QForm" => Corbinjurgens\QForm\Facade::class,
```