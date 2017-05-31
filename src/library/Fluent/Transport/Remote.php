<?php
namespace Fluent\Transport;

class Remote implements \Fluent\Transport
{
    protected $_curl;
    
    protected $_key;
    
    protected $_secret;
    
    public static $endpoint = 'https://apps.5sq.io/fluent/service/v3';
    
    public static $debug = false;
    
    public function __construct($defaults, $endpoint = null)
    {
        $this->_curl = curl_init();
        $this->_key = $defaults['key'];
        $this->_secret = $defaults['secret'];
        if ($endpoint !== null) {
            self::$endpoint = $endpoint;
        }
    }
    
    public function send(\Fluent\Message $message)
    {
        $properties = $message->toArray();
        $params = array(
            'sender'      => $properties['sender'],
            'subject'     => $properties['subject'],
            'recipient'   => $properties['recipient'],
            'content'     => $properties['content'],
            'header'      => $properties['headers'],
            'attachment'  => $properties['attachments'],
            'option'      => $properties['options'],
        );
        
        $response = $this->_call('message', 'create', $params, self::$debug);
        return $response['_id'];
    }
    
    protected function _call($resource, $method, $params, $debug = false)
    {
        $this->_debug = $debug;
        
        $payload = http_build_query($params);
        
        $url = self::$endpoint . '/' . $resource;
        
        curl_setopt($this->_curl, CURLOPT_URL, $url);
        curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($this->_curl, CURLOPT_VERBOSE, $debug);
        curl_setopt($this->_curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_curl, CURLOPT_USERPWD, $this->_key . ':' . $this->_secret);
        
        switch ($method) {
            case 'create':
                curl_setopt($this->_curl, CURLOPT_POST, true);
                break;
            default:
                throw new \Fluent\Exception('Invalid method: ' . $method);
                break;
        }
        
        $start = microtime(true);
        $this->_log('Call to ' . $url . ': ' . $payload);
        if ($debug) {
            $curl_buffer = fopen('php://memory', 'w+');
            curl_setopt($this->_curl, CURLOPT_STDERR, $curl_buffer);
        }
        
        $response_body = curl_exec($this->_curl);
        $info = curl_getinfo($this->_curl);
        $time = microtime(true) - $start;
        if ($debug) {
            rewind($curl_buffer);
            $this->_log(stream_get_contents($curl_buffer));
            fclose($curl_buffer);
        }
        
        $this->_log('Completed in ' . number_format($time * 1000, 2) . 'ms');
        $this->_log('Got response: ' . $response_body);
        
        if(curl_error($this->_curl)) {
            throw new \Fluent\Exception("API call to " . $url . " failed: " . curl_error($this->_curl));
        }
        $result = json_decode($response_body, true);
        if ($result === null) {
            throw new \Fluent\Exception('We were unable to decode the JSON response from the Fluent API: ' . $response_body);
        }
        if(floor($info['http_code'] / 100) >= 4) {
            throw new \Fluent\Exception("{$info['http_code']}, " . $result['message']);
        }
        return $result;
    }
    
    protected function _log($msg) {
        if ($this->_debug) {
            error_log($msg);
        }
    }
}
