<?php

namespace QuantumCA\Sdk\Requests;

/**
 * 更改DCV接口请求
 *
 * @property string $service_id 必传,下单时返回的id
 * @property object|array<string,string>|array<string,"dns"|"http"|"https"> $domain_dcv 
 */
class CertificateUpdateDcvRequest extends AbstractRequest
{
    public $service_id;
    public $domain_dcv;
}
