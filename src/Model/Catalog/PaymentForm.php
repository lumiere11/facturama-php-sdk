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
class PaymentForm implements CatalogInterface
{
    public const array PAYMENT_FORM_CASH = [
        'name' => 'Efectivo',
        'value' => '01',
    ];
    public const array PAYMENT_FORM_NOMINAL_CHECK = [
        'name' => 'Cheque nominativo',
        'value' => '02',
    ];
    public const array PAYMENT_FORM_TRANSFER = [
        'name' => 'Transferencia electrónica de fondos',
        'value' => '03',
    ];
    public const array PAYMENT_FORM_CREDIT_CARD = [
        'name' => 'Tarjeta de crédito',
        'value' => '04',
    ];
    public const array PAYMENT_FORM_DIGITAL_WALLET = [
        'name' => 'Monedero electrónico',
        'value' => '05',
    ];
    public const array PAYMENT_FORM_DIGITAL_MONEY = [
        'name' => 'Dinero electrónico',
        'value' => '06',
    ];
    public const array PAYMENT_FORM_GROCERY_COUPON = [
        'name' => 'Vales de despensa',
        'value' => '08',
    ];
    public const array PAYMENT_FORM_DATION = [
        'name' => 'Dación en pago',
        'value' => '12',
    ];
    public const array PAYMENT_FORM_SUBROGATION = [
        'name' => 'Pago por subrogación',
        'value' => '13',
    ];
    public const array PAYMENT_FORM_CONSIGNMENT = [
        'name' => 'Pago por consignación',
        'value' => '14',
    ];
    public const array PAYMENT_FORM_CONDONATION = [
        'name' => 'Condonación',
        'value' => '15',
    ];
    public const array PAYMENT_FORM_COMPENSATION = [
        'name' => 'Compensación',
        'value' => '17',
    ];
    public const array PAYMENT_FORM_NOVATION = [
        'name' => 'Novación',
        'value' => '23',
    ];
    public const array PAYMENT_FORM_CONFUSION = [
        'name' => 'Confusión',
        'value' => '24',
    ];
    public const array PAYMENT_FORM_DEBT_REMITTANCE = [
        'name' => 'Remisión de deuda',
        'value' => '25',
    ];
    public const array PAYMENT_FORM_PRESCRIPTION = [
        'name' => 'Prescripción o caducidad',
        'value' => '26',
    ];
    public const array PAYMENT_FORM_CREDITOR_SATISFACTION = [
        'name' => 'A satisfacción del acreedor',
        'value' => '27',
    ];
    public const array PAYMENT_FORM_DEBIT_CARD = [
        'name' => 'Tarjeta de débito',
        'value' => '28',
    ];
    public const array PAYMENT_FORM_SERVICE_CARD = [
        'name' => 'Tarjeta de servicios',
        'value' => '29',
    ];
    public const array PAYMENT_FORM_APPLICATION_OF_ADVANCES = [
        'name' => 'Aplicación de anticipos',
        'value' => '30',
    ];
    public const array PAYMENT_FORM_INTERMEDIARIES = [
        'name' => 'Intermediarios',
        'value' => '31',
    ];
    public const array PAYMENT_FORM_TO_BE_DEFINED = [
        'name' => 'Por definir',
        'value' => '99',
    ];

    /**
     * @var array
     *
     * @see https://www.api.facturama.com.mx/api/catalogs/PaymentForms
     */
    public const array CATALOG_PAYMENT_FORM = [
        self::PAYMENT_FORM_CASH,
        self::PAYMENT_FORM_NOMINAL_CHECK,
        self::PAYMENT_FORM_TRANSFER,
        self::PAYMENT_FORM_CREDIT_CARD,
        self::PAYMENT_FORM_DIGITAL_WALLET,
        self::PAYMENT_FORM_DIGITAL_MONEY,
        self::PAYMENT_FORM_GROCERY_COUPON,
        self::PAYMENT_FORM_DATION,
        self::PAYMENT_FORM_SUBROGATION,
        self::PAYMENT_FORM_CONSIGNMENT,
        self::PAYMENT_FORM_CONDONATION,
        self::PAYMENT_FORM_COMPENSATION,
        self::PAYMENT_FORM_NOVATION,
        self::PAYMENT_FORM_CONFUSION,
        self::PAYMENT_FORM_DEBT_REMITTANCE,
        self::PAYMENT_FORM_PRESCRIPTION,
        self::PAYMENT_FORM_CREDITOR_SATISFACTION,
        self::PAYMENT_FORM_DEBIT_CARD,
        self::PAYMENT_FORM_SERVICE_CARD,
        self::PAYMENT_FORM_APPLICATION_OF_ADVANCES,
        self::PAYMENT_FORM_INTERMEDIARIES,
        self::PAYMENT_FORM_TO_BE_DEFINED,
    ];

    /**
     * @throws \InvalidArgumentException
     *
     * @return array The matching `PAYMENT_FORM_*` constant for the given payment form name
     */
    #[\Override]
    public static function findByName(string $name): array
    {
        if (false === $index = array_search($name, array_column(self::CATALOG_PAYMENT_FORM, 'name'), true)) {
            throw new \InvalidArgumentException(\sprintf('Payment form with name "%s" was not found', $name));
        }

        return self::CATALOG_PAYMENT_FORM[$index];
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return array The matching `PAYMENT_FORM_*` constant for the given payment form value
     */
    #[\Override]
    public static function findByValue(string $value): array
    {
        if (false === $index = array_search($value, array_column(self::CATALOG_PAYMENT_FORM, 'value'), true)) {
            throw new \InvalidArgumentException(\sprintf('Payment form with value "%s" was not found', $value));
        }

        return self::CATALOG_PAYMENT_FORM[$index];
    }
}
