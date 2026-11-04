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

namespace Facturama;

use Facturama\Exception\ModelException;
use Facturama\Exception\RequestException;
use Facturama\Exception\ResponseException;
use Facturama\Resource\Cfdis;
use Facturama\Resource\Clients;
use Facturama\Resource\Products;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\RequestOptions;

/**
 * @author Javier Spagnoletti <phansys@gmail.com>
 */
class Client
{
    public const string VERSION = '3.0.0';
    /*
    https://apisandbox.facturama.mx //Pruebas desarrollo
    https://api.facturama.mx  //Produccion
    */
    public const string API_URL = 'https://apisandbox.facturama.mx/';
    public const string SANDBOX_URL = 'https://apisandbox.facturama.mx';
    public const string PRODUCTION_URL = 'https://api.facturama.mx';
    public const string USER_AGENT = 'Facturama-PHP-SDK-v'.self::VERSION;

    public const string FILE_TYPE_PDF = 'pdf';
    public const string FILE_TYPE_HTML = 'html';
    public const string FILE_TYPE_XML = 'xml';

    public const string RECEIPT_PAYROLL = 'payroll';
    public const string RECEIPT_RECEIVED = 'received';
    public const string RECEIPT_ISSUED = 'issued';

    public const string STATE_AGUASCALIENTES = 'AGUASCALIENTES';
    public const string STATE_BAJA_CALIFORNIA = 'BAJA CALIFORNIA';
    public const string STATE_BAJA_CALIFORNIA_SUR = 'BAJA CALIFORNIA SUR';
    public const string STATE_CAMPECHE = 'CAMPECHE';
    public const string STATE_CHIAPAS = 'CHIAPAS';
    public const string STATE_CHIHUAHUA = 'CHIHUAHUA';
    public const string STATE_COAHUILA = 'COAHUILA';
    public const string STATE_COLIMA = 'COLIMA';
    public const string STATE_DISTRITO_FEDERAL = 'DISTRITO FEDERAL';
    public const string STATE_CIUDAD_DE_MEXICO = 'CIUDAD DE MEXICO';
    public const string STATE_DURANGO = 'DURANGO';
    public const string STATE_GUANAJUATO = 'GUANAJUATO';
    public const string STATE_GUERRERO = 'GUERRERO';
    public const string STATE_HIDALGO = 'HIDALGO';
    public const string STATE_JALISCO = 'JALISCO';
    public const string STATE_ESTADO_DE_MEXICO = 'ESTADO DE MEXICO';
    public const string STATE_MICHOACAN_DE_OCAMPO = 'MICHOACAN DE OCAMPO';
    public const string STATE_MORELOS = 'MORELOS';
    public const string STATE_NAYARIT = 'NAYARIT';
    public const string STATE_NUEVO_LEON = 'NUEVO LEON';
    public const string STATE_OAXACA = 'OAXACA';
    public const string STATE_PUEBLA = 'PUEBLA';
    public const string STATE_QUERETARO = 'QUERETARO';
    public const string STATE_QUINTANA_ROO = 'QUINTANA ROO';
    public const string STATE_SAN_LUIS_POTOSI = 'SAN LUIS POTOSI';
    public const string STATE_SINALOA = 'SINALOA';
    public const string STATE_SONORA = 'SONORA';
    public const string STATE_TABASCO = 'TABASCO';
    public const string STATE_TAMAULIPAS = 'TAMAULIPAS';
    public const string STATE_TLAXCALA = 'TLAXCALA';
    public const string STATE_VERACRUZ_DE_IGNACIO_DE_LA_LLAVE = 'VERACRUZ DE IGNACIO DE LA LLAVE';
    public const string STATE_YUCATAN = 'YUCATAN';
    public const string STATE_ZACATECAS = 'ZACATECAS';

    private ClientInterface $client;

    private string $baseUri;

    public function __construct(?string $user = null, ?string $password = null, array $requestOptions = [], ?ClientInterface $httpClient = null)
    {
        if ($httpClient && $requestOptions) {
            throw new \InvalidArgumentException('If argument 3 is provided, argument 4 must be omitted or passed with `null` as value');
        }
        $requestOptions += [
            RequestOptions::HEADERS => ['User-Agent' => self::USER_AGENT],
            RequestOptions::AUTH => [$user, $password],
            RequestOptions::CONNECT_TIMEOUT => 10,
            RequestOptions::TIMEOUT => 60,
        ];
        $this->baseUri = rtrim(self::API_URL, '/');
        $this->client = $httpClient ?: new GuzzleClient($requestOptions);
    }

    public function setApiUrl(string $baseUri): self
    {
        $this->baseUri = rtrim($baseUri, '/');

        return $this;
    }

    /**
     * Whether this client targets the production API.
     */
    public function isProduction(): bool
    {
        return self::PRODUCTION_URL === $this->baseUri;
    }

    public function cfdis(): Cfdis
    {
        return new Cfdis($this);
    }

    public function clients(): Clients
    {
        return new Clients($this);
    }

    public function products(): Products
    {
        return new Products($this);
    }

    /**
     * Get Request
     */
    public function get(string $path, array $params = []): \stdClass|array|null
    {
        return $this->executeRequest('GET', $path, [RequestOptions::QUERY => $params]);
    }

    /**
     * POST Request
     *
     * @param array|null $body
     */
    public function post(string $path, array $body = [], ?array $params = null): \stdClass|array|null
    {
        return $this->executeRequest('POST', $path, [RequestOptions::JSON => $body, RequestOptions::QUERY => $params]);
    }

    /**
     * PUT Request
     */
    public function put(string $path, ?array $body = null, array $params = []): \stdClass|array|null
    {
        return $this->executeRequest('PUT', $path, [RequestOptions::JSON => $body, RequestOptions::QUERY => $params]);
    }

    /**
     * DELETE Request
     */
    public function delete(string $path, array $params = []): \stdClass|array|null
    {
        return $this->executeRequest('DELETE', $path, [RequestOptions::QUERY => $params]);
    }

    /**
     * Execute the request and return the resulting object
     *
     * @throws \RuntimeException|\LogicException
     *
     * @return \stdClass|array|null The decoded JSON representation using `json_decode()`
     */
    private function executeRequest(string $method, string $url, array $options = []): \stdClass|array|null
    {
        try {
            $response = $this->client->request($method, \sprintf('%s/%s', $this->baseUri, $url), $options);
            $content = trim($response->getBody()->getContents());
        } catch (GuzzleRequestException $e) {
            if ($e->hasResponse()) {
                $content = trim($e->getResponse()->getBody()->getContents());
                $object = $content ? json_decode($content) : null;
                if ($object && isset($object->Message)) {
                    $modelException = null;
                    if (isset($object->ModelState)) {
                        $modelExceptionMessages = [];
                        foreach ($object->ModelState as $invalidPropertyMessages) {
                            $modelExceptionMessages = array_merge($modelExceptionMessages, $invalidPropertyMessages);
                        }
                        $modelExceptionMessage = null;
                        if ($modelExceptionMessages) {
                            $modelExceptionMessage = implode('; ', $modelExceptionMessages).'.';
                        }
                        $modelException = new ModelException($modelExceptionMessage ?? '', $e->getCode());
                    }
                    throw new RequestException($object->Message, 0, $modelException);
                }

                throw new RequestException($content ?: $e->getMessage(), $e->getCode());
            }

            throw new RequestException($e->getMessage(), $e->getCode());
        }

        if ($content) {
            $object = json_decode($content);
            if (null === $object && \JSON_ERROR_NONE !== ($jsonLastError = json_last_error())) {
                throw new ResponseException(\sprintf('Response body could not be parsed since it doesn\'t contain a valid JSON structure, %s (%d): %s', json_last_error_msg(), $jsonLastError, $content));
            }

            return $object;
        }

        return null;
    }
}
