<?php
namespace Fluent;

class Api 
{
    protected $_curl;
    
    protected $_key;
    
    protected $_secret;
    
    public static $endpoint = 'https://fluent.clickapp.co.za/v3';
    
    public static $debug = false;
    
    public function __construct($key, $secret, $endpoint = null)
    {
        $this->_curl = curl_init();
        $this->_key = $key;
        $this->_secret = $secret;
        if ($endpoint !== null) {
            self::$endpoint = $endpoint;
        }
    }
    
    public function call($resource, $method, $params)
    {
        $payload = http_build_query($params);
        
        $url = self::$endpoint . '/' . $resource;
        
        curl_setopt($this->_curl, CURLOPT_VERBOSE, self::$debug);
        curl_setopt($this->_curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_curl, CURLOPT_USERPWD, $this->_key . ':' . $this->_secret);
        curl_setopt($this->_curl, CURLOPT_USERAGENT, 'Fluent-Library-PHP-v' . \Fluent::VERSION);
        
        switch ($method) {
            case 'create':
                curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($this->_curl, CURLOPT_POST, true);
                break;
            case 'get':
                $url .= '/' . $params['id'];
                break;
            case 'index':
                $url .= ($payload) ? '?' . $payload : null;
                break;
            default:
                if (!array_key_exists('id', $params)) {
                    throw new Exception('id missing from REST RPC call');
                }
                $url .= '/' . $params['id'] . '/' . $method;
                break;
        }

        curl_setopt($this->_curl, CURLOPT_URL, $url);
        
        $start = microtime(true);
        $this->_log('Call to ' . $url . ': ' . $payload);
        if (self::$debug) {
            $curl_buffer = fopen('php://memory', 'w+');
            curl_setopt($this->_curl, CURLOPT_STDERR, $curl_buffer);
        }
        
        $response_body = curl_exec($this->_curl);
        $info = curl_getinfo($this->_curl);
        $time = microtime(true) - $start;
        if (self::$debug) {
            rewind($curl_buffer);
            $this->_log(stream_get_contents($curl_buffer));
            fclose($curl_buffer);
        }
        
        $this->_log('Completed in ' . number_format($time * 1000, 2) . 'ms');
        $this->_log('Got response: ' . $response_body);
        
        if(curl_error($this->_curl)) {
            throw new \Fluent\Exception("API call to " . $url . " failed: " . curl_error($this->_curl));
        }

        $result = json_decode($response_body);

        if ($result === null) {
            throw new \Fluent\Exception('We were unable to decode the JSON response from the Fluent API: ' . $response_body);
        }

        if (floor($info['http_code'] / 100) >= 4) {
            throw new \Fluent\Exception("{$info['http_code']}, " . $result->message);
        }

        return $result;
    }
    
    protected function _log($msg) {
        if (self::$debug) {
            error_log($msg);
        }
    }
}
