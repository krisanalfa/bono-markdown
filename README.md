# Markdown Support for Bono Based Project
This package will make you able to use markdown as Norm schema or input type markdown in your Bono Based Project.

> **Note:** This package required PHP >5.4

# Installation

Add this package to your `composer.json` file

```js
"require": {
    "krisanalfa/bono-markdown": "~0.0.1",
}
```

Register Bono Markdown provider to your Bono provider configuration:

```php
'bono.providers' => [
    'Bono\\Markdown\\Provider\\MarkdownProvider' => [
        'gfm' => true, // activate Github Flavored Markdown
    ],
],
```

> **Note:** For another config options, see the Config section below.

# Example Usage

```php
$app = App::getInstance();
$markdown = '**Markdown is awesome**';

// render markdown syntax
echo $app->container['markdown']->render($markdown);

// render markdown form input
echo $app->container['markdown.form']->renderInput($markdown);
```

Input form use Bootstrap 3 as it's theme, if you want to override this one, you should use custom view,
and render your own partial template there.

# Options

Below is complete example of available option for your Markdown configuration:

```php
use App\MyCustomViewEngine;

'bono.providers' => [
    'Bono\\Markdown\\Provider\\MarkdownProvider' => [
        'gfm' => true, // default false
        'service' => true, // default false
        'endpoint' => '/md-parser', // default '/md'
        'requestKeyName' => 'entry', // default 'markdown',
        'view' => function () { // default is not set, fallback to Slim\View
            return new MyCustomViewEngine;
        },
        'partialTemplatePath' => 'templates/partials', // default is not set, using our own partials
    ],
],
```

#### Options –– `gfm`
Enable Github Flavored Markdown, default is `false`

#### Options –– `service`
If you want to enable web service that able to parse markdown set this to `true`, default is `false`

#### Options –– `endpoint`
The endpoint URL of your web service, require `service` to `true`, default is `md`


#### Options –– `requestKeyName`
The request key name for your web service, default is `markdown`

#### Options –– `view`
Your custom view engine, default is `Slim\View`

#### Options –– `partialTemplatePath`
Your custom partial template, default is not set (using our own partials)

# Norm Schema
You can also use this package as Norm schema. Be caution, when you call `formatInput()` method, the
method calls `$app->container['markdown.form']->renderInput($markdown)` method; the default
partial still use `_markdown/form` and `Slim\View` view engine. So, if you want to override this
behavior, you must set your own view in config file:

```php
'bono.providers' => [
    'Bono\\Markdown\\Provider\\MarkdownProvider' => [
        'view' => function() {
            return new MyCustomViewEngine();
        }
    ],
],
```

> **Note:** Make sure your custom view is an extends from `Slim\View`

# Web Service
To enable this feature, you have to change your config, and set `service` to `true`. Access them via `POST`
request. This is a simple javascript example to access your webservice:

```js
$.ajax({
    url: 'http://yourdomain.com/md',
    data: {
        'markdown': '**Markdown is awesome**'
    },
    type: 'POST'
}).done(function(html) {
    $('div').html(html);
});
```

> **Note:** As you see, the given URL, `/md` is based on your `endpoint` configuration.

# Another Hack

Form input use `Slim\View` to fetch and load the view. If you want to change this default, you can use
`$app->container['markdown.form']->setView($view)`.

```php
$view = new MyCustomViewEngine();

$app->container['markdown.form']->setView($view); // after this, form should use your custom view engine
```

One more. The default partials to render is `_markdown/form`, if you want to render another partials,
just pass a second argument in `Form::renderInput()` method. Example:

```php
$app->container['markdown.form']->renderInput($markdown, '_partials/markdown');
```

For more information, see `Bono\Markdown\Helper\Form.php` file.

> **Note:** Your custom view should an extends from `Slim\View`
