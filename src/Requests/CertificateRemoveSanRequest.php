<?php

namespace QuantumCA\Sdk\Requests;

/**
 * 移除域名请求
 *
 * @property string $service_id 必传,下单时返回的id
 * @property string $domain 必传,域名
 */
class CertificateRemoveSanRequest extends AbstractRequest
{
    public $domain;
}
