<?php

namespace QuantumCA\Sdk\Requests;

/**
 * 更改DCV接口请求
 *
 * @property string $service_id 必传,下单时返回的id
 * @property object|array<string,string>|array<string,"dns"|"http"|"https"> $domain_dcv 
 * @deprecated @property string $domain [不推荐]必传,域名 
 * @deprecated @property string $type [不推荐]必传,验证类型:dns/http/https
 */
class CertificateUpdateDcvRequest extends AbstractRequest
{
}
