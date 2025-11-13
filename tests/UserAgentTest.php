<?php

namespace QuantumCA\Sdk\Test;

use PHPUnit\Framework\TestCase;
use QuantumCA\Sdk\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

final class UserAgentTest extends TestCase
{
    private function makeMockedClient($mock)
    {
        $stack = HandlerStack::create($mock);
        $history = [];
        $stack->push(Middleware::history($history));
        $guzzle = new GuzzleClient([
            'handler' => $stack,
            'http_errors' => false,
        ]);

        $client = new Client('ak_test', 'sk_test', 'https://mock.local/api/v1');
        $client->setHttpClient($guzzle);
        return $client;
    }

    public function testInjectedGuzzleCarriesUserAgentHeader()
    {
        $expectedUa = 'QuantumCA-PHP-SDK/TEST-UA';
        $mock = new MockHandler([
            function (RequestInterface $request) use ($expectedUa) {
                $ua = $request->getHeaderLine('User-Agent');
                $payload = ['success' => true, 'data' => ['ua' => $ua]];
                return new Response(200, ['Content-Type' => 'application/json'], json_encode($payload));
            },
        ]);

        $sdk = $this->makeMockedClient($mock);
        $sdk->setUserAgent($expectedUa);

        $result = $sdk->product->productList();
        $result = is_array($result) ? $result : json_decode(json_encode($result), true);
        $this->assertEquals($expectedUa, $result['ua']);
    }
}

