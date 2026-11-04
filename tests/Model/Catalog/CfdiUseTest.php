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
use Facturama\Model\Catalog\CfdiUse;
use Facturama\Tests\Support\LiveApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @author Javier Spagnoletti <phansys@gmail.com>
 */
class CfdiUseTest extends LiveApiTestCase
{
    public function testCfdiUseApi(): void
    {
        $this->assertNotEmpty($this->client->get('catalogs/CfdiUses'));
    }

    #[DataProvider('getCfdiUses')]
    public function testCfdiUses(\stdClass $cfdiUse): void
    {
        $this->assertTrue(property_exists($cfdiUse, 'Name'));
        $this->assertSame($cfdiUse->Name, CfdiUse::findByName($cfdiUse->Name)['name']);

        $this->assertTrue(property_exists($cfdiUse, 'Value'));
        $this->assertSame($cfdiUse->Value, CfdiUse::findByValue($cfdiUse->Value)['value']);

        $this->assertTrue(property_exists($cfdiUse, 'Moral'));
        $this->assertSame($cfdiUse->Moral, CfdiUse::findByName($cfdiUse->Name)['moral']);

        $this->assertTrue(property_exists($cfdiUse, 'Natural'));
        $this->assertSame($cfdiUse->Natural, CfdiUse::findByName($cfdiUse->Name)['natural']);
    }

    public function testNoUnusedCfdiUses(): void
    {
        $apiCfdiUses = self::getCfdiUses();
        $cfdiUses = CfdiUse::CATALOG_CFDI_USE;

        foreach ($apiCfdiUses as $cfdiUse) {
            $cfdiUse = reset($cfdiUse);
            if (false === $index = array_search($cfdiUse->Name, array_column(CfdiUse::CATALOG_CFDI_USE, 'name'), true)) {
                $this->fail(\sprintf('CFDI use with name "%s" was not found', $cfdiUse->Name));
            }

            unset($cfdiUses[$index]);
        }

        $this->assertEmpty($cfdiUses);
    }

    /**
     * return \stdClass[]|\Generator
     */
    public static function getCfdiUses(): iterable
    {
        $client = new Client(getenv('api_username') ?: null, getenv('api_password') ?: null);

        // RFC with 12 chars length
        foreach ($client->get('catalogs/CfdiUses', ['keyword' => 'AAAA12345678']) as $cfdiUse) {
            yield [$cfdiUse];
        }

        // RFC with 13 chars length
        foreach ($client->get('catalogs/CfdiUses', ['keyword' => 'AAAA123456789']) as $cfdiUse) {
            yield [$cfdiUse];
        }
    }
}
