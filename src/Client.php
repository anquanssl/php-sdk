<?php

namespace QuantumCA\Sdk;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use QuantumCA\Sdk\Exceptions\DoNotHavePrivilegeException;
use QuantumCA\Sdk\Exceptions\InsufficientBalanceException;
use QuantumCA\Sdk\Exceptions\RequestException;
use QuantumCA\Sdk\Resources\Order;
use QuantumCA\Sdk\Resources\Product;

use QuantumCA\Sdk\Traits\SignTrait;

/**
 * @method mixed get($uri, $parameters = [])
 * @method mixed post($uri, $parameters = [])
 */
class Client
{
    use SignTrait;

    const ORIGIN_API = 'https://api.orion.pki.plus/api/v1';

    const CODE_EXCEPTION_MAP = [
        'INSUFFICIENT_BALANCE' => InsufficientBalanceException::class,
        'DO_NOT_HAVE_RIVILEGE' => DoNotHavePrivilegeException::class,
    ];

    /**
     * @var Product
     */
    public $product;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var string
     */
    protected $accessKeyId;

    /**
     * @var string
     */
    protected $accessKeySecret;

    /**
     * @var string
     */
    protected $apiOrigin;

    /**
     * @var int
     */
    protected $connectTimeout;

    /**
     * @var int
     */
    protected $readTimeout;

    public function __construct($accessKeyId, $accessKeySecret, $apiOrigin = null, $connectTimeout = 5, $readTimeout = 15)
    {
        if ($apiOrigin === null) {
            $apiOrigin = self::ORIGIN_API;
        }

        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
        $this->apiOrigin = $apiOrigin;

        $this->product = new Product($this);
        $this->order = new Order($this);
        $this->connectTimeout = $connectTimeout;
        $this->readTimeout = $readTimeout;
        //$this->callback = new Callback($this);
    }

    /**
     * @param string|'post'|'get' $method
     * @param string $uri
     * @param array $query
     * @param array $body
     * 
     * @return mixed
     */
    protected function _http($method, $uri, $data)
    {
        if (class_exists(Http::class)) {
            /**
             * @var \Illuminate\Http\Client\Response $response
             */
            $response = Http::withoutVerifying()
                ->withoutRedirecting()
                ->timeout($this->connectTimeout)
                ->asJson()
                ->{$method}($uri, $data);
            if (!$response->successful()) {
            } else if (!$response->json('success')) {
                $exception_class = RequestException::class;
                $map = static::CODE_EXCEPTION_MAP;
                if (!$response->json('message')) {
                    throw new RequestException('未知错误', -1);
                }
                if (isset($map[$response->json('message')])) {
                    $exception_class = $map[$response->json('message')];
                }
                throw new $exception_class(!!$response->json('message') ? $response->json('message') : '请求接口出错', !!$response->json('message') ? $response->json('message') : -1);
            }

            $json = $response->object();
            return $json->data;
        } else {
            $http = new GuzzleHttpClient([
                RequestOptions::CONNECT_TIMEOUT => $this->connectTimeout,
                RequestOptions::READ_TIMEOUT => $this->readTimeout,
                RequestOptions::VERIFY => false,
                RequestOptions::ALLOW_REDIRECTS => false,
            ]);
            /**
             * @var \GuzzleHttp\Psr7\Response $response
             */
            $response = $http->{$method}($uri, [
                ($method == 'get' ? RequestOptions::QUERY : RequestOptions::JSON) => array_merge($query, $body),
            ]);

            $json = json_decode($response->getBody()->__toString());

            if (!isset($json->success) || !$json->success) {
                $exception_class = RequestException::class;
                $map = static::CODE_EXCEPTION_MAP;
                if (!isset($json->message)) {
                    throw new RequestException('未知错误', -1);
                }
                if (isset($map[$json->message])) {
                    $exception_class = $map[$json->message];
                }
                throw new $exception_class(isset($json->message) ? $json->message : '请求接口出错', isset($json->message) ? $json->message : -1);
            }
            return $json->data;
        }
    }

    /**
     * 魔术
     *
     * @param string $method GET、POST
     * @param array $arguments 第一个参数为API的路径，第二个参数为业务参数
     * @return \QuantumCA\Sdk\Response\Interfaces\BaseResponse
     */
    public function __call($method, $arguments = [])
    {
        try {
            $http = new GuzzleHttpClient([
                RequestOptions::CONNECT_TIMEOUT => $this->connectTimeout,
                RequestOptions::READ_TIMEOUT => $this->readTimeout,
                RequestOptions::VERIFY => false,
            ]);

            $api = $arguments[0];
            $resource = '/' . $api;
            $uri = $this->apiOrigin . $resource;
            $resource = parse_url($uri)['path'];

            $parameters = isset($arguments[1]) ? $arguments[1] : [];
            $parameters = $this->sign($resource, $parameters, $this->accessKeyId, $this->accessKeySecret);

            return $this->_http(strtolower($method), $uri, $parameters);
        } catch (ClientException $e) {
            // 若不存在 Laravel's ValidationException 类，或者版本太低没有 withMessages 方法，抛出Guzzle的异常
            if (!class_exists(ValidationException::class) || !method_exists(ValidationException::class, 'withMessages')) {
                throw $e;
            }

            $response = $e->getResponse();
            if ($response->getStatusCode() !== 422) {
                throw $e;
            }

            $data = json_decode($response->getBody()->__toString(), true);;

            if (JSON_ERROR_NONE !== json_last_error() || !isset($data['message'])) {
                throw new ClientException('JSON DECODE ERROR', $e->getRequest(), $e->getResponse(), $e);
            }

            throw ValidationException::withMessages($data['errors']);
        }
    }
}
