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

namespace Facturama\Tests;

use Facturama\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * Offline tests: the resources only build the right HTTP requests.
 */
final class ResourcesTest extends TestCase
{
    /**
     * @var list<array{request: RequestInterface}>
     */
    private array $history = [];

    public function testCfdiCreatePostsTo3Cfdis(): void
    {
        $result = $this->client()->cfdis()->create(['CfdiType' => 'I']);

        $this->assertSame('x', $result->Id);
        $this->assertSame('POST', $this->lastRequest()->getMethod());
        $this->assertSame('/3/cfdis', $this->lastRequest()->getUri()->getPath());
        $this->assertSame('{"CfdiType":"I"}', (string) $this->lastRequest()->getBody());
    }

    public function testCfdiListDefaultsToIssued(): void
    {
        $this->client()->cfdis()->list(['page' => 2]);

        $this->assertSame('/Cfdi', $this->lastRequest()->getUri()->getPath());
        parse_str($this->lastRequest()->getUri()->getQuery(), $query);
        $this->assertSame(['page' => '2', 'type' => 'issued'], $query);
    }

    public function testCfdiDownload(): void
    {
        $this->client()->cfdis()->download('abc', Client::FILE_TYPE_XML, 'received');

        $this->assertSame('/cfdi/xml/received/abc', $this->lastRequest()->getUri()->getPath());
    }

    public function testCfdiSendByEmail(): void
    {
        $this->client()->cfdis()->sendByEmail('abc', 'a@b.mx');

        $this->assertSame('POST', $this->lastRequest()->getMethod());
        parse_str($this->lastRequest()->getUri()->getQuery(), $query);
        $this->assertSame(['cfdiType' => 'issued', 'cfdiId' => 'abc', 'email' => 'a@b.mx'], $query);
    }

    public function testCfdiCancelWithSubstitution(): void
    {
        $this->client()->cfdis()->cancel('abc', '01', 'issued', 'UUID-1');

        $this->assertSame('DELETE', $this->lastRequest()->getMethod());
        $this->assertSame('/Cfdi/abc', $this->lastRequest()->getUri()->getPath());
        parse_str($this->lastRequest()->getUri()->getQuery(), $query);
        $this->assertSame(['type' => 'issued', 'motive' => '01', 'uuidReplacement' => 'UUID-1'], $query);
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function crudProvider(): array
    {
        return [
            'clients' => ['clients', 'Clients', 'Client'],
            'products' => ['products', 'Products', 'Product'],
        ];
    }

    #[DataProvider('crudProvider')]
    public function testCrudRequests(string $accessor, string $collection, string $item): void
    {
        foreach ([
            ['list', [], 'GET', '/'.$collection],
            ['find', ['1'], 'GET', '/'.$item.'/1'],
            ['create', [['Name' => 'n']], 'POST', '/'.$item],
            ['update', ['1', ['Name' => 'n']], 'PUT', '/'.$item.'/1'],
            ['delete', ['1'], 'DELETE', '/'.$item.'/1'],
        ] as [$method, $args, $httpMethod, $path]) {
            $this->history = [];
            $resource = $this->client()->$accessor();
            $resource->$method(...$args);

            $this->assertSame($httpMethod, $this->lastRequest()->getMethod(), $method);
            $this->assertSame($path, $this->lastRequest()->getUri()->getPath(), $method);
        }
    }

    public function testIsProduction(): void
    {
        $client = new Client();
        $this->assertFalse($client->isProduction());
        $this->assertTrue($client->setApiUrl(Client::PRODUCTION_URL.'/')->isProduction());
    }

    private function client(): Client
    {
        $stack = HandlerStack::create(new MockHandler([new Response(200, [], '{"Id":"x"}')]));
        $stack->push(Middleware::history($this->history));

        return new Client('u', 'p', [], new GuzzleClient(['handler' => $stack]));
    }

    private function lastRequest(): RequestInterface
    {
        return $this->history[0]['request'];
    }
}
