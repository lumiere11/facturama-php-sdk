<?php

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
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\RequestOptions;

/**
 * @author Javier Spagnoletti <phansys@gmail.com>
 */
class Client
{
    public const VERSION = '2.1.0';
    /*
    https://apisandbox.facturama.mx //Pruebas desarrollo
    https://api.facturama.mx  //Produccion
    */
    public const API_URL = 'https://apisandbox.facturama.mx/';
    public const USER_AGENT = 'Facturama-PHP-SDK-v'.self::VERSION;

    public const FILE_TYPE_PDF = 'pdf';
    public const FILE_TYPE_HTML = 'html';
    public const FILE_TYPE_XML = 'xml';

    public const RECEIPT_PAYROLL = 'payroll';
    public const RECEIPT_RECEIVED = 'received';
    public const RECEIPT_ISSUED = 'issued';

    public const STATE_AGUASCALIENTES = 'AGUASCALIENTES';
    public const STATE_BAJA_CALIFORNIA = 'BAJA CALIFORNIA';
    public const STATE_BAJA_CALIFORNIA_SUR = 'BAJA CALIFORNIA SUR';
    public const STATE_CAMPECHE = 'CAMPECHE';
    public const STATE_CHIAPAS = 'CHIAPAS';
    public const STATE_CHIHUAHUA = 'CHIHUAHUA';
    public const STATE_COAHUILA = 'COAHUILA';
    public const STATE_COLIMA = 'COLIMA';
    public const STATE_DISTRITO_FEDERAL = 'DISTRITO FEDERAL';
    public const STATE_CIUDAD_DE_MEXICO = 'CIUDAD DE MEXICO';
    public const STATE_DURANGO = 'DURANGO';
    public const STATE_GUANAJUATO = 'GUANAJUATO';
    public const STATE_GUERRERO = 'GUERRERO';
    public const STATE_HIDALGO = 'HIDALGO';
    public const STATE_JALISCO = 'JALISCO';
    public const STATE_ESTADO_DE_MEXICO = 'ESTADO DE MEXICO';
    public const STATE_MICHOACAN_DE_OCAMPO = 'MICHOACAN DE OCAMPO';
    public const STATE_MORELOS = 'MORELOS';
    public const STATE_NAYARIT = 'NAYARIT';
    public const STATE_NUEVO_LEON = 'NUEVO LEON';
    public const STATE_OAXACA = 'OAXACA';
    public const STATE_PUEBLA = 'PUEBLA';
    public const STATE_QUERETARO = 'QUERETARO';
    public const STATE_QUINTANA_ROO = 'QUINTANA ROO';
    public const STATE_SAN_LUIS_POTOSI = 'SAN LUIS POTOSI';
    public const STATE_SINALOA = 'SINALOA';
    public const STATE_SONORA = 'SONORA';
    public const STATE_TABASCO = 'TABASCO';
    public const STATE_TAMAULIPAS = 'TAMAULIPAS';
    public const STATE_TLAXCALA = 'TLAXCALA';
    public const STATE_VERACRUZ_DE_IGNACIO_DE_LA_LLAVE = 'VERACRUZ DE IGNACIO DE LA LLAVE';
    public const STATE_YUCATAN = 'YUCATAN';
    public const STATE_ZACATECAS = 'ZACATECAS';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @param string $user
     * @param string $password
     */
    public function __construct($user = null, $password = null, array $requestOptions = [], ?ClientInterface $httpClient = null)
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

    /**
     * @param string $baseUri
     *
     * @return Client
     */
    public function setApiUrl($baseUri)
    {
        $this->baseUri = rtrim($baseUri, '/');

        return $this;
    }

    /**
     * Get Request
     *
     * @param string $path
     *
     * @return \stdClass|array|null
     */
    public function get($path, array $params = [])
    {
        return $this->executeRequest('GET', $path, [RequestOptions::QUERY => $params]);
    }

    /**
     * POST Request
     *
     * @param string $path
     * @param array|null $body
     *
     * @return \stdClass|array|null
     */
    public function post($path, array $body = [], ?array $params = null)
    {
        return $this->executeRequest('POST', $path, [RequestOptions::JSON => $body, RequestOptions::QUERY => $params]);
    }

    /**
     * PUT Request
     *
     * @param string $path
     *
     * @return \stdClass|array|null
     */
    public function put($path, ?array $body = null, array $params = [])
    {
        return $this->executeRequest('PUT', $path, [RequestOptions::JSON => $body, RequestOptions::QUERY => $params]);
    }

    /**
     * DELETE Request
     *
     * @param string $path
     *
     * @return \stdClass|array|null
     */
    public function delete($path, array $params = [])
    {
        return $this->executeRequest('DELETE', $path, [RequestOptions::QUERY => $params]);
    }

    /**
     * Execute the request and return the resulting object
     *
     * @param string $method
     * @param string $url
     *
     * @throws \RuntimeException|\LogicException
     *
     * @return \stdClass|array|null The decoded JSON representation using `json_decode()`
     */
    private function executeRequest($method, $url, array $options = [])
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
                        $modelException = new ModelException($modelExceptionMessage, $e->getCode());
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
