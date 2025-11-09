<?php

declare(strict_types=1);

use QuantumCA\Sdk\Requests\CertificateReissueRequest;

require __DIR__ . '/bootstrap.php';

$sdk = sdk();

$orderId = getenv('SERVICE_ID') ?: ($_SERVER['SERVICE_ID'] ?? '');
if (!$orderId) {
    fwrite(STDERR, "请设置环境变量 SERVICE_ID 用于重签\n");
}

$domain = 'www.example.org';
$req = new CertificateReissueRequest();
$req->service_id = $orderId;
$req->csr = exampleCsr();
$req->period = 'Quarterly';
$req->contact_email = 'test@example.org';
$req->domain_dcv = [
    $domain => 'dns',
];
$req->notify_url = 'https://partner.app/notify';

try {
    $result = $sdk->order->certificateReissue($req);
    println($result);
} catch (Throwable $e) {
    fwrite(STDERR, '重签失败: ' . $e->getMessage() . PHP_EOL);
}