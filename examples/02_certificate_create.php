<?php

declare(strict_types=1);

use QuantumCA\Sdk\Requests\CertificateCreateRequest;

require __DIR__ . '/bootstrap.php';

$sdk = sdk();

$domain = 'www.example.org';
$req = new CertificateCreateRequest();
$req->unique_id = uniqid();
$req->product_id = 6; // 示例产品ID，实际请以 productList 返回为准
$req->period = 'Quarterly';
$req->csr = exampleCsr();
$req->contact_email = 'test@example.org';
$req->domain_dcv = [
    $domain => 'dns',
];
$req->notify_url = 'https://partner.app/notify';

try {
    $result = $sdk->order->certificateCreate($req);
    println($result);
} catch (Throwable $e) {
    fwrite(STDERR, '下单失败: ' . $e->getMessage() . PHP_EOL);
}