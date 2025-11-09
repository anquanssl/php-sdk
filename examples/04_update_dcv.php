<?php

declare(strict_types=1);

use QuantumCA\Sdk\Requests\CertificateUpdateDcvRequest;

require __DIR__ . '/bootstrap.php';

$sdk = sdk();

$orderId = getenv('SERVICE_ID') ?: ($_SERVER['SERVICE_ID'] ?? '');
if (!$orderId) {
    fwrite(STDERR, "请设置环境变量 SERVICE_ID 用于重签\n");
}

// 示例域名（可按需替换或改为环境变量）
$domain = getenv('DOMAIN') ?: ($_SERVER['DOMAIN'] ?? 'www.example.org');

// 1) DNS 验证
try {
    $reqDns = new CertificateUpdateDcvRequest();
    $reqDns->service_id = $orderId;
    $reqDns->domain_dcv = [
        $domain => 'dns',
    ];
    $resultDns = $sdk->order->certificateUpdateDcv($reqDns);
    echo "DNS 类型 DCV 更新结果:" . PHP_EOL;
    println($resultDns);
} catch (Throwable $e) {
    fwrite(STDERR, '更新DNS DCV失败: ' . $e->getMessage() . PHP_EOL);
}

// 2) HTTP 验证
try {
    $reqHttp = new CertificateUpdateDcvRequest();
    $reqHttp->service_id = $orderId;
    $reqHttp->domain_dcv = [
        $domain => 'http',
    ];
    $resultHttp = $sdk->order->certificateUpdateDcv($reqHttp);
    echo "HTTP 类型 DCV 更新结果:" . PHP_EOL;
    println($resultHttp);
} catch (Throwable $e) {
    fwrite(STDERR, '更新HTTP DCV失败: ' . $e->getMessage() . PHP_EOL);
}

// 3) HTTPS 验证
try {
    $reqHttps = new CertificateUpdateDcvRequest();
    $reqHttps->service_id = $orderId;
    $reqHttps->domain_dcv = [
        $domain => 'https',
    ];
    $resultHttps = $sdk->order->certificateUpdateDcv($reqHttps);
    echo "HTTPS 类型 DCV 更新结果:" . PHP_EOL;
    println($resultHttps);
} catch (Throwable $e) {
    fwrite(STDERR, '更新HTTPS DCV失败: ' . $e->getMessage() . PHP_EOL);
}