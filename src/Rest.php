<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient;
use JP\RestClient\Exceptions\AuthenticationException;
use JP\RestClient\Exceptions\RedirectionException;
use JP\RestClient\Exceptions\ResponseStatusException;
use Teze\Rest\Client\JsonParser;

/**
 * Rest
 * @author Jan Pospisil
 */

class Rest extends \Nette\Object {

	const POST = 'POST';
	const GET = 'GET';
	const DELETE = 'DELETE';
	const PUT = 'PUT';
	const OPTIONS = 'OPTIONS';

	private $sender;
	private $endpointParameter;
	private $url;
	private $queryDelimiter;
	private $bar;

	public function setEndpointAsParameter($parameter){
		$this->endpointParameter = $parameter;
	}

	public function __construct($url, $queryDelimiter = '?'){
		$this->url = $url;
		$this->queryDelimiter = $queryDelimiter;
	}

	public function request($endpoint, array $params = array(), $method = self::GET, array $headers = array()){
		$request = new RestRequest($endpoint, $params, $method, $headers);
		$response = $this->getSender()->send($request);
		$this->getBar()->add($request, $response);
		// 2xx
		if($response->code < 300)
			return $response;
		// 3xx
		if($response < 400){
			throw new RedirectionException('Endpoint "'.$endpoint.'" at URL "'.$this->url.'" is redirected to "'.$response->getHeader('Location').'"', $response->code);
		}
		// 4xx+
		if($response->code == 401)
			throw new AuthenticationException(isset($response->body['error']['message']) ? $response->body['error']['message'] : '');
		elseif($response->code == 403)
			throw new AuthenticationException(isset($response->body['error']['message']) ? $response->body['error']['message'] : '');
		elseif($response->code == 404)
			throw new AuthenticationException(isset($response->body['error']['message']) ? $response->body['error']['message'] : '');
		else
			throw new ResponseStatusException(isset($response->body['error']['message']) ? $response->body['error']['message'] : '', $response->code);
	}

	private function getSender(){
		if(!$this->sender){
			$this->sender = new Sender($this->url, $this->endpointParameter, $this->queryDelimiter);
			$this->sender->setBodyParser(new JsonParser());
		}
		return $this->sender;
	}

	private function getBar(){
		if(!$this->bar){
			$this->bar = new BarPanel($this->url);
		}
		return $this->bar;
	}

	public function setSender(ISender $sender){
		$this->sender = $sender;
	}


}
