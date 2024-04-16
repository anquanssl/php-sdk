[<p align="center"><img src="https://github.com/anquanssl/.github/raw/main/profile/logo_dark.png" width="600" height="85"/></p>](https://www.anquanssl.com?__utm_from=github-org-profile#gh-dark-mode-only)
[<p align="center"><img src="https://github.com/anquanssl/.github/raw/main/profile/logo_light.png" width="600" height="85"/></p>](https://www.anquanssl.com?__utm_from=github-org-profile#gh-light-mode-only)

## AnquanSSL

AnquanSSL, aka "Security SSL", also known as "安全 SSL" in Mandarin, founded in 2018, and our mission is providing affordable, secure, and enhanced TLS utilization experiences in the Greater China market.

这是 [安全SSL](https://www.anquanssl.com) 开放API的 PHP SDK.

[![Build Status](https://travis-ci.com/anquanssl/sdk.svg?branch=master)](https://travis-ci.com/anquanssl/sdk)

[获取](https://www.anquanssl.com/dashboard/api-credentials) `AccessKey` 秘钥对.

此SDK包仅面向开发者提供支持，若您是分销商，您可以需要:
- [AnquanSSL Module for WHMCS]()
- [AnquanSSL Module for HostBill]()
- [AnquanSSL Module for 宝塔(bt.cn)]()

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

## 智能感知

我们的 SDK 将智能感知 Intellisense (VS Code、PHPStorm) 做为目标之一.
![Intellisense.png](https://user-images.githubusercontent.com/6964962/64444468-c5336700-d106-11e9-81aa-e660e72a1149.png)

## License

MIT
