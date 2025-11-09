[<p align="center"><img src="https://github.com/anquanssl/.github/raw/main/profile/logo_dark.svg?v=3" width="300"/></p>](https://www.anquanssl.com?__utm_from=github-org-profile#gh-dark-mode-only)
[<p align="center"><img src="https://github.com/anquanssl/.github/raw/main/profile/logo_light.svg?v=3" width="300"/></p>](https://www.anquanssl.com?__utm_from=github-org-profile#gh-light-mode-only)

## AnquanSSL

AnquanSSL, aka "Security SSL", also known as "安全 SSL" in Mandarin, founded in 2018, and our mission is providing affordable, secure, and enhanced TLS utilization experiences in the Greater China market.

这是 [安全SSL](https://www.anquanssl.com) 开放API的 PHP SDK.

[![Build Status](https://travis-ci.com/anquanssl/sdk.svg?branch=master)](https://travis-ci.com/anquanssl/sdk)

[获取](https://www.anquanssl.com/dashboard/api-credentials) `AccessKey` 秘钥对.

此SDK包仅面向开发者提供支持，若您是分销商，您可能需要:
- [AnquanSSL Module for WHMCS]()
- [AnquanSSL Module for HostBill]()
- [AnquanSSL Module for 宝塔(bt.cn)]()

如果您要其它编程语言的开发者，您可能需要
- [AnquanSSL PHP SDK](https://github.com/anquanssl/sdk)
- [AnquanSSL Python SDK](https://github.com/anquanssl/python-sdk)
- [AnquanSSL NodeJS SDK](https://github.com/anquanssl/nodejs-sdk)
- [AnquanSSL Golang SDK](https://github.com/anquanssl/golang-sdk)
- [AnquanSSL Java SDK](https://github.com/anquanssl/java-sdk)


## 安装

```bash
composer require quantumca/sdk -vvv
```

## 使用

```php
<?php

use QuantumCA\Sdk\Client;

require __DIR__ . '/../vendor/autoload.php';

$sdk = new Client('accessKeyId', 'accessKeySecret');
$result = $sdk->product->productList();
print($result->products);
```

## 快速开始

- 设置环境变量：
  - `ACCESS_KEY_ID`、`ACCESS_KEY_SECRET`：在控制台获取的秘钥对。
  - 可选 `API_ORIGIN`：API 地址，默认使用 SDK 内置地址。
  - 部分示例需要 `SERVICE_ID`：某个订单的编号。
- 执行示例脚本：
  - 列出产品与价格：`php examples/01_product_list.php`
  - 下单购买证书：`php examples/02_certificate_create.php`
  - 查询订单详情：`php examples/03_order_detail.php`
  - 更新域名 DCV：`php examples/04_update_dcv.php`
  - 提交检查 DCV：`php examples/05_validate_dcv.php`
  - 重签证书：`php examples/06_reissue_certificate.php`
  - 添加 SAN：`php examples/07_add_san.php`
  - 移除无法验证的域名：`php examples/08_remove_san.php`
  - 订单退款：`php examples/09_refund.php`

以上示例统一通过 `examples/bootstrap.php` 初始化客户端，示例内已包含最小参数与错误处理，便于对接和调试。

## 智能感知

我们的 SDK 将智能感知 Intellisense (VS Code、PHPStorm) 做为目标之一.
![Intellisense.png](https://user-images.githubusercontent.com/6964962/64444468-c5336700-d106-11e9-81aa-e660e72a1149.png)

## License

MIT
