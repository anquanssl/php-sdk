<?php

require __DIR__ . '/bootstrap.php';

$sdk = sdk();

try {
    $list = $sdk->product->productList();
    println($list);
} catch (Exception $e) {
    fwrite(STDERR, '获取产品列表失败: ' . $e->getMessage() . PHP_EOL);
}