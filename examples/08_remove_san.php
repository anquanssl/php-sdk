<?php

declare(strict_types=1);

use QuantumCA\Sdk\Requests\CertificateRemoveSanRequest;

require __DIR__ . '/bootstrap.php';

$sdk = sdk();

$orderId = getenv('SERVICE_ID') ?: ($_SERVER['SERVICE_ID'] ?? '');
if (!$orderId) {
    fwrite(STDERR, "请设置环境变量 SERVICE_ID 用于添加SAN\n");
}

$req = new CertificateRemoveSanRequest();
$req->service_id = $orderId;
$req->domain = 'bad.example.org';

try {
    $result = $sdk->order->certificateRemoveSan($req);
    println($result);
} catch (Throwable $e) {
    fwrite(STDERR, '移除SAN失败: ' . $e->getMessage() . PHP_EOL);
}