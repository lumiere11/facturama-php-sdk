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
class CfdiUse implements CatalogInterface
{
    public const array CFDI_USE_GOODS_ACQUISITION = [
        'name' => 'Adquisición de mercancias',
        'value' => 'G01',
        'moral' => true,
        'natural' => true,
    ];
    public const array CFDI_USE_RETURNS = [
        'name' => 'Devoluciones, descuentos o bonificaciones',
        'value' => 'G02',
        'moral' => true,
        'natural' => true,
    ];
    public const array CFDI_USE_GENERAL_EXPENSES = [
        'name' => 'Gastos en general',
        'value' => 'G03',
        'moral' => true,
        'natural' => true,
    ];
    public const array CFDI_USE_BUILDING = [
        'name' => 'Construcciones',
        'value' => 'I01',
        'moral' => true,
        'natural' => true,
    ];
    public const array CFDI_USE_OFFICE_FURNITURE = [
        'name' => 'Mobilario y equipo de oficina por inversiones',
        'value' => 'I02',
        'moral' => true,
        'natural' => true,
    ];
    public const array CFDI_USE_TRANSPORT_EQUIPMENT = [
        'name' => 'Equipo de transporte',
        'value' => 'I03',
        'moral' => true,
        'natural' => true,
    ];
    public const array CFDI_USE_COMPUTING_EQUIPMENT = [
        'name' => 'Equipo de computo y accesorios',
        'value' => 'I04',
        'moral' => true,
        'natural' => true,
    ];
    public const array CFDI_USE_TOOLING = [
        'name' => 'Dados, troqueles, moldes, matrices y herramental',
        'value' => 'I05',
        'moral' => true,
        'natural' => true,
    ];
    public const array CFDI_USE_PHONE_COMMUNICATIONS = [
        'name' => 'Comunicaciones telefónicas',
        'value' => 'I06',
        'moral' => true,
        'natural' => true,
    ];
    public const array CFDI_USE_SATELLITAL_COMMUNICATION = [
        'name' => 'Comunicaciones satelitales',
        'value' => 'I07',
        'moral' => true,
        'natural' => true,
    ];
    public const array CFDI_USE_OTHER_MACHINERY = [
        'name' => 'Otra maquinaria y equipo',
        'value' => 'I08',
        'moral' => true,
        'natural' => true,
    ];
    /**
     * @deprecated `P01` was removed in CFDI 4.0; only valid for CFDI 3.3.
     */
    public const array CFDI_USE_TO_BE_DEFINED = [
        'name' => 'Por definir',
        'value' => 'P01',
        'moral' => true,
        'natural' => true,
    ];
    public const array CFDI_USE_MEDICAL_EXPENSES = [
        'name' => 'Honorarios médicos, dentales y gastos hospitalarios.',
        'value' => 'D01',
        'moral' => false,
        'natural' => true,
    ];
    public const array CFDI_USE_MEDICAL_EXPENSES_BY_DISABILITY = [
        'name' => 'Gastos médicos por incapacidad o discapacidad',
        'value' => 'D02',
        'moral' => false,
        'natural' => true,
    ];
    public const array CFDI_USE_FUNERAL_EXPENSES = [
        'name' => 'Gastos funerales.',
        'value' => 'D03',
        'moral' => false,
        'natural' => true,
    ];
    public const array CFDI_USE_DONATIONS = [
        'name' => 'Donativos.',
        'value' => 'D04',
        'moral' => false,
        'natural' => true,
    ];
    public const array CFDI_USE_MORTGAGE_LOANS = [
        'name' => 'Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación).',
        'value' => 'D05',
        'moral' => false,
        'natural' => true,
    ];
    public const array CFDI_USE_SAR_CONTRIBUTIONS = [
        'name' => 'Aportaciones voluntarias al SAR.',
        'value' => 'D06',
        'moral' => false,
        'natural' => true,
    ];
    public const array CFDI_USE_MEDICAL_INSURANCE = [
        'name' => 'Primas por seguros de gastos médicos.',
        'value' => 'D07',
        'moral' => false,
        'natural' => true,
    ];
    public const array CFDI_USE_SCHOOL_TRANSPORTATION_EXPENSES = [
        'name' => 'Gastos de transportación escolar obligatoria.',
        'value' => 'D08',
        'moral' => false,
        'natural' => true,
    ];
    public const array CFDI_USE_PENSION_PLAN_DEPOSIT = [
        'name' => 'Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones.',
        'value' => 'D09',
        'moral' => false,
        'natural' => true,
    ];
    public const array CFDI_USE_EDUCATIONAL_SERVICE_PAYMENTS = [
        'name' => 'Pagos por servicios educativos (colegiaturas)',
        'value' => 'D10',
        'moral' => false,
        'natural' => true,
    ];

    /**
     * CFDI 4.0 only.
     */
    public const array CFDI_USE_WITHOUT_TAX_EFFECTS = [
        'name' => 'Sin efectos fiscales',
        'value' => 'S01',
        'moral' => true,
        'natural' => true,
    ];
    /**
     * CFDI 4.0 only.
     */
    public const array CFDI_USE_PAYMENTS = [
        'name' => 'Pagos',
        'value' => 'CP01',
        'moral' => true,
        'natural' => true,
    ];
    /**
     * CFDI 4.0 only.
     */
    public const array CFDI_USE_PAYROLL = [
        'name' => 'Nómina',
        'value' => 'CN01',
        'moral' => false,
        'natural' => true,
    ];

    /**
     * @var array
     *
     * @see https://www.api.facturama.com.mx/api/catalogs/CfdiUses
     */
    public const array CATALOG_CFDI_USE = [
        self::CFDI_USE_GOODS_ACQUISITION,
        self::CFDI_USE_RETURNS,
        self::CFDI_USE_GENERAL_EXPENSES,
        self::CFDI_USE_BUILDING,
        self::CFDI_USE_OFFICE_FURNITURE,
        self::CFDI_USE_TRANSPORT_EQUIPMENT,
        self::CFDI_USE_COMPUTING_EQUIPMENT,
        self::CFDI_USE_TOOLING,
        self::CFDI_USE_PHONE_COMMUNICATIONS,
        self::CFDI_USE_SATELLITAL_COMMUNICATION,
        self::CFDI_USE_OTHER_MACHINERY,
        self::CFDI_USE_TO_BE_DEFINED,
        self::CFDI_USE_MEDICAL_EXPENSES,
        self::CFDI_USE_MEDICAL_EXPENSES_BY_DISABILITY,
        self::CFDI_USE_FUNERAL_EXPENSES,
        self::CFDI_USE_DONATIONS,
        self::CFDI_USE_MORTGAGE_LOANS,
        self::CFDI_USE_SAR_CONTRIBUTIONS,
        self::CFDI_USE_MEDICAL_INSURANCE,
        self::CFDI_USE_SCHOOL_TRANSPORTATION_EXPENSES,
        self::CFDI_USE_PENSION_PLAN_DEPOSIT,
        self::CFDI_USE_EDUCATIONAL_SERVICE_PAYMENTS,
        self::CFDI_USE_WITHOUT_TAX_EFFECTS,
        self::CFDI_USE_PAYMENTS,
        self::CFDI_USE_PAYROLL,
    ];

    /**
     * @throws \InvalidArgumentException
     *
     * @return array The matching `CFDI_USE_*` constant for the given CFDI use name
     */
    #[\Override]
    public static function findByName(string $name): array
    {
        if (false === $index = array_search($name, array_column(self::CATALOG_CFDI_USE, 'name'), true)) {
            throw new \InvalidArgumentException(\sprintf('CFDI use with name "%s" was not found', $name));
        }

        return self::CATALOG_CFDI_USE[$index];
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return array The matching `CFDI_USE_*` constant for the given CFDI use value
     */
    #[\Override]
    public static function findByValue(string $value): array
    {
        if (false === $index = array_search($value, array_column(self::CATALOG_CFDI_USE, 'value'), true)) {
            throw new \InvalidArgumentException(\sprintf('CFDI use with value "%s" was not found', $value));
        }

        return self::CATALOG_CFDI_USE[$index];
    }
}
