<?php

namespace QuantumCA\Sdk\Test;

use QuantumCA\Sdk\Requests\CertificateCreateRequest;

final class CreateCertificateTest extends TestCase
{
    public function testCreate()
    {
        $domain = 'testapi.staging.digital-sign.com.cn';
        $request = new CertificateCreateRequest();
        $request->product_id = 6;
        $request->period = 'Quarterly';
        $request->csr = $this->csr();
        $request->unique_id = uniqid();
        $request->product_id = 6;
        $request->period = 'Quarterly';
        $request->contact_email = 'xiaohui.lam@e.hexdata.cn';
        $request->domain_dcv = [
            $domain => 'dns',
        ];
        $request->notify_url = 'https://partner.app/notify';

        $result = $this->sdk()->order->certificateCreate($request);
        $this->assertObjectHasAttribute('QuantumCA_id', $result);
        $this->assertObjectHasAttribute('cost', $result);
        $this->assertObjectHasAttribute('status', $result);
        $this->assertObjectHasAttribute('dcv', $result);
        $this->assertObjectHasAttribute($domain, $result->dcv);
    }
}
