# STEREO Render

### PHP abstraction functions to help more easily render views for [Slim Framework](https://www.slimframework.com/) (v4) with plain text, HTML, JSON, and Blade (using [BladeOne](https://github.com/EFTEC/BladeOne))

These functions aim to provide a simplified and standardized interface for rendering various types of data-driven responses as PSR-7 objects for use with Slim.

Although this package can be used with any Slim4 project, it's specifically designed for use with the [STEREO](https://github.com/jyoungblood/stereo) internet toolkit.


# Installation
Easy install with composer:
```
composer require jyoungblood/stereo-render
```
```php
use Stereo\render;
require __DIR__ . '/vendor/autoload.php';
```

## Requirements
- [Slim Framework](https://www.slimframework.com/) 4
- [BladeOne](https://github.com/EFTEC/BladeOne) >= 4.13
- PHP >= 7.4


# Usage
## render::html($request, $response, $string, $status = 200)
Renders a string as HTML. Returns a standard Slim (PSR-7) response object with optional HTTP status code (200 by default).
```php
$app->get('/', function ($req, $res, $args) {

  return render::html($req, $res, '<h2>Hey whats up</h2>');

});
```

Additionally, a path to an HTML file (relative to the `public` web root) can be specified to load and render instead of a string:
```php
$app->get('/', function ($req, $res, $args) {

  return render::html($req, $res, 'hey/whats-up.html');

});
```




## render::text($request, $response, $string, $status = 200)
Renders a string as plain text. Returns a standard Slim (PSR-7) response object with optional HTTP status code (200 by default).
```php
$app->get('/', function ($req, $res, $args) {

  return render::text($req, $res, 'Hey whats up');

});
```

## render::redirect($request, $response, $string, $status = 302)
Renders a redirect as standard Slim (PSR-7) response object with optional HTTP status code.
```php
  return render::redirect($req, $res, 'https://google.com/');
```

## render::json($request, $response, $data, $status = 200)
Renders an array or data as standard Slim (PSR-7) response object with `application/json` content type and optional HTTP status code.
```php
$app->get('/json/', function ($req, $res, $args) {

  $data = [
    'name' => 'Ringo',
    'friends' => [
      'Paul', 'George', 'John'
    ]
  ];

  return render::json($req, $res, $data);

});
```



## render::blade($request, $response, $parameters, $status = 200)
Renders a specific Blade template with an array of data. Returns a standard Slim (PSR-7) response object with optional HTTP status code (200 by default).
```php
$app->get('/', function ($req, $res, $args) {

  return render::blade($req, $res, [
    'template' => 'index',
    'data' => [
      'name' => 'Ringo',
      'friends' => [
        'Paul', 'George', 'John'
      ]
    ],
  ], 200); // optional status code, 200 by default

});
```



The Blade compiler expects views and cache files to be directories called `views` and `cache`, respectively, in the `public` web root. These defaults, along with the compilation mode, can be customized in your `.env` file:
```php
BLADE_VIEWS_PATH = "views"
BLADE_CACHE_PATH = "cache"
BLADE_MODE = "AUTO"
```

The compilation mode can be set to `AUTO` (default), `SLOW`, `FAST`, or `DEBUG`, see the [BladeOne](https://github.com/EFTEC/BladeOne/blob/master/lib/BladeOne.php#L44) source for more information.

Check out the [BladeOne](https://github.com/EFTEC/BladeOne) and official [Blade](https://laravel.com/docs/blade) documentation to see everything you can do with this incredible templating syntax.

The [BladeOne HTML Extension](https://github.com/EFTEC/BladeOneHtml) is also included for conveniently creating form components with near-native performance.


## render::blade_template($parameters)
Renders a specicific Blade template with data array the same as `render::blade()`, but returns raw html instead of a PSR-7 response.
```php
$app->get('/', function ($req, $res, $args) {

  echo render::blade_template('email/test', [
    'link' => 'https://jy.hxgf.io',
  ]);

  return $res;
});
```
