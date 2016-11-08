<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient;

/**
 * Response
 * @property string $body
 * @property int $code
 * @property array $headers
 * @author Jan Pospisil
 */

class Response extends \Nette\Object {

	private $code;
	private $body;
	private $headers;
	private $execTime;

	public function __construct($code, $body, array $headers, $execTime) {
		$this->code = $code;
		$this->headers = $headers;
		$this->body = $body;
		$this->execTime = $execTime;
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
}
