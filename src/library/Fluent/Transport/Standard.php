<?php
namespace Fluent\Transport;

require_once 'Fluent/Transport.php';
require_once 'Fluent/Exception.php';
require_once 'Fluent.php';

class Standard implements \Fluent\Transport
{
    protected $_curl;
    
    protected $_debug;
    
    protected $_key;
    
    public static $url = 'https://jifno.clickapp.co.za/v2';
    
    public function __construct($key = null)
    {
        $this->_curl = curl_init();
        $this->_key;
    }
    
    public function send(\Fluent\Message $message)
    {
        $properties = $message->toArray();
        $params = array(
            'sender'      => $properties['sender'],
            'subject'     => $properties['subject'],
            'recipients'  => array($properties['recipient']),
            'content'     => $properties['content'],
            'attachments' => $properties['attachments'],
        );
        
        $response = $this->_call('message', 'create', json_encode($params));
        return $response['_id'];
    }
    
    protected function _call($resource, $method, $params, $debug = false)
    {
        $this->_debug = $debug;
        
        $profile = array(
            'theme'     => \Fluent::getDefault('theme'),
            'logo'      => \Fluent::getDefault('logo'),
            'color'     => \Fluent::getDefault('color'),
            'teaser'    => \Fluent::getDefault('teaser'),
            'footer'    => \Fluent::getDefault('footer')
        );
        
        $payload =  '{"key": "' . \Fluent::getDefault('key', $this->_key) . '", ';
        $payload .= '"profile": ' . json_encode($profile) . ', '; 
        $payload .= '"message": ' . $params . '}'; 
        
        $url = self::$url . '/' . $resource;
        
        curl_setopt($this->_curl, CURLOPT_URL, $url);
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($this->_curl, CURLOPT_VERBOSE, $debug);
        curl_setopt($this->_curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);
        
        switch ($method) {
            case 'create':
                curl_setopt($this->_curl, CURLOPT_POST, true);
                break;
            default:
                throw new \Fluent\Exception('Invalid method: ' . $method);
                break;
        }
        
        $start = microtime(true);
        $this->_log('Call to ' . $url . ': ' . $params);
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
            throw new \Fluent\Exception("{$info['http_code']}, " . $result['error']);
        }
        return $result;
    }
    
    protected function _log($msg) {
        if ($this->_debug) {
            error_log($msg);
        }
    }
}
