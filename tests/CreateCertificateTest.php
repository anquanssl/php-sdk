<?php

namespace QuantumCA\Sdk\Test;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use QuantumCA\Sdk\Requests\CertificateCreateRequest;

final class CreateCertificateTest extends TestCase
{
    public function testCreate()
    {
        $domain = 'www.example.org';
        $request = new CertificateCreateRequest();
        $request->product_id = 1;
        $request->period = 'Quarterly';
        $request->csr = $this->csr();
        $request->contact_email = 'test@example.org';
        $request->domain_dcv = [
            $domain => 'dns',
        ];
        $request->notify_url = 'https://partner.app/notify';

        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'success' => true,
                'data' => [
                    'service_id' => 123,
                    'cost' => '0.00',
                    'status' => 'pending',
                    'dcv' => (object)[
                        $domain => (object)[]
                    ]
                ]
            ]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handlerStack]);

        $sdk = $this->sdk();
        $sdk->setHttpClient($httpClient);

        $result = $sdk->order->certificateCreate($request);

        $this->assertObjectHasProperty('service_id', $result);
        $this->assertObjectHasProperty('cost', $result);
        $this->assertObjectHasProperty('status', $result);
        $this->assertObjectHasProperty('dcv', $result);
        $this->assertObjectHasProperty($domain, $result->dcv);
    }
}
