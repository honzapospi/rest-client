<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient;
use Nette\SmartObject;

/**
 * Response
 * @property string $body
 * @property int $code
 * @property array $headers
 * @property float execTime
 * @author Jan Pospisil
 */

class Response {
	use SmartObject;

	private $code;
	private $body;
	private $headers;
	private $execTime;
	private $source;

	public function __construct($code, $body, array $headers, $execTime, $source) {
		$this->code = $code;
		$this->headers = $headers;
		$this->body = $body;
		$this->execTime = $execTime;
		$this->source = $source;
	}

	public function getHeaders(){
		return $this->headers;
	}

	public function getHeader($name, $default = null){
		return isset($this->headers[$name]) ? $this->headers[$name] : $default;
	}

	public function getBody(){
		return $this->body;
	}

	public function getCode(){
		return $this->code;
	}

	public function getExecTime(){
		return $this->execTime;
	}

	public function getSource(){
		return $this->source;
	}
}
