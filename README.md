<p align="center"><a href="https://fresns.org" target="_blank"><img src="https://raw.githubusercontent.com/fresns/docs/main/images/Fresns-Logo(orange).png" width="300"></a></p>

<p align="center">
<img src="https://img.shields.io/badge/PHP-%5E8.0-blueviolet" alt="PHP">
<img src="https://img.shields.io/badge/Laravel-9.0%7C%5E10.0-orange" alt="Laravel">
<img src="https://img.shields.io/badge/License-Apache--2.0-green" alt="License">
</p>

## About

Command word manager(in laravel) helps plugins(individual functional modules) to communicate with each other easily.

## Install

To install through Composer, by run the following command:

```bash
composer require fresns/cmd-word-manager
```

## Using

### Create cmd word service providers

```php
// Generate cmd word providers: /app/Providers/CmdWordServiceProvider.php
php artisan make:cmd-word-provider
```

```php
// Generate a cmd word provider for the specified name or directory
php artisan make:cmd-word-provider [Name] [--path Name]

php artisan make:cmd-word-provider FooBar --path Demo
// path directory: /demo/FooBar/Providers/CmdWordServiceProvider.php
```

### Registered service providers

In the `providers` key value of the `/config/app.php` file, add the generated command word service provider.

- `App\Providers\CmdWordServiceProvider::class`
- or
- `Demo/FooBar/Providers/CmdWordServiceProvider::class`

```php
<?php

return [
    <...>
    'providers' => [
        <...>
        App\Providers\CmdWordServiceProvider::class,
    ],
    <...>
];
```

### Mapping command word

In the properties of the command word provider file `/app/Providers/CmdWordServiceProvider.php`, in `$cmdWordsMap`, add the command word mapping config.

```php
<?php

namespace App\Providers;

use Plugins\BarBaz\Models\TestModel;
use Plugins\BarBaz\Services\AWordService;
use Plugins\BarBaz\Services\BWordService;

class CmdWordServiceProvider extends ServiceProvider implements \Fresns\CmdWordManager\Contracts\CmdWordProviderContract
{
    <...>
    protected $fsKeyName = 'FooBar';

    protected $cmdWordsMap = [
        ['word' => 'test', 'provider' => [AWordService::class, 'handleTest']],
        ['word' => 'staticTest', 'provider' => [BWordService::class, 'handleStaticTest']],
        ['word' => 'modelTest', 'provider' => [TestModel::class, 'handleModelTest']],
    ];
    <...>
}
```

### Using cmd words

#### Request input

| Name | Description |
| --- | --- |
| `\FresnsCmdWord` | Cmd Word Facades |
| `FresnsEmail` | Requesting Object `fskey`, Leaving blank or filling in `Fresns` means that the main program handles the request |
| `sendEmail` | Command word |
| `$wordBody` | Parameter list of command word parameters |

```php
// $parameter list = (parameter array);
$wordBody = [
    "email" => "Mail address",
    "title" => "Mail title",
    "content" => "Mail content"
];

// \facades::plugin('plugin name')->cmd word($parameter list): Define the contract for the return object
\FresnsCmdWord::plugin('FresnsEmail')->sendEmail($wordBody);
```

**Another way to write**

```php
\FresnsCmdWord::plugin('FresnsEmail')->sendEmail([
    "email" => "Mail address",
    "title" => "Mail title",
    "content" => "Mail content"
]);
```

#### Result output

| Name | Description |
| --- | --- |
| code | Status code |
| message | Status information |
| data | Output data |

```json
// Success
{
    "code": 0,
    "message": "ok",
    "data": {
        //Command word output data
    }
}

// Failure
{
    "code": 21001,
    "message": "Plugin does not exist",
    "data": {
        //Command word output data
    }
}
```

| Code | Message |
| --- | --- |
| 21000 | Unconfigured plugin |
| 21001 | Plugin does not exist |
| 21002 | Command word does not exist |
| 21003 | Command word unknown error |
| 21004 | Command word not responding |
| 21005 | Command word request parameter error |
| 21006 | Command word execution request error |
| 21007 | Command word response result is incorrect |
| 21008 | Data anomalies, queries not available or data duplication |
| 21009 | Execution anomalies, missing files or logging errors |
| 21010 | Command word function is disabled |

#### Result processing($fresnsResp)

If you are standardized to use command word return results, you can use Fresns Response to help you quickly handle the return of the request.

**Example:**

```php
$fresnsResp = \FresnsCmdWord::plugin('FresnsEmail')->sendEmail($wordBody);
```

**Handling abnormal situations**

```php
if ($fresnsResp->isErrorResponse()) {
    return $fresnsResp->errorResponse(); //When an error is reported, the full amount of parameters is output(code+message+data)
}
```

**Handling normal situations**

```php
$fresnsResp->getOrigin(); //Obtaining raw data
$fresnsResp->getCode(); //Get code only
$fresnsResp->getMessage(); //Get only the message
$fresnsResp->getData(); //Get only the full amount of data
$fresnsResp->getData('user.nickname'); //Get only the parameters specified in data, for example: data.user.nickname
$fresnsResp->isSuccessResponse(); //Determine if the request is true
$fresnsResp->isErrorResponse(); //Determine if the request is false
$fresnsResp->getErrorResponse(); //Get the error response object
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/fresns/command-word/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/fresns/command-word/issues).
3. Contribute new features or update the wiki.

*The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable.*

## License

Fresns Command Word Manager is open-sourced software licensed under the [Apache-2.0 license](https://github.com/fresns/cmd-word-manager/blob/main/LICENSE).