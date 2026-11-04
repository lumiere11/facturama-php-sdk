
[![CI](https://github.com/Facturama/facturama-php-sdk/actions/workflows/ci.yml/badge.svg)](https://github.com/Facturama/facturama-php-sdk/actions/workflows/ci.yml)

# Facturama PHP SDK 

>[NOTE] This document is also available in [English]
>
>Librería para consumir la API Web y API Multiemisor de [Facturama](https://api.facturama.mx/).
>
>Puedes consultar la guía completa de la [API](https://apisandbox.facturama.mx/guias).

## Crear cuenta de usuario

> Crear una cuenta de usuario en el ambiente de prueba [Sandbox](https://dev.facturama.mx/api/login) 
>
> Para API Web, realiza la configuración básica usando RFC de pruebas **"EKU9003173C9"**, más información [aquí](https://apisandbox.facturama.mx/guias/perfil-fiscal).
>
> Sellos Digitales de prueba (CSD), [aquí](https://apisandbox.facturama.mx/guias/conocimientos/sellos-digitales-pruebas). 

## Inicio Rápido

### Dependencias

Esta librería requiere PHP 8.3 o superior (probada hasta PHP 8.5).

### Instalación

```sh
    composer require facturama/facturama-php-sdk
```

### Incluyendo la librería

Incluye la librería a tu proyecto
```php
require __DIR__.'/vendor/autoload.php';
```
### Crear una instancia de la clase Facturama\Client
Ejemplo:
```php
$facturama = new \Facturama\Client('USER', 'PASSWORD');
```

> **Importante:** por defecto el cliente apunta al ambiente **Sandbox** (`https://apisandbox.facturama.mx`).
> Para timbrar en producción usa `$facturama->setApiUrl('https://api.facturama.mx');`
## Laravel

El SDK incluye integración para **Laravel 13** (PHP 8.3 o superior): service provider, facade y archivo de configuración. Se registran automáticamente (package auto-discovery), no hay que tocar `config/app.php`.

### 1. Instalar

```sh
composer require facturama/facturama-php-sdk
```

### 2. Configurar credenciales

Agrega a tu `.env`:

```env
FACTURAMA_USER=tu-usuario
FACTURAMA_PASSWORD=tu-password
FACTURAMA_ENV=sandbox
```

| Variable | Descripción |
|---|---|
| `FACTURAMA_USER` / `FACTURAMA_PASSWORD` | Credenciales de tu cuenta Facturama. |
| `FACTURAMA_ENV` | `sandbox` (por defecto, solo datos de prueba) o `production` (CFDI reales). Cualquier otro valor lanza una excepción. |

> Usa `sandbox` mientras desarrollas. Cambia a `production` solo cuando vayas a timbrar facturas reales.

Si necesitas cambiar timeouts u otras opciones, publica el archivo de configuración (opcional):

```sh
php artisan vendor:publish --tag=facturama-config
```

Esto crea `config/facturama.php`. Después de cambiar el `.env` en producción, ejecuta `php artisan config:clear` (o `config:cache`).

### 3. Usar el cliente

Puedes usar el facade o inyectar `Facturama\Client` (el contenedor devuelve siempre la misma instancia ya configurada).

**Facade**
```php
use Facturama\Laravel\Facades\Facturama;

$clientes = Facturama::clients()->list();
$cfdi = Facturama::cfdis()->create($datos);
```

**Inyección de dependencias**
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

Un controlador completo está en [`examples/Laravel/InvoiceController.php`](examples/Laravel/InvoiceController.php).

### 4. Operaciones disponibles

| Recurso | Métodos |
|---|---|
| `cfdis()` | `create($cfdi)`, `list($filtros)`, `download($id, $formato, $tipo)`, `sendByEmail($id, $email)`, `cancel($id, $motivo)` |
| `clients()` | `list()`, `find($id)`, `create($datos)`, `update($id, $datos)`, `delete($id)` |
| `products()` | `list()`, `find($id)`, `create($datos)`, `update($id, $datos)`, `delete($id)` |

Para cualquier otro endpoint usa los métodos genéricos `get()`, `post()`, `put()` y `delete()`, por ejemplo `Facturama::get('catalogs/CfdiUses')`.

Descargar el PDF de una factura (la API devuelve el contenido en base64):

```php
$archivo = Facturama::cfdis()->download($id, 'pdf');

return response(base64_decode($archivo->Content), 200, ['Content-Type' => 'application/pdf']);
```

### 5. Manejo de errores

Los errores de la API lanzan `Facturama\Exception\RequestException` (con el mensaje de la API) y las respuestas inválidas `ResponseException`. En Laravel conviene convertirlas en una respuesta controlada:

```php
use Facturama\Exception\RequestException;

try {
    $cfdi = Facturama::cfdis()->create($datos);
} catch (RequestException $e) {
    report($e);

    return back()->withErrors(['facturama' => $e->getMessage()]);
}
```

### 6. Pruebas en tu aplicación

No llames a Facturama en tus tests: reemplaza el cliente en el contenedor.

```php
use Facturama\Client;

$this->mock(Client::class, function ($mock) {
    $mock->shouldReceive('cfdis->create')->once()->andReturn((object) ['Id' => 'abc']);
});
```

### Fuera de Laravel

Los mismos recursos funcionan sin Laravel:

```php
$facturama = new \Facturama\Client('USER', 'PASSWORD');
$cfdi = $facturama->cfdis()->create($datos);
```

## API Web

> Creación de CFDIs con un único emisor, (el propietario de la cuenta, cuyo Perfil Fiscal se tiene configurado)
> 
> *Todas las operaciones son reflejadas en la plataforma web.*

## Operaciones Web API


- Crear, Consultar y Cancelar CFDI, así como descargar XML, PDF y envío de
  éstos por e-mail;
- Consultar Perfil y Suscripción actual;
- Carga de Logo y Certificados Digitales;
- CRUD de Productos, Clientes, Sucursales y Series.

Ejemplos: [aquí](https://github.com/Facturama/facturama-php-sdk/wiki/API-Web)


## API Multiemisor

> Creación de CFDIs con múltiples emisores.
>
> *Las operaciones NO se reflejan en la plataforma web.*

## Operaciones API Multiemisor

- Crear, Consultar, Cancelar descarga de XML
- CRUD de CSD (Certificados de los Sellos Digitales).

Ejemplos: [aquí](https://github.com/Facturama/facturama-php-sdk/wiki/API-Multiemisor)


## ¡Quiero contribuir!

¡Genial! Simplemente realiza un fork del proyecto en GitHub, crea un branch, escribe un código y agregua algunas pruebas para tu nuevo código.

¡Gracias por ayudar!
## Contribuyendo:
[phansys](https://github.com/phansys)

[English]: ./README-en.md
[ejemplos]: ./examples/
