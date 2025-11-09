<?php

declare(strict_types=1);

use QuantumCA\Sdk\Requests\CertificateValidateDcvRequest;

require __DIR__ . '/bootstrap.php';

$sdk = sdk();

$orderId = getenv('SERVICE_ID') ?: ($_SERVER['SERVICE_ID'] ?? '');
if (!$orderId) {
    fwrite(STDERR, "请设置环境变量 SERVICE_ID 用于提交DCV验证\n");
}

$req = new CertificateValidateDcvRequest();
$req->service_id = $orderId;

try {
    $result = $sdk->order->certificateValidateDcv($req);
    println($result);
} catch (Throwable $e) {
    fwrite(STDERR, '提交DCV验证失败: ' . $e->getMessage() . PHP_EOL);
}