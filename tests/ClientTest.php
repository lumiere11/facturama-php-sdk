<?php

/*
 * This file is part of Facturama PHP SDK.
 *
 * (c) Facturama <dev@facturama.com>
 *
 * This source file is subject to a MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Facturama\Tests;

use Facturama\Client;
use Facturama\Exception\RequestException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Offline tests: no network access required.
 */
class ClientTest extends TestCase
{
    private function createClient(array $responses)
    {
        $http = new GuzzleClient(['handler' => HandlerStack::create(new MockHandler($responses))]);

        return new Client(null, null, [], $http);
    }

    public function testUserAgentMatchesVersion()
    {
        $this->assertSame('Facturama-PHP-SDK-v'.Client::VERSION, Client::USER_AGENT);
    }

    public function testGetDecodesJson()
    {
        $client = $this->createClient([new Response(200, [], '{"Name":"x"}')]);

        $this->assertSame('x', $client->get('catalogs')->Name);
    }

    public function testEmptyBodyReturnsNull()
    {
        $this->assertNull($this->createClient([new Response(200, [], '')])->get('catalogs'));
    }

    public function testErrorMessageIsExposed()
    {
        $client = $this->createClient([new Response(400, [], '{"Message":"boom"}')]);

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('boom');
        $client->post('cfdi', []);
    }
}
