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
class PaymentMethod implements CatalogInterface
{
    public const array PAYMENT_METHOD_INITIAL = [
        'name' => 'Pago inicial y parcialidades',
        'value' => 'PIP',
    ];
    public const array PAYMENT_METHOD_DEFERRED = [
        'name' => 'Pago en parcialidades ó diferido',
        'value' => 'PPD',
    ];
    public const array PAYMENT_METHOD_SINGLE = [
        'name' => 'Pago en una sola exhibición',
        'value' => 'PUE',
    ];

    /**
     * @var array
     *
     * @see https://www.api.facturama.com.mx/api/catalogs/PaymentMethods
     */
    public const array CATALOG_PAYMENT_METHOD = [
        self::PAYMENT_METHOD_INITIAL,
        self::PAYMENT_METHOD_DEFERRED,
        self::PAYMENT_METHOD_SINGLE,
    ];

    /**
     * @throws \InvalidArgumentException
     *
     * @return array The matching `PAYMENT_METHOD_*` constant for the given payment method name
     */
    #[\Override]
    public static function findByName(string $name): array
    {
        if (false === $index = array_search($name, array_column(self::CATALOG_PAYMENT_METHOD, 'name'), true)) {
            throw new \InvalidArgumentException(\sprintf('Payment method with name "%s" was not found', $name));
        }

        return self::CATALOG_PAYMENT_METHOD[$index];
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return array The matching `PAYMENT_METHOD_*` constant for the given payment method value
     */
    #[\Override]
    public static function findByValue(string $value): array
    {
        if (false === $index = array_search($value, array_column(self::CATALOG_PAYMENT_METHOD, 'value'), true)) {
            throw new \InvalidArgumentException(\sprintf('Payment method with value "%s" was not found', $value));
        }

        return self::CATALOG_PAYMENT_METHOD[$index];
    }
}
