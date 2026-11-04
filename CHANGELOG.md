# Changelog

## 3.0.0

### Breaking
- Requires PHP `^8.3` (aligned with Laravel 13) and Guzzle `^7.8`.
- `Client` methods now have native parameter and return types, and every file uses `declare(strict_types=1)`.
- `CatalogInterface::findByName()`/`findByValue()` and all catalog constants are now typed (PHP 8.3 typed class constants).
- Invalid JSON in a successful response now throws `ResponseException` (it used to leak `InvalidArgumentException`); a non-JSON error body now throws `RequestException`.
- Removed the CFDI 3.3 examples; they can no longer be stamped.

### Added
- Laravel integration (Laravel 13): auto-discovered `FacturamaServiceProvider`, `Facturama` facade and publishable `config/facturama.php` (`FACTURAMA_USER`, `FACTURAMA_PASSWORD`, `FACTURAMA_ENV`).
- Typed resources: `$client->cfdis()`, `$client->clients()`, `$client->products()`.
- CFDI 4.0 uses `S01`, `CP01`, `CN01` in `CfdiUse`; `STATE_CIUDAD_DE_MEXICO`.
- `Client::SANDBOX_URL`, `Client::PRODUCTION_URL`, `Client::isProduction()`.
- GitHub Actions CI and tag-triggered release workflow.

### Deprecated
- `CfdiUse::CFDI_USE_TO_BE_DEFINED` (`P01`) no longer exists in CFDI 4.0.
