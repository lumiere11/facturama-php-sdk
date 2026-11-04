<?php

/*
 * This file is part of Facturama PHP SDK.
 *
 * (c) Facturama <dev@facturama.com>
 *
 * This source file is subject to a MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Facturama\Resource;

use Facturama\Client;

/**
 * CFDI 4.0 operations of the Web API (single issuer).
 */
final class Cfdis extends AbstractResource
{
    public const TYPE_ISSUED = 'issued';
    public const TYPE_RECEIVED = 'received';
    public const TYPE_PAYROLL = 'payroll';

    /**
     * Creates (stamps) a CFDI 4.0.
     */
    public function create(array $cfdi): \stdClass|array|null
    {
        return $this->client->post('3/cfdis', $cfdi);
    }

    /**
     * Lists CFDIs. `$filters` accepts the API query parameters (`type`, `folioStart`, `rfc`, `page`, ...).
     */
    public function list(array $filters = []): \stdClass|array|null
    {
        return $this->client->get('Cfdi', $filters + ['type' => self::TYPE_ISSUED]);
    }

    /**
     * Downloads the CFDI as `pdf`, `html` or `xml` (see `Client::FILE_TYPE_*`). The API returns the content base64 encoded.
     */
    public function download(string $id, string $format = Client::FILE_TYPE_PDF, string $type = self::TYPE_ISSUED): \stdClass|array|null
    {
        return $this->client->get(\sprintf('cfdi/%s/%s/%s', $format, $type, $id));
    }

    public function sendByEmail(string $id, string $email, string $type = self::TYPE_ISSUED): \stdClass|array|null
    {
        return $this->client->post('Cfdi', [], ['cfdiType' => $type, 'cfdiId' => $id, 'email' => $email]);
    }

    /**
     * Cancels a CFDI. `$motive` is the SAT cancellation motive (`01` to `04`); `$substitutionUuid` is required for `01`.
     */
    public function cancel(string $id, string $motive, string $type = self::TYPE_ISSUED, ?string $substitutionUuid = null): \stdClass|array|null
    {
        $params = ['type' => $type, 'motive' => $motive];
        if (null !== $substitutionUuid) {
            $params['uuidReplacement'] = $substitutionUuid;
        }

        return $this->client->delete('Cfdi/'.$id, $params);
    }
}
