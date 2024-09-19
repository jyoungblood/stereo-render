# STEREO Render

### PHP abstraction functions to help more easily render views for [Slim Framework](https://www.slimframework.com/) (v4) with plain text, HTML, JSON, and Handlebars (using [BladeOne](https://github.com/EFTEC/BladeOne))

These functions aim to provide a simplified and standardized interface for rendering various types of data-driven responses as PSR-7 objects for use with Slim.

Included with the [STEREO](https://github.com/jyoungblood/stereo) internet toolkit.

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

Additionally, a path to an HTML file can be specified to load and render instead of a string:
```php
$app->get('/', function ($req, $res, $args) {

  return render::html($req, $res, '/hey/whats-up.html');

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

// fixit stopped here

// fixit replace w/ blade examples & descriptions

blade_template()
blade()

// fixit note variables for setting blade views path, cache path, and mode (related to compiling & caching behavior). note what the modes do, note how cache works, note that paths are relative to the 'public' directory, and that they need to be somewhere in the public dir (which is the public docroot)

  // set it in the .env if you want, or you can use $_ENV['BLADE_VIEW_PATH'] too. idgaf tbh

// here's what the modes do:     /** @var int BladeOne reads if the compiled file has changed. If it has changed,then the file is replaced. */
    AUTO = 0; *** AUTO IS DEFAULT
    /** @var int Then compiled file is always replaced. It's slow and it's useful for development. */
    SLOW = 1;
    /** @var int The compiled file is never replaced. It's fast and it's useful for production. */
    FAST = 2;
    /** @var int DEBUG MODE, the file is always compiled and the filename is identifiable. */
    DEBUG = 5;


// note the blade templates are designed to work specifically w/ stereo's structure (views & cache in the public folder)

// how to use bladeone html components? (they're included, use them or dont, who cares)



## render::hbs($request, $response, $parameters, $status = 200)
Renders a specific Handlebars template with an array of data, including any partials and global `locals` variables array. Returns a standard Slim (PSR-7) response object with optional HTTP status code (200 by default).
```php
$app->get('/', function ($req, $res, $args) {

  return render::hbs($req, $res, [
    'template' => 'index',
    'layout' => '_layouts/base', // optional "wrapper" layout template
    'title' => 'Page title', // for HTML <title> tag
    'data' => [
      'name' => 'Ringo',
      'friends' => [
        'Paul', 'George', 'John'
      ]
    ],
  ], 200); // optional status code, 200 by default

});
```

The parser function expects templates to be in a `templates` directory with `html` file extension. This can be customized by defining these variables in a global `settings` array:
```php
$GLOBALS['settings']['templates']['path'] = 'pages';
$GLOBALS['settings']['templates']['extension'] = 'hbs';
```

Additionally, an array of `locals` can be added to make variables available across all templates:
```php
$GLOBALS['locals'] = [
  'year' => date('Y'),
  'site_title' => 'Web Site Title',
  'site_code' => 'WST',
  'site_domain' => 'example.com',
];
```
```html
Welcome to {{locals.site_title}}, the year is {{locals.year}}!
```

Parameters from PHP $_GET and $_POST variables are automatically made available to templates rendered with this function, using the variables `{{GET}}` and `{{POST}}`:
```html
<!-- assuming a url like /hello/?name=Delilah&location=New%20York%20City -->
Hey there, {{GET.name}}, what's it like in {{GET.location}}?
```

Check out the [Handlebars Cookbook](https://zordius.github.io/HandlebarsCookbook/) to see everything you can do with LightnCandy and Handlebars.

Additionally, we've included a couple of helper functions.

The `date` helper applies the PHP `date()` function to a given variable or string (or `now` keyword for the current time)
```html
Date from unix timestamp: {{date unix_ts_var "d/m/Y"}}
Current date: {{date "now" "d/m/Y"}} <!-- use the "now" keyword instead of a variable to use the current time -->
Date from non-unix timestamp: {{date non_unix_ts_var "d/m/Y" "convert"}} <!-- add the "convert" parameter to convert the variable to unix time using strtotime() -->
```
The `#is` block helper allows for basic conditional logic:
```html
Is it 1981? {{#is locals.year "==" "1981"}} Yes! {{else}} No! {{/is}}
```

Custom helpers are easy to create. Take a look at how these helpers are defined in [initialize_handlebars_helpers()](https://github.com/jyoungblood/slime-render/blob/74e6e4a89a90a2490196a4d50d7466855820dd3a/src/render.php#L46). The Handlebars cookbook also has a reference for creating [custom helpers](https://zordius.github.io/HandlebarsCookbook/0021-customhelper.html) and [custom block helpers](https://zordius.github.io/HandlebarsCookbook/0022-blockhelper.html).














## render::handlebars($parameters)
Renders a specicific Handlebars template with data array the same as `render::hbs()`, but returns raw html instead of a PSR-7 response.
```php
$app->get('/', function ($req, $res, $args) {

  echo render::handlebars([
    'template' => 'email/test',
    'data' => [
      'link' => 'https://jy.hxgf.io',
    ]
  ]);

  return $res;
});
```



## render::lightncandy_html($parameters)($data)
Prepares and compiles a specific Handlebars template with an array of data, including any partials and global `locals` variables array.<br />
This is automatically called by `render::hbs()` but can be used as a standalone function if desired.
```php
$args = [
  'template' => 'index',
  'layout' => '_layouts/base',
  'title' => 'Page title',
];

$data = [
  'name' => 'Ringo',
  'friends' => [
    'Paul', 'George', 'John'
  ]
];

echo render::lightncandy_html($args)($data);
```