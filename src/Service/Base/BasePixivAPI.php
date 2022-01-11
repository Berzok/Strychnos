<?php

namespace App\Service\Base;

use Curl\Curl;
use Exception;

class BasePixivAPI {

    /**
     * @var string
     */
    protected string $oauth_url = 'https://oauth.secure.pixiv.net/auth/token';

    /**
     * @var array
     */
    protected array $headers = [
        'Authorization' => 'Bearer ',
        'X-Client-Time' => '2021-11-30T17:47:53+01:00',
        'App-OS' => 'android',
        'Accept-Language' => 'en-us',
        'App-OS-Version' => '9.0',
        'App-Version' => '5.0.234',
        'User-Agent' => 'PixivAndroidApp/5.0.234 (Android 11; Pixel 5)',
        'Content-Type' => 'application/x-www-form-urlencoded',
    ];

    /**
     * @var string
     */
    protected string $api_prefix = 'https://app-api.pixiv.net';

    /**
     * @var string
     */
    protected string $client_id = '';

    /**
     * @var string
     */
    protected string $client_secret = '';

    /**
     * @var string
     */
    protected string $hash_secret = '';

    /**
     * @var string|null
     */
    protected ?string $access_token = null;

    /**
     * @var string|null
     */
    protected ?string $refresh_token = null;


    /**
     * @throws Exception
     */
    public function __construct() {
        if (!in_array('curl', get_loaded_extensions())) {
            throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
        }
    }

    public function init(){
        $this->headers['Authorization'] .= getenv('PIXIV_BEARER');
        $this->client_id = getenv('PIXIV_CLIENT_ID');
        $this->client_secret = getenv('PIXIV_CLIENT_SECRET');
        $this->hash_secret = getenv('PIXIV_HASH_SECRET');
    }


    /**
     * @param $refreshToken
     * @return void
     */
    public function refreshAccessToken($refreshToken) : void {
        if ((!$this->access_token || !$this->refresh_token) && !$refreshToken) {
            return;
        }

        // Create map with request parameters
        $params = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'include_policy' => 'true',
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken ?? $this->getRefreshToken()
        );

        $headers = array_merge($this->headers, [
            'X-Client-Hash' => md5(date("c") . $this->hash_secret)
        ]);

        $curl = new Curl();
        // Build Http query using params

        $curl->setOpt(CURLOPT_CONNECTTIMEOUT, 10);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        $curl->setHeaders($headers);
        $curl->post($this->oauth_url, $params);
        $result = $curl->response;
        $curl->close();

        $auth_token = $result->response->access_token;
        // Server response is now stored in $result variable so you can process it

        $this->setAccessToken($auth_token);
    }


    /**
     * Access Token 取得する
     *
     * @return string|null
     */
    public function getAccessToken(): ?string {
        return $this->access_token;
    }

    /**
     * Access Token セット
     *
     * @param $access_token
     */
    public function setAccessToken($access_token) {
        $this->access_token = $access_token;
        $this->headers['Authorization'] = 'Bearer ' . $access_token;
    }

    /**
     * Refresh Token 取得する
     *
     * @return string|null
     */
    public function getRefreshToken(): ?string {
        return $this->refresh_token;
    }

    /**
     * Refresh Token セット
     *
     * @param $refresh_token
     */
    public function setRefreshToken($refresh_token) {
        $this->refresh_token = $refresh_token;
    }


    /**
     * ネットワーク要求
     *
     * @param $uri
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    protected function fetch($uri, array $options = array()) : mixed {
        $method = isset($options['method']) ? strtolower($options['method']) : 'get';
        if (!in_array($method, array('post', 'get', 'put', 'delete'))) {
            throw new Exception('HTTP Method is not allowed.');
        }
        $body = $options['body'] ?? array();
        $headers = $options['headers'] ?? array();
        $url = $this->api_prefix . $uri;
        foreach ($body as $key => $value) {
            if (is_bool($value)) {
                $body[$key] = ($value) ? 'true' : 'false';
            }
        }
        $curl = new Curl();
        $curl->setOpt(CURLOPT_CONNECTTIMEOUT, 10);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        $curl->setOpt(CURLINFO_HEADER_OUT, true);
        if (is_array($headers)) {
            foreach ($headers as $key => $value) {
                $curl->setHeader($key, $value);
            }
        }
        $curl->$method($url, $body);

        $result = $curl->response;
        $curl->close();
        $array = json_decode(json_encode($result), true);

        return $array;
    }
}