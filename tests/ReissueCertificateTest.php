<?php

namespace QuantumCA\Sdk\Test;

use QuantumCA\Sdk\Requests\CertificateReissueRequest;

final class ReissueCertificateTest extends TestCase
{
    public function testReissue()
    {
        $domain = 'www.example.org';
        $request = new CertificateReissueRequest();
        $request->QuantumCA_id = $_SERVER['QuantumCA_ORDER_ID'];
        $request->csr = $this->csr();
        $request->period = 'Quarterly';
        $request->contact_email = 'test@example.org';
        $request->domain_dcv = [
            $domain => 'dns',
        ];
        $request->notify_url = 'https://partner.app/notify';
        try {
            $result = $this->sdk()->order->certificateReissue($request);
        } catch (\Exception $e) {

        }

        $this->assertObjectHasAttribute('QuantumCA_id', $result);
        $this->assertObjectHasAttribute('cost', $result);
        $this->assertObjectHasAttribute('status', $result);
        $this->assertObjectHasAttribute('dcv', $result);
        $this->assertObjectHasAttribute($domain, $result->dcv);
    }
}
