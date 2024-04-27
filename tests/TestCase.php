<?php

namespace QuantumCA\Sdk\Test;

use QuantumCA\Sdk\Client;
use PHPUnit\Framework\TestCase as AbstractTestCase;

abstract class TestCase extends AbstractTestCase
{
    /**
     * 获取 SDK 实例
     *
     * @return \QuantumCA\Sdk\Client
     */
    public function sdk()
    {
        $access_key_id = $_SERVER['QuantumCA_ACCESS_KEY_ID'];
        $access_key_secret = $_SERVER['QuantumCA_ACCESS_KEY_SECRET'];
        $api_origin = $_SERVER['QuantumCA_API_ORIGIN'];

        $sdk = new Client($access_key_id, $access_key_secret, $api_origin);
        return $sdk;
    }

    /**
     * 获取 CSR
     *
     * @return string
     */
    public function csr()
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
}