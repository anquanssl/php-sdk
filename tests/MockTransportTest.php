<?php

namespace QuantumCA\Sdk\Test;

use PHPUnit\Framework\TestCase;
use QuantumCA\Sdk\Client;
use QuantumCA\Sdk\Requests\CertificateUpdateDcvRequest;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

final class MockTransportTest extends TestCase
{
    private function makeMockedClient(MockHandler $mock): Client
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

    public function testProductListParametersSigned()
    {
        $mock = new MockHandler([
            function (RequestInterface $request) {
                $query = [];
                parse_str($request->getUri()->getQuery() ?: '', $query);
                $payload = ['success' => true, 'data' => $query];
                return new Response(200, ['Content-Type' => 'application/json'], json_encode($payload));
            },
        ]);

        $sdk = $this->makeMockedClient($mock);
        $result = $sdk->product->productList();
        // 兼容返回为对象或 Scheme 的情况，统一转换为数组进行断言
        $result = is_array($result) ? $result : json_decode(json_encode($result), true);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('accessKeyId', $result);
        $this->assertArrayHasKey('nonce', $result);
        $this->assertArrayHasKey('timestamp', $result);
        $this->assertArrayHasKey('sign', $result);
    }

    public function testUpdateDcvDnsHttpHttpsParameters()
    {
        $reply = function (RequestInterface $request) {
            $data = json_decode((string) $request->getBody(), true) ?: [];
            $payload = ['success' => true, 'data' => $data];
            return new Response(200, ['Content-Type' => 'application/json'], json_encode($payload));
        };
        $mock = new MockHandler([$reply, $reply, $reply]);
        $sdk = $this->makeMockedClient($mock);
        $domain = 'www.example.org';

        // DNS
        $reqDns = new CertificateUpdateDcvRequest();
        $reqDns->service_id = 'ORDER123';
        $reqDns->domain_dcv = [$domain => 'dns'];
        $dns = $sdk->order->certificateUpdateDcv($reqDns);
        $dns = is_array($dns) ? $dns : json_decode(json_encode($dns), true);
        $this->assertArrayHasKey('service_id', $dns);
        $this->assertArrayHasKey('domain_dcv', $dns);
        $this->assertEquals('dns', $dns['domain_dcv'][$domain]);
        $this->assertArrayHasKey('accessKeyId', $dns);
        $this->assertArrayHasKey('sign', $dns);

        // HTTP
        $reqHttp = new CertificateUpdateDcvRequest();
        $reqHttp->service_id = 'ORDER123';
        $reqHttp->domain_dcv = [$domain => 'http'];
        $http = $sdk->order->certificateUpdateDcv($reqHttp);
        $http = is_array($http) ? $http : json_decode(json_encode($http), true);
        $this->assertEquals('http', $http['domain_dcv'][$domain]);
        $this->assertArrayHasKey('nonce', $http);
        $this->assertArrayHasKey('timestamp', $http);

        // HTTPS
        $reqHttps = new CertificateUpdateDcvRequest();
        $reqHttps->service_id = 'ORDER123';
        $reqHttps->domain_dcv = [$domain => 'https'];
        $https = $sdk->order->certificateUpdateDcv($reqHttps);
        $https = is_array($https) ? $https : json_decode(json_encode($https), true);
        $this->assertEquals('https', $https['domain_dcv'][$domain]);
    }
}