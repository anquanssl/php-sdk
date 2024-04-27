<?php

namespace QuantumCA\Sdk\Scheme;

/**
 * 订单详情格式
 *
 * @property float $cost 本订单的成本
 * @property integer $service_id 帝玺的订单号
 * @property string $tracker_url 跟单连接
 * @property string $status pending、valid、issued、cancelled
 * @property \QuantumCA\Sdk\Scheme\Certificate\DnsDCV[]|\QuantumCA\Sdk\Scheme\Certificate\EmailDCV[]|\QuantumCA\Sdk\Scheme\Certificate\HttpDCV[]|\QuantumCA\Sdk\Scheme\Certificate\HttpsDCV[] $dcv
 * @property string $issued_cert 签发的证书
 * @property string $issuer_cert 签发者证书
 */
class CertificateDetailScheme
{
}
