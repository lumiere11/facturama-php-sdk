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

namespace Facturama\Model\Catalog;

/**
 * @author Javier Spagnoletti <phansys@gmail.com>
 */
class FederalTax implements CatalogInterface
{
    public const array FEDERAL_TAX_IEPS = [
        'name' => 'IEPS',
        'value' => '003',
    ];
    public const array FEDERAL_TAX_IEPS_RET = [
        'name' => 'IEPS RET',
        'value' => '003',
    ];
    public const array FEDERAL_TAX_ISR = [
        'name' => 'ISR',
        'value' => '001',
    ];
    public const array FEDERAL_TAX_IVA = [
        'name' => 'IVA',
        'value' => '002',
    ];
    public const array FEDERAL_TAX_IVA_RET = [
        'name' => 'IVA RET',
        'value' => '002',
    ];

    /**
     * @var array
     *
     * @see https://www.api.facturama.com.mx/api/catalogs/FederalTaxes
     */
    public const array CATALOG_FEDERAL_TAX = [
        self::FEDERAL_TAX_IEPS,
        self::FEDERAL_TAX_IEPS_RET,
        self::FEDERAL_TAX_ISR,
        self::FEDERAL_TAX_IVA,
        self::FEDERAL_TAX_IVA_RET,
    ];

    /**
     * @throws \InvalidArgumentException
     *
     * @return array The matching `FEDERAL_TAX_*` constant for the given federal tax name
     */
    #[\Override]
    public static function findByName(string $name): array
    {
        if (false === $index = array_search($name, array_column(self::CATALOG_FEDERAL_TAX, 'name'), true)) {
            throw new \InvalidArgumentException(\sprintf('Federal tax with name "%s" was not found', $name));
        }

        return self::CATALOG_FEDERAL_TAX[$index];
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return array The matching `FEDERAL_TAX_*` constant for the given federal tax value
     */
    #[\Override]
    public static function findByValue(string $value): array
    {
        if (false === $index = array_search($value, array_column(self::CATALOG_FEDERAL_TAX, 'value'), true)) {
            throw new \InvalidArgumentException(\sprintf('Federal tax with value "%s" was not found', $value));
        }

        return self::CATALOG_FEDERAL_TAX[$index];
    }
}
