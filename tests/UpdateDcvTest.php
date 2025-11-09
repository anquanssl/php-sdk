<?php

namespace QuantumCA\Sdk\Test;

use QuantumCA\Sdk\Requests\CertificateUpdateDcvRequest;

final class UpdateDcvTest extends TestCase
{
    public function testUpdateDcv()
    {
        $domain = 'www.example.org';
        $request = new CertificateUpdateDcvRequest();
        $request->service_id = $_SERVER['SERVICE_ID'];
        $request->domain = $domain;
        $request->type = 'email';
        $request->value = 'admin@' . $domain;
        $result = $this->sdk()->order->certificateUpdateDcv($request);

        $this->assertObjectHasAttribute($domain, $result);
    }
}
