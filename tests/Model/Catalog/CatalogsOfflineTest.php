<?php

/*
 * This file is part of Facturama PHP SDK.
 *
 * (c) Facturama <dev@facturama.com>
 *
 * This source file is subject to a MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Facturama\Tests\Model\Catalog;

use Facturama\Model\Catalog\CfdiType;
use Facturama\Model\Catalog\CfdiUse;
use Facturama\Model\Catalog\FederalTax;
use Facturama\Model\Catalog\PaymentForm;
use Facturama\Model\Catalog\PaymentMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Offline tests for the local catalogs: no network access required.
 */
class CatalogsOfflineTest extends TestCase
{
    public static function catalogProvider(): array
    {
        return [
            'CfdiType' => [CfdiType::class, CfdiType::CATALOG_CFDI_TYPE],
            'CfdiUse' => [CfdiUse::class, CfdiUse::CATALOG_CFDI_USE],
            'FederalTax' => [FederalTax::class, FederalTax::CATALOG_FEDERAL_TAX],
            'PaymentForm' => [PaymentForm::class, PaymentForm::CATALOG_PAYMENT_FORM],
            'PaymentMethod' => [PaymentMethod::class, PaymentMethod::CATALOG_PAYMENT_METHOD],
        ];
    }

    #[DataProvider('catalogProvider')]
    public function testCatalogIsNotEmptyAndEntriesAreWellFormed($class, array $catalog): void
    {
        $this->assertNotEmpty($catalog);
        foreach ($catalog as $entry) {
            $this->assertArrayHasKey('name', $entry);
            $this->assertArrayHasKey('value', $entry);
            $this->assertNotSame('', $entry['name']);
            $this->assertNotSame('', $entry['value']);
        }
    }

    #[DataProvider('catalogProvider')]
    public function testNamesAreUnique($class, array $catalog): void
    {
        $names = array_column($catalog, 'name');
        $this->assertSame(\count($names), \count(array_unique($names)));
    }

    #[DataProvider('catalogProvider')]
    public function testFindByNameAndValueRoundTrip($class, array $catalog): void
    {
        foreach ($catalog as $entry) {
            $this->assertSame($entry, $class::findByName($entry['name']));

            // Values are not unique in every catalog (e.g. FederalTax, CfdiType), so only the value is compared.
            $found = $this->toList($class::findByValue($entry['value']));
            $this->assertContains($entry['value'], array_column($found, 'value'));
        }
    }

    #[DataProvider('catalogProvider')]
    public function testUnknownNameThrows($class, array $catalog): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $class::findByName('__not-found__');
    }

    #[DataProvider('catalogProvider')]
    public function testUnknownValueIsNotFound($class, array $catalog): void
    {
        // CfdiType::findByValue() is a generator and yields nothing; the others throw.
        try {
            $this->assertSame([], $this->toList($class::findByValue('__not-found__')));
        } catch (\InvalidArgumentException $e) {
            $this->addToAssertionCount(1);
        }
    }

    public function testCfdiUseValuesAreUnique(): void
    {
        $values = array_column(CfdiUse::CATALOG_CFDI_USE, 'value');
        $this->assertSame(\count($values), \count(array_unique($values)));
    }

    #[DataProvider('cfdi40UseProvider')]
    public function testCfdi40UsesAreAvailable($value, $name): void
    {
        $this->assertSame($name, CfdiUse::findByValue($value)['name']);
    }

    public static function cfdi40UseProvider(): array
    {
        return [
            ['S01', 'Sin efectos fiscales'],
            ['CP01', 'Pagos'],
            ['CN01', 'Nómina'],
        ];
    }

    public function testPayrollUseIsNaturalPersonsOnly(): void
    {
        $use = CfdiUse::findByValue('CN01');
        $this->assertTrue($use['natural']);
        $this->assertFalse($use['moral']);
    }

    private function toList($result)
    {
        if ($result instanceof \Generator) {
            return iterator_to_array($result, false);
        }

        return [$result];
    }
}
