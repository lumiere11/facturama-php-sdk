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
interface CatalogInterface
{
    /**
     * @throws InvalidArgumentException
     *
     * @return array The matching entity for the given name
     */
    public static function findByName(string $name): array;

    /**
     * @throws InvalidArgumentException
     *
     * @return array|\Generator The matching entity / entities for the given value
     */
    public static function findByValue(string $value): array|\Generator;
}
