<?php

namespace QuantumCA\Sdk\Test;

use PHPUnit\Framework\TestCase;
use QuantumCA\Sdk\Client;
use QuantumCA\Sdk\Requests\CertificateCreateRequest;
use QuantumCA\Sdk\Requests\CertificateDetailRequest;
use QuantumCA\Sdk\Requests\CertificateUpdateDcvRequest;
use QuantumCA\Sdk\Requests\CertificateRemoveSanRequest;
use QuantumCA\Sdk\Requests\CertificateValidateDcvRequest;
use QuantumCA\Sdk\Requests\CertificateReissueRequest;
use QuantumCA\Sdk\Requests\CertificateRefundRequest;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

final class ApiDocsMockTest extends TestCase
{
    private function makeMockedClientWithRouter(callable $router): Client
    {
        $mock = new MockHandler([
            function (RequestInterface $request) use ($router) {
                return $router($request);
            },
        ]);
        $stack = HandlerStack::create($mock);
        $history = [];
        $stack->push(Middleware::history($history));
        $guzzle = new GuzzleClient([
            'handler' => $stack,
            'http_errors' => false,
        ]);

        $client = new Client('ak_test', 'sk_test', 'https://mock.local/api/v1');
        $client->setHttpClient($guzzle);
        return $client;
    }

    private function buildDnsDcv(string $subdomain, string $tld): array
    {
        return [
            'type' => 'dns',
            'subdomain' => $subdomain,
            'topleveldomain' => $tld,
            'status' => 'pending',
            'dns' => [
                'type' => 'CNAME',
                'hostname' => '_pki-validation.' . $subdomain,
                'fullname' => '_pki-validation.' . $subdomain . '.' . $tld,
                'value' => 'validation.pki.plus.',
            ],
        ];
    }

    private function buildHttpDcv(string $subdomain, string $tld, bool $https = false): array
    {
        $scheme = $https ? 'https' : 'http';
        return [
            'type' => $https ? 'https' : 'http',
            'subdomain' => $subdomain,
            'topleveldomain' => $tld,
            'status' => 'pending',
            $https ? 'https' : 'http' => [
                'filename' => 'pki-validation.txt',
                'filecontent' => 'abcdef123456',
                'filepath' => '/.well-known/pki-validation',
                'filefullpath' => '/.well-known/pki-validation/pki-validation.txt',
                'url' => $scheme . '://' . $subdomain . '.' . $tld . '/.well-known/pki-validation/pki-validation.txt',
            ],
        ];
    }

    private function buildEmailDcv(string $subdomain, string $tld): array
    {
        $domain = $subdomain . '.' . $tld;
        return [
            'type' => 'email',
            'subdomain' => $subdomain,
            'topleveldomain' => $tld,
            'status' => 'pending',
            'email' => [
                'address' => 'admin@' . $tld,
                'available' => [
                    'admin@' . $tld,
                    'administrator@' . $tld,
                    'hostmaster@' . $tld,
                    'postmaster@' . $tld,
                    'webmaster@' . $tld,
                ],
            ],
        ];
    }

    private function routeApi(RequestInterface $request): Response
    {
        $path = $request->getUri()->getPath();
        $json = function (array $data) {
            return new Response(200, ['Content-Type' => 'application/json'], json_encode($data));
        };

        $periods = ['quarterly', 'annually', 'biennially', 'triennially', 'quadrennially', 'quinquennially'];

        // 模拟各接口的真实结构（依据代码中的 Scheme 注释）
        if (preg_match('#/product/list$#', $path)) {
            $generatePricing = function () {
                $periods = ['Quarterly', 'Annually', 'Biennially', 'Triennially', 'Quadrennially', 'Quinquennially'];
                $pricing = [];
                $numPeriods = rand(1, count($periods));
                $selectedPeriods = array_rand(array_flip($periods), $numPeriods);
                if (!is_array($selectedPeriods)) {
                    $selectedPeriods = [$selectedPeriods];
                }
                foreach ($selectedPeriods as $period) {
                    $priceType = rand(1, 3);
                    switch ($priceType) {
                        case 1:
                            $pricing[$period] = rand(10, 1000);
                            break;
                        case 2:
                            $pricing[$period] = (float)rand(1000, 100000) / 100;
                            break;
                        case 3:
                            $pricing[$period] = (string)((float)rand(1000, 100000) / 100);
                            break;
                    }
                }
                return $pricing;
            };

            return $json([
                'success' => true,
                'data' => (object) [
                    'products' => [
                        (object)[
                            'id' => 'prod_1',
                            'name' => 'Product 1',
                            'pricing' => (object)[
                                'normal' => (object)$generatePricing(),
                                'wildcard' => (object)$generatePricing(),
                                'ip' => (object)$generatePricing(),
                                'ipv6' => (object)$generatePricing(),
                            ]
                        ]
                    ]
                ]
            ]);
        }

        if (preg_match('#/certificate/create$#', $path)) {
            $payload = json_decode((string) $request->getBody(), true) ?: [];
            $domainMap = isset($payload['domain_dcv']) && is_array($payload['domain_dcv']) ? $payload['domain_dcv'] : [];
            $dcvList = [];
            foreach ($domainMap as $domain => $type) {
                // 拆分子域和顶级域
                $parts = explode('.', $domain);
                $tld = implode('.', array_slice($parts, -2));
                $sub = implode('.', array_slice($parts, 0, -2)) ?: '@';
                switch ($type) {
                    case 'dns':
                        $dcvList[] = $this->buildDnsDcv($sub, $tld);
                        break;
                    case 'http':
                        $dcvList[] = $this->buildHttpDcv($sub, $tld, false);
                        break;
                    case 'https':
                        $dcvList[] = $this->buildHttpDcv($sub, $tld, true);
                        break;
                    case 'email':
                    default:
                        $dcvList[] = $this->buildEmailDcv($sub, $tld);
                        break;
                }
            }
            return $json([
                'success' => true,
                'data' => (object) [
                    'cost' => 299.0,
                    'service_id' => 'ORDER123',
                    'tracker_url' => 'https://tracker.pki.plus/orders/ORDER123',
                    'status' => 'pending',
                    'has_dcv' => true,
                    'dcv' => $dcvList,
                    'issued_cert' => null,
                    'issuer_cert' => null,
                    'period' => 'annually',
                ],
            ]);
        }

        if (preg_match('#/certificate/detail$#', $path) || preg_match('#/certificate/status$#', $path)) {
            // 查询订单状态
            return $json([
                'success' => true,
                'data' => (object) [
                    'cost' => 299.0,
                    'service_id' => 'ORDER123',
                    'tracker_url' => 'https://tracker.pki.plus/orders/ORDER123',
                    'status' => 'issued',
                    'has_dcv' => true,
                    'dcv' => [],
                    'issued_cert' => '-----BEGIN CERTIFICATE-----...-----END CERTIFICATE-----',
                    'issuer_cert' => '-----BEGIN CERTIFICATE-----...-----END CERTIFICATE-----',
                    'period' => 'annually',
                ],
            ]);
        }

        if (preg_match('#/certificate/update-dcv$#', $path)) {
            $payload = json_decode((string) $request->getBody(), true) ?: [];
            $domainMap = isset($payload['domain_dcv']) && is_array($payload['domain_dcv']) ? $payload['domain_dcv'] : [];
            $dcvMap = [];
            foreach ($domainMap as $domain => $type) {
                $parts = explode('.', $domain);
                $tld = implode('.', array_slice($parts, -2));
                $sub = implode('.', array_slice($parts, 0, -2)) ?: '@';
                if ($type === 'dns') {
                    $dcvMap[$domain] = $this->buildDnsDcv($sub, $tld);
                } elseif ($type === 'https') {
                    $dcvMap[$domain] = $this->buildHttpDcv($sub, $tld, true);
                } else {
                    $dcvMap[$domain] = $this->buildHttpDcv($sub, $tld, false);
                }
            }
            return $json([
                'success' => true,
                'data' => (object) $dcvMap,
            ]);
        }

        if (preg_match('#/certificate/remove-san$#', $path)) {
            // 移除无法验证域名 -> 返回退款状态结构
            return $json([
                'success' => true,
                'data' => (object) [
                    'service_id' => 'ORDER123',
                    'status' => 'refunded',
                ],
            ]);
        }

        if (preg_match('#/certificate/validate-dcv$#', $path)) {
            // 检查 DCV -> 返回订单详情结构
            return $json([
                'success' => true,
                'data' => (object) [
                    'cost' => 299.0,
                    'service_id' => 'ORDER123',
                    'tracker_url' => 'https://tracker.pki.plus/orders/ORDER123',
                    'status' => 'pending',
                    'has_dcv' => true,
                    'dcv' => [],
                    'issued_cert' => null,
                    'issuer_cert' => null,
                    'period' => 'annually',
                ],
            ]);
        }

        if (preg_match('#/certificate/reissue$#', $path)) {
            // 证书重签 -> 返回订单详情结构
            return $json([
                'success' => true,
                'data' => (object) [
                    'cost' => 0.0,
                    'service_id' => 'ORDER123',
                    'tracker_url' => 'https://tracker.pki.plus/orders/ORDER123',
                    'status' => 'pending',
                    'has_dcv' => false,
                    'dcv' => [],
                    'issued_cert' => null,
                    'issuer_cert' => null,
                    'period' => 'annually',
                ],
            ]);
        }

        if (preg_match('#/certificate/refund$#', $path)) {
            // 证书退款 -> 返回退款结构
            return $json([
                'success' => true,
                'data' => (object) [
                    'service_id' => 'ORDER123',
                    'status' => 'refunded',
                ],
            ]);
        }

        // 默认回退
        return $json([
            'success' => false,
            'message' => 'unknown api',
            'code' => 404,
        ]);
    }

    public function testProductList_ResponseShape()
    {
        $sdk = $this->makeMockedClientWithRouter([$this, 'routeApi']);
        $result = $sdk->product->productList();
        $result = json_decode(json_encode($result), true);

        $this->assertArrayHasKey('products', $result);
        $this->assertIsArray($result['products']);
        $first = $result['products'][0];
        $this->assertArrayHasKey('id', $first);
        $this->assertArrayHasKey('name', $first);
        $this->assertArrayHasKey('pricing', $first);
        $this->assertArrayHasKey('normal', $first['pricing']);
        $this->assertArrayHasKey('wildcard', $first['pricing']);
        $this->assertArrayHasKey('ip', $first['pricing']);
        $this->assertArrayHasKey('ipv6', $first['pricing']);

        $normalPricing = $first['pricing']['normal'];
        $this->assertIsArray($normalPricing);
        if (!empty($normalPricing)) {
            $firstPeriod = key(array_slice($normalPricing, 0, 1));
            $this->assertIsString($firstPeriod);
            $this->assertTrue(is_numeric($normalPricing[$firstPeriod]));
        }
    }

    public function testCertificateSubmit_ResponseShape()
    {
        $sdk = $this->makeMockedClientWithRouter([$this, 'routeApi']);
        $req = new CertificateCreateRequest();
        $req->product_id = 1001;
        $req->period = 'Annually';
        $req->csr = '-----BEGIN CERTIFICATE REQUEST-----...';
        $req->domain_dcv = [
            'www.example.com' => 'dns',
            'api.example.com' => 'http',
        ];
        $detail = $sdk->order->certificateCreate($req);
        $detail = json_decode(json_encode($detail), true);
        $this->assertArrayHasKey('service_id', $detail);
        $this->assertArrayHasKey('status', $detail);
        $this->assertArrayHasKey('dcv', $detail);
        $this->assertIsArray($detail['dcv']);
        $this->assertArrayHasKey('type', $detail['dcv'][0]);
    }

    public function testCertificateStatus_ResponseShape()
    {
        $sdk = $this->makeMockedClientWithRouter([$this, 'routeApi']);
        $req = new CertificateDetailRequest();
        $req->service_id = 'ORDER123';
        $detail = $sdk->order->certificateDetail($req);
        $detail = json_decode(json_encode($detail), true);
        $this->assertEquals('issued', $detail['status']);
        $this->assertArrayHasKey('issued_cert', $detail);
    }

    public function testUpdateDcv_ResponseShape()
    {
        $sdk = $this->makeMockedClientWithRouter([$this, 'routeApi']);
        $req = new CertificateUpdateDcvRequest();
        $req->service_id = 'ORDER123';
        $req->domain_dcv = [
            'www.example.com' => 'dns',
            'api.example.com' => 'https',
        ];
        $dcv = $sdk->order->certificateUpdateDcv($req);
        $dcv = json_decode(json_encode($dcv), true);
        $this->assertArrayHasKey('www.example.com', $dcv);
        $this->assertEquals('dns', $dcv['www.example.com']['type']);
        $this->assertEquals('https', $dcv['api.example.com']['type']);
    }

    public function testRemoveSan_ResponseShape()
    {
        $sdk = $this->makeMockedClientWithRouter([$this, 'routeApi']);
        $req = new CertificateRemoveSanRequest();
        $req->service_id = 'ORDER123';
        $req->domain = 'bad.example.com';
        $refund = $sdk->order->certificateRemoveSan($req);
        $refund = json_decode(json_encode($refund), true);
        $this->assertArrayHasKey('service_id', $refund);
        $this->assertEquals('refunded', $refund['status']);
    }

    public function testValidateDcv_ResponseShape()
    {
        $sdk = $this->makeMockedClientWithRouter([$this, 'routeApi']);
        $req = new CertificateValidateDcvRequest();
        $req->service_id = 'ORDER123';
        $detail = $sdk->order->certificateValidateDcv($req);
        $detail = json_decode(json_encode($detail), true);
        $this->assertArrayHasKey('has_dcv', $detail);
        $this->assertEquals('annually', $detail['period']);
    }

    public function testReissue_ResponseShape()
    {
        $sdk = $this->makeMockedClientWithRouter([$this, 'routeApi']);
        $req = new CertificateReissueRequest();
        $req->service_id = 'ORDER123';
        $req->csr = '-----BEGIN CERTIFICATE REQUEST-----...';
        $detail = $sdk->order->certificateReissue($req);
        $detail = json_decode(json_encode($detail), true);
        $this->assertArrayHasKey('service_id', $detail);
        $this->assertArrayHasKey('status', $detail);
    }

    public function testRefund_ResponseShape()
    {
        $sdk = $this->makeMockedClientWithRouter([$this, 'routeApi']);
        $req = new CertificateRefundRequest();
        $req->service_id = 'ORDER123';
        $refund = $sdk->order->certificateRefund($req);
        $refund = json_decode(json_encode($refund), true);
        $this->assertArrayHasKey('service_id', $refund);
        $this->assertEquals('refunded', $refund['status']);
    }
}