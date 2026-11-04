[![CI](https://github.com/Facturama/facturama-php-sdk/actions/workflows/ci.yml/badge.svg)](https://github.com/Facturama/facturama-php-sdk/actions/workflows/ci.yml)

# Facturama SDK PHP

>[NOTA] Este documento esta disponible en [Español].
>
>Library to consume the Web API and Multiissuer API of [Facturama](https://api.facturama.mx/).
>
>Check the Facturama guide [here](https://apisandbox.facturama.mx/guias).

## Dependencies

This library requires PHP 8.3 or higher (tested up to PHP 8.5).

## Create user account

> Create a user account in [Sandbox](https://dev.facturama.mx/api/login) environment.
>
> For WEB API, use the RFC  "EKU9003173C9" to make tests, more information [here](https://apisandbox.facturama.mx/guias/perfil-fiscal).
>
> Digital stamp certificates (CSDs), more information [here](https://apisandbox.facturama.mx/guias/conocimientos/sellos-digitales-pruebas). 


## How do I install it?
```sh
    composer require facturama/facturama-php-sdk
```

### Including the Lib

It includes the library to your project.
```php
require __DIR__.'/vendor/autoload.php';
```

Start the development!

### Create an instance of Facturama\Client class

Example:
```php
$facturama = new \Facturama\Client('USER', 'PASSWORD');
```

> **Important:** by default the client targets the **Sandbox** environment (`https://apisandbox.facturama.mx`).
> To issue real CFDIs use `$facturama->setApiUrl('https://api.facturama.mx');`

## Laravel

The SDK ships with an integration for **Laravel 13** (PHP 8.3 or higher): service provider, facade and config file. They are registered automatically (package auto-discovery); you do not need to edit `config/app.php`.

### 1. Install

```sh
composer require facturama/facturama-php-sdk
```

### 2. Configure credentials

Add to your `.env`:

```env
FACTURAMA_USER=your-user
FACTURAMA_PASSWORD=your-password
FACTURAMA_ENV=sandbox
```

| Variable | Description |
|---|---|
| `FACTURAMA_USER` / `FACTURAMA_PASSWORD` | Your Facturama account credentials. |
| `FACTURAMA_ENV` | `sandbox` (default, test data only) or `production` (real CFDIs). Any other value throws an exception. |

> Use `sandbox` while developing. Switch to `production` only when you are going to stamp real invoices.

To change timeouts or other options, publish the config file (optional):

```sh
php artisan vendor:publish --tag=facturama-config
```

This creates `config/facturama.php`. After changing `.env` in production, run `php artisan config:clear` (or `config:cache`).

### 3. Use the client

Use the facade or type-hint `Facturama\Client` (the container always returns the same configured instance).

**Facade**
```php
use Facturama\Laravel\Facades\Facturama;

$clients = Facturama::clients()->list();
$cfdi = Facturama::cfdis()->create($data);
```

**Dependency injection**
```php
use Facturama\Client;

class InvoiceController
{
    public function __construct(private readonly Client $facturama)
    {
    }

    public function store(Request $request)
    {
        $cfdi = $this->facturama->cfdis()->create($request->validated());

        return response()->json($cfdi, 201);
    }
}
```

A complete controller is available at [`examples/Laravel/InvoiceController.php`](examples/Laravel/InvoiceController.php).

### 4. Available operations

| Resource | Methods |
|---|---|
| `cfdis()` | `create($cfdi)`, `list($filters)`, `download($id, $format, $type)`, `sendByEmail($id, $email)`, `cancel($id, $motive)` |
| `clients()` | `list()`, `find($id)`, `create($data)`, `update($id, $data)`, `delete($id)` |
| `products()` | `list()`, `find($id)`, `create($data)`, `update($id, $data)`, `delete($id)` |

For any other endpoint use the generic `get()`, `post()`, `put()` and `delete()` methods, e.g. `Facturama::get('catalogs/CfdiUses')`.

Download an invoice PDF (the API returns the content base64 encoded):

```php
$file = Facturama::cfdis()->download($id, 'pdf');

return response(base64_decode($file->Content), 200, ['Content-Type' => 'application/pdf']);
```

### 5. Error handling

API errors throw `Facturama\Exception\RequestException` (carrying the API message) and invalid responses throw `ResponseException`. In Laravel, turn them into a controlled response:

```php
use Facturama\Exception\RequestException;

try {
    $cfdi = Facturama::cfdis()->create($data);
} catch (RequestException $e) {
    report($e);

    return back()->withErrors(['facturama' => $e->getMessage()]);
}
```

### 6. Testing your application

Do not call Facturama in your tests: replace the client in the container.

```php
use Facturama\Client;

$this->mock(Client::class, function ($mock) {
    $mock->shouldReceive('cfdis->create')->once()->andReturn((object) ['Id' => 'abc']);
});
```

### Outside Laravel

The same resources work without Laravel:

```php
$facturama = new \Facturama\Client('USER', 'PASSWORD');
$cfdi = $facturama->cfdis()->create($data);
```

## Web API 

> Make CFDIs by using one issuer.
>
> *All operations will be reflected on Facturama's web app.*

## API operations

- Create, get, cancel CFDIs; download XMLs and PDFs and send them by email;
- Check profile and current subscription;
- Logo and digital certificates uploading;
- CRUDs for Product, Customer, Branch office and series.

Some examples: [here](https://github.com/Facturama/facturama-php-sdk/wiki/API-Web).


## Mult-issuer API

> Make CFDIs by using multiple issuers.
>
> *These operations will NOT be reflected on Facturama's web app.*


## Mult-issuer API operations

- Create, get, cancel CFDIs; download XMLs and PDFs;
- CRUD for digital sign certificates ("CSD", "Certificados de los Sellos Digitales").

Some examples: [here](https://github.com/Facturama/facturama-php-sdk/wiki/API-Multiemisor).


## I want to contribute!
That is great! Just fork the project in GitHub, create a topic branch, write some code, and add some tests for your new code.

Thanks for helping!

## Contributing:
[phansys](https://github.com/phansys)

[Spanish]: ./README.md
[examples]: ./examples/
