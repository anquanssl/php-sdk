<?php

use QuantumCA\Sdk\Requests\CertificateRefundRequest;

require __DIR__ . '/bootstrap.php';

$sdk = sdk();

$orderId = getenv('SERVICE_ID') ?: (isset($_SERVER['SERVICE_ID']) ? $_SERVER['SERVICE_ID'] : '');
if (!$orderId) {
    fwrite(STDERR, "请设置环境变量 SERVICE_ID 用于重签\n");
}

$req = new CertificateRefundRequest();
$req->service_id = $orderId;

try {
    $result = $sdk->order->certificateRefund($req);
    println($result);
} catch (Exception $e) {
    fwrite(STDERR, '退款失败: ' . $e->getMessage() . PHP_EOL);
}