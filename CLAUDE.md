# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
composer install                                   # dev dependencies (PHPUnit 9, php-cs-fixer 3, phpmd)
vendor/bin/phpunit -c phpunit.xml.dist             # full suite
vendor/bin/phpunit --filter testCfdiUses tests/Model/Catalog/CfdiUseTest.php   # single test
vendor/bin/phpunit tests/ClientTest.php            # offline tests only
vendor/bin/phpmd src/ text phpmd.xml.dist          # mess detector (runs in CI)
vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --dry-run -v   # style check (src/ and tests/ only)
```

CI (`.github/workflows/ci.yml`) runs those three checks on PHP 7.4–8.5; 7.4 also runs with `--prefer-lowest`. The package supports `php >=7.4`, so avoid syntax newer than 7.4 in `src/`.

## Architecture

This is a thin SDK for the Facturama (Mexican CFDI invoicing) REST API. Everything lives in the `Facturama\` namespace (PSR-4 → `src/`).

- `src/Client.php` is the whole HTTP layer. It exposes only `get/post/put/delete($path, ...)` and returns the decoded JSON (`stdClass`/array/`null`). There are **no per-resource classes**: callers build the arrays themselves and pass API paths such as `catalogs/CfdiUses`. The Guzzle client is injectable (4th constructor arg, mutually exclusive with `$requestOptions`), which is how tests mock HTTP.
- `Client::executeRequest()` translates failures: Guzzle errors become `RequestException` (using the API's `Message`, with `ModelState` messages wrapped in a chained `ModelException`); an unparseable success body raises `ResponseException`. All derive from `Exception\FacturamaException`.
- The default base URL is the **Sandbox** (`https://apisandbox.facturama.mx`); production needs `setApiUrl('https://api.facturama.mx')`.
- `src/Model/Catalog/*` are static offline copies of SAT/Facturama catalogs (`CfdiUse`, `CfdiType`, `PaymentForm`, `PaymentMethod`, `FederalTax`). Each defines `const` entries (`name`, `value`, flags), a `CATALOG_*` list and `findByName()`/`findByValue()` per `CatalogInterface`. When the API adds codes (e.g. CFDI 4.0 `S01`, `CP01`, `CN01`), add the constant **and** append it to the `CATALOG_*` array.
- `examples/` holds runnable scripts by API (`API_Web`, `API_Multiemisor`) and CFDI version. Only CFDI 4.0 examples are kept; 3.3 can no longer be stamped. Credentials come from `FACTURAMA_USER`/`FACTURAMA_PASSWORD` via `examples/credentials.php`, which is also an autoload-dev file.

## Testing notes

- `tests/Model/Catalog/*Test.php` compare the local catalogs against the **live Sandbox API** (with the credentials set in `phpunit.xml.dist`), so they need network access and can fail when the remote catalog changes. `tests/ClientTest.php` is offline (Guzzle `MockHandler`).
- `testNoUnusedCfdiUses` and similar tests require the local catalog and the API to match exactly, so removing or renaming an entry can break them.

## Versioning

`Client::VERSION` is the single source of truth; `USER_AGENT` is derived from it. Keep it in sync with `extra.branch-alias` in `composer.json` when releasing.
