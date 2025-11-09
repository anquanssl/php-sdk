<?php

use QuantumCA\Sdk\Requests\CertificateAddSanRequest;

require __DIR__ . '/bootstrap.php';

$sdk = sdk();

$orderId = getenv('SERVICE_ID') ?: (isset($_SERVER['SERVICE_ID']) ? $_SERVER['SERVICE_ID'] : '');
if (!$orderId) {
    fwrite(STDERR, "请设置环境变量 SERVICE_ID 用于添加SAN\n");
}

$req = new CertificateAddSanRequest();
$req->service_id = $orderId;
$req->san = 1; // 示例：添加 1 个域名槽位

try {
    $result = $sdk->order->certificateAddSan($req);
    println($result);
} catch (Exception $e) {
    fwrite(STDERR, '添加SAN失败: ' . $e->getMessage() . PHP_EOL);
}