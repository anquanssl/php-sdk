<?php

namespace QuantumCA\Sdk\Requests;

/**
 * 更改DCV接口请求
 *
 * @property string $service_id 必传,下单时返回的id
 * @property object $domain_dcv 
 * @property string $domain [不推荐]必传,域名 
 * @property string $type [不推荐]必传,验证类型:dns/http/https/email
 * @property string $value [不推荐]当 type=email 时候必传,邮箱地址
 */
class CertificateUpdateDcvRequest extends AbstractRequest
{
}
