<?php

/*
 * This file is part of Facturama PHP SDK.
 *
 * (c) Facturama <dev@facturama.com>
 *
 * This source file is subject to a MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/*
 * Laravel 13 example (copy into app/Http/Controllers).
 *
 *   composer require facturama/facturama-php-sdk
 *   .env: FACTURAMA_USER, FACTURAMA_PASSWORD, FACTURAMA_ENV=sandbox
 *   routes/web.php:
 *     Route::post('/invoices', [InvoiceController::class, 'store']);
 *     Route::get('/invoices/{id}/pdf', [InvoiceController::class, 'pdf']);
 */

namespace App\Http\Controllers;

use Facturama\Client;
use Facturama\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceController
{
    // The Client is resolved from the container (configured by FacturamaServiceProvider).
    public function __construct(private readonly Client $facturama)
    {
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $cfdi = $this->facturama->cfdis()->create([
                'Serie' => 'A',
                'Currency' => 'MXN',
                'ExpeditionPlace' => '78140',
                'PaymentForm' => '01',
                'PaymentMethod' => 'PUE',
                'CfdiType' => 'I',
                'Exportation' => '01',
                'Receiver' => [
                    'Rfc' => $request->string('rfc')->toString(),
                    'Name' => $request->string('name')->toString(),
                    'CfdiUse' => 'G03',
                    'FiscalRegime' => $request->string('fiscal_regime', '601')->toString(),
                    'TaxZipCode' => $request->string('zip')->toString(),
                ],
                'Items' => [[
                    'ProductCode' => '10101504',
                    'Description' => 'Estudios de viabilidad',
                    'Unit' => 'NO APLICA',
                    'UnitCode' => 'E48',
                    'UnitPrice' => 50.0,
                    'Quantity' => 2.0,
                    'Subtotal' => 100.0,
                    'TaxObject' => '02',
                    'Taxes' => [[
                        'Total' => 16.0,
                        'Name' => 'IVA',
                        'Base' => 100.0,
                        'Rate' => 0.16,
                        'IsRetention' => false,
                    ]],
                    'Total' => 116.0,
                ]],
            ]);
        } catch (RequestException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json($cfdi, 201);
    }

    public function pdf(string $id): Response
    {
        $file = $this->facturama->cfdis()->download($id, Client::FILE_TYPE_PDF);

        return response(base64_decode($file->Content), 200, ['Content-Type' => 'application/pdf']);
    }
}
