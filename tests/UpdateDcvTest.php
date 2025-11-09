<?php

namespace QuantumCA\Sdk\Test;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use QuantumCA\Sdk\Requests\CertificateUpdateDcvRequest;

final class UpdateDcvTest extends TestCase
{
    public function testUpdateDcv()
    {
        $domain = 'www.example.org';
        $request = new CertificateUpdateDcvRequest();
        $request->service_id = $_SERVER['SERVICE_ID'];
        $request->domain_dcv = [
            $domain => 'dns',
        ];

        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'success' => true,
                'data' => [
                    'service_id' => 123,
                    'cost' => '0.00',
                    'status' => 'pending',
                    'dcv' => (object)[
                        'www.example.org' => (object)[]
                    ]
                ]
            ]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handlerStack]);

        $sdk = $this->sdk();
        $sdk->setHttpClient($httpClient);

        $result = $sdk->order->certificateUpdateDcv($request);

        $this->assertObjectHasProperty('dcv', $result);
        $this->assertObjectHasProperty($domain, $result->dcv);
    }
}
