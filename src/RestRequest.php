<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient;
use Nette\SmartObject;

/**
 * HttpRequest
 * @property $endpoint string
 * @property $params array
 * @property $method string
 * @property $headers array
 * @author Jan Pospisil
 */

class RestRequest {
	use SmartObject;

	private $endpoint;
	private $params;
	private $method;
	private $headers;

	public function __construct($endpoint, array $params = array(), $method = Rest::GET, array $headers = array()) {
		$this->endpoint = $endpoint;
		$this->params = $params;
		$this->method = $method;
		$this->headers = $headers;
		if($endpoint[0] != '/')
			throw new RestException('Endpoint must start with /');
	}

	public function getEndpoint(){
		return $this->endpoint;
	}

	public function getParams(){
		return $this->params;
	}

	public function getMethod(){
		return $this->method;
	}

	public function getHeaders(){
		return $this->headers;
	}

}

class RestException extends \Exception {

}
