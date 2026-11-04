<?php

declare(strict_types=1);

/*
 * This file is part of Facturama PHP SDK.
 *
 * (c) Facturama <dev@facturama.com>
 *
 * This source file is subject to a MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Facturama\Tests\Model\Catalog;

use Facturama\Client;
use Facturama\Model\Catalog\FederalTax;
use Facturama\Tests\Support\LiveApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @author Javier Spagnoletti <phansys@gmail.com>
 */
class FederalTaxTest extends LiveApiTestCase
{
    public function testFederalTaxApi(): void
    {
        $this->assertNotEmpty($this->client->get('catalogs/FederalTaxes'));
    }

    #[DataProvider('getFederalTaxes')]
    public function testFederalTaxes(\stdClass $federalTax): void
    {
        $this->assertTrue(property_exists($federalTax, 'Name'));
        $this->assertSame($federalTax->Name, FederalTax::findByName($federalTax->Name)['name']);

        $this->assertTrue(property_exists($federalTax, 'Value'));
        $this->assertSame($federalTax->Value, FederalTax::findByValue($federalTax->Value)['value']);
    }

    public function testNoUnusedFederalTaxes(): void
    {
        $apiFederalTaxes = self::getFederalTaxes();
        $paymentForms = FederalTax::CATALOG_FEDERAL_TAX;

        foreach ($apiFederalTaxes as $federalTax) {
            $federalTax = reset($federalTax);
            if (false === $index = array_search($federalTax->Name, array_column(FederalTax::CATALOG_FEDERAL_TAX, 'name'), true)) {
                $this->fail(\sprintf('Federal tax with name "%s" was not found', $federalTax->Name));
            }

            unset($paymentForms[$index]);
        }

        $this->assertEmpty($paymentForms);
    }

    /**
     * return \stdClass[]|\Generator
     */
    public static function getFederalTaxes(): iterable
    {
        $client = new Client(getenv('api_username') ?: null, getenv('api_password') ?: null);

        foreach ($client->get('catalogs/FederalTaxes') as $paymentForm) {
            yield [$paymentForm];
        }
    }
}
