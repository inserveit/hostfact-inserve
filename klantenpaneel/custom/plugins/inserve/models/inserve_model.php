<?php

namespace Inserve;

class Inserve_Model extends \Base_Model
{
	public $Error;
	public $Warning;
	public $Success;
    public $apiKey;
    public $baseUrl;

	public function __construct($baseUrl, $apiKey)
	{
	    $this->apiKey = $apiKey;
	    $this->baseUrl = $baseUrl;
		$this->Error = $this->Warning = $this->Success = array();
	}

    public function get($url) {
        return $this->request('get', $url);
    }

    public function post($url, $data) {
        return $this->request('post', $url, $data);
    }

	public function request($method, $url, $data=null)
	{
        $options = [
            CURLOPT_VERBOSE => false,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $this->baseUrl . $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTPHEADER => [
                'x-api-key: '.$this->apiKey
            ],
        ];

        if($method === 'post') {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $data;
        }

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $output = trim(curl_exec($ch));

        return json_decode($output, true);
    }
}
