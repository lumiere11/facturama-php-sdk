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
use Facturama\Exception\ModelException;
use Facturama\Exception\RequestException;
use Facturama\Exception\ResponseException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Offline tests: no network access required.
 */
class ClientTest extends TestCase
{
    public function testUserAgentMatchesVersion(): void
    {
        $this->assertSame('Facturama-PHP-SDK-v'.Client::VERSION, Client::USER_AGENT);
    }

    public function testGetDecodesJson(): void
    {
        $client = $this->createClient([new Response(200, [], '{"Name":"x"}')]);

        $this->assertSame('x', $client->get('catalogs')->Name);
    }

    public function testEmptyBodyReturnsNull(): void
    {
        $this->assertNull($this->createClient([new Response(200, [], '')])->get('catalogs'));
    }

    public function testErrorMessageIsExposed(): void
    {
        $client = $this->createClient([new Response(400, [], '{"Message":"boom"}')]);

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('boom');
        $client->post('cfdi', []);
    }

    public function testModelStateMessagesAreChained(): void
    {
        $body = '{"Message":"invalid","ModelState":{"a":["bad A"],"b":["bad B"]}}';
        $client = $this->createClient([new Response(400, [], $body)]);

        try {
            $client->post('cfdi', []);
            $this->fail('RequestException expected');
        } catch (RequestException $e) {
            $this->assertSame('invalid', $e->getMessage());
            $this->assertInstanceOf(ModelException::class, $e->getPrevious());
            $this->assertSame('bad A; bad B.', $e->getPrevious()->getMessage());
        }
    }

    public function testNonJsonErrorBodyIsUsedAsMessage(): void
    {
        $client = $this->createClient([new Response(500, [], 'Internal failure')]);

        $this->expectException(RequestException::class);
        $this->expectExceptionMessageMatches('/Internal failure|500/');
        $client->get('anything');
    }

    public function testInvalidJsonOnSuccessThrows(): void
    {
        $client = $this->createClient([new Response(200, [], '{not json')]);

        $this->expectException(ResponseException::class);
        $client->get('anything');
    }

    public function testRequestUsesBaseUriWithoutDoubleSlash(): void
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([new Response(200, [], '[]')]));
        $stack->push(Middleware::history($history));
        $client = new Client(null, null, [], new GuzzleClient(['handler' => $stack]));

        $client->get('catalogs/CfdiTypes', ['keyword' => 'x']);

        $this->assertSame('https://apisandbox.facturama.mx/catalogs/CfdiTypes?keyword=x', (string) $history[0]['request']->getUri());
    }

    public function testSetApiUrlChangesBaseUri(): void
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([new Response(200, [], '[]')]));
        $stack->push(Middleware::history($history));
        $client = new Client(null, null, [], new GuzzleClient(['handler' => $stack]));

        $this->assertSame($client, $client->setApiUrl('https://api.facturama.mx/'));
        $client->delete('cfdi/1');

        $this->assertSame('DELETE', $history[0]['request']->getMethod());
        $this->assertSame('https://api.facturama.mx/cfdi/1', (string) $history[0]['request']->getUri());
    }

    public function testPostSendsJsonBody(): void
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([new Response(200, [], '{}')]));
        $stack->push(Middleware::history($history));
        $client = new Client(null, null, [], new GuzzleClient(['handler' => $stack]));

        $client->post('cfdi', ['A' => 1]);

        $this->assertSame('{"A":1}', (string) $history[0]['request']->getBody());
    }

    public function testHttpClientAndRequestOptionsAreMutuallyExclusive(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Client(null, null, ['verify' => false], new GuzzleClient());
    }

    private function createClient(array $responses)
    {
        $http = new GuzzleClient(['handler' => HandlerStack::create(new MockHandler($responses))]);

        return new Client(null, null, [], $http);
    }
}
