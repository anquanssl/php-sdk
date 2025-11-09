<?php

use QuantumCA\Sdk\Client;

require __DIR__ . '/../vendor/autoload.php';

/**
 * 初始化 SDK 客户端
 */
function sdk()
{
    $accessKeyId = getenv('ACCESS_KEY_ID') ?: (isset($_SERVER['ACCESS_KEY_ID']) ? $_SERVER['ACCESS_KEY_ID'] : '');
    $accessKeySecret = getenv('ACCESS_KEY_SECRET') ?: (isset($_SERVER['ACCESS_KEY_SECRET']) ? $_SERVER['ACCESS_KEY_SECRET'] : '');
    $apiOrigin = getenv('API_ORIGIN') ?: (isset($_SERVER['API_ORIGIN']) ? $_SERVER['API_ORIGIN'] : null);

    if (!$accessKeyId || !$accessKeySecret) {
        fwrite(STDERR, "请在环境变量中设置 ACCESS_KEY_ID 与 ACCESS_KEY_SECRET\n");
    }

    return new Client($accessKeyId, $accessKeySecret, $apiOrigin);
}

/**
 * 示例 CSR（仅演示用途）
 */
function exampleCsr()
{
    return '-----BEGIN CERTIFICATE REQUEST-----' . PHP_EOL .
        'MIICuzCCAaMCAQAwSTELMAkGA1UEBhMCVVMxETAPBgNVBAgTCFByb3ZpbmNlMQ0w' . PHP_EOL .
        'CwYDVQQHEwRDaXR5MRgwFgYDVQQDEw93d3cuZXhhbXBsZS5vcmcwggEiMA0GCSqG' . PHP_EOL .
        'SIb3DQEBAQUAA4IBDwAwggEKAoIBAQDMak2BEAUbApEtjmuEEcpVw4Rkh8yzLdLV' . PHP_EOL .
        'ne03TFNnK3XyUHNqtaeyLG7qOplozvJazQgPt8yxKcmazM5gdZfaFVJxvIUzexK4' . PHP_EOL .
        'jvxV/8UFjkQ5B6X1SdN9OjEmvyXSY5toMf+KG3xCoCwExUyH6TTH4Iz2HgQ17iXz' . PHP_EOL .
        '6JsNhmhFCAe1A0NzITFXZ4YdsHUo2AUgf31VEdasrAdR8QEruc1h7+UAYRsu3Cgz' . PHP_EOL .
        '8lN7KZtlyKe5n1ZeROedLGiprhPPcd9LPhgcf1XWl9b9xFLUCamz0qAjeQNwGaOV' . PHP_EOL .
        'nHj9lhtZUWn22zqK/uDrPg/xPOJE0SZo1em3EACwu8iUhDV/07LzAgMBAAGgLTAr' . PHP_EOL .
        'BgkqhkiG9w0BCQ4xHjAcMBoGA1UdEQQTMBGCD3d3dy5leGFtcGxlLm9yZzANBgkq' . PHP_EOL .
        'hkiG9w0BAQsFAAOCAQEARWU4d6rVWUa4CSXhnGx/vh28p1QyJN8nitLF2+LcgbSc' . PHP_EOL .
        'ybDd2arAs06q1lZSb0+tOIod26j4OflkpgfINorJdaiCCrNwhF2/LoctD2ssodNz' . PHP_EOL .
        'lzL2dxi2I+bSQTcGA81Hb/CXWCDy8v/KfTwik8StGHX0/ThUBMgTYVVpBSKzQmPk' . PHP_EOL .
        'Ko/vyMU+CelxZIGDiRONITHDmxsLitwtYAX0/9tQqU949bex+pwdsXzC7IMr7423' . PHP_EOL .
        'ozsYq+Abjj3V4bDjXFfFHS5LB2hqnJjxIuR3h7Pa7u8BJsKujTuK9BxdNY9eyIYG' . PHP_EOL .
        'gpbG5CjfVgsu+tVYCcAwg3DhcaIcqtIM6CtVRh5JTQ==' . PHP_EOL .
        '-----END CERTIFICATE REQUEST-----';
}

/**
 * 简单打印工具
 */
function println($data)
{
    if (is_object($data) || is_array($data)) {
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL;
    } else {
        echo (string)$data . PHP_EOL;
    }
}