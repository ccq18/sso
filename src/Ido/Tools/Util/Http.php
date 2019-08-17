<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 2015/5/13
 * Time: 15:48
 */

namespace Ido\Tools\Util;

use GuzzleHttp\Client;

class Http
{
    protected $url = '';
    protected $sleepTime, $interval, $nowNum;
    protected $client = null;
    protected $baseUrl;

    public function __construct($baseUrl = '', $interval = PHP_INT_MAX, $sleep_time = 0)
    {
        $this->baseUrl = $baseUrl;
        $this->sleepTime = $sleep_time;
        $this->interval = $interval;
        $this->nowNum;
        $this->client = new Client();
    }

    public function getUrl()
    {
        return $this->url;
    }

    private function toUrl($url, $parameters = [])
    {
        if (!empty($this->baseUrl)) {
            $url = rtrim($this->baseUrl, '/') . '/' . ltrim($url, '/');
        }
        $info = parse_url($url);
        $params = isset($info['query']) ? $info['query'] : "";
        parse_str($params, $output);
        $parm_str = http_build_query(array_merge($output, $parameters));

        return $this->clearUrlParam($url) . (empty($parm_str) ? "" : '?' . $parm_str);
    }

    private function clearUrlParam($url)
    {
        if (strpos($url, '?') !== false) {
            $url = substr($url, 0, strpos($url, '?'));
        }

        return $url;
    }

    protected function waitOrDo()
    {
        $this->nowNum++;
        if ($this->nowNum % $this->interval == 0) {
            sleep($this->sleepTime);
        }
    }

    public function get($url, $params = [], $headers = [], $timeout = 10)
    {
        $this->waitOrDo();
        $this->url = $this->toUrl($url, $params);
        $options = [];
        if (!empty($headers)) {
            $options['headers'] = $headers;
        }
        if (!empty($timeout)) {
            $options['timeout'] = $timeout;
        }

        return $this->client->get($this->url, $options)->getBody()->getContents();

    }

    public function put($url, $data = [], $params = [], $headers = [], $timeout = 10)
    {

        $this->waitOrDo();
        $this->url = $this->toUrl($url, $params);
        $options = [];
        if (!empty($headers)) {
            $options['headers'] = $headers;
        }
        if (!empty($data)) {
            $options['form_params'] = $data;
        }
        if (!empty($timeout)) {
            $options['timeout'] = $timeout;
        }

        return $this->client->put($this->url, $options)->getBody()->getContents();
    }

    public function delete($url, $params = [], $headers = [], $timeout = 10)
    {
        $this->waitOrDo();
        $this->url = $this->toUrl($url, $params);
        $options = [];
        if (!empty($headers)) {
            $options['headers'] = $headers;
        }
        if (!empty($timeout)) {
            $options['timeout'] = $timeout;
        }

        return $this->client->delete($this->url, $options)->getBody()->getContents();
    }

    public function post($url, $data = [], $params = [], $headers = [], $timeout = 10)
    {
        $this->waitOrDo();
        $this->url = $this->toUrl($url, $params);
        $options = [];
        if (!empty($headers)) {
            $options['headers'] = $headers;
        }
        if (!empty($data)) {
            $options['form_params'] = $data;
        }
        if (!empty($timeout)) {
            $options['timeout'] = $timeout;
        }

        return $this->client->post($this->url, $options)->getBody()->getContents();
    }

    function getJson($url, $params = [], $headers = [], $timeout = 10)
    {
        try {
            return json_decode($this->get($url, $params, $headers, $timeout), true);
        } catch (\Exception $e) {
            return null;
        }

    }

    function postJson($url, $data = [], $params = [], $headers = [], $timeout = 10)
    {
        try {
            return json_decode($this->post($url, $data, $params, $headers, $timeout), true);
        } catch (\Exception $e) {
            return null;
        }

    }
}