<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient;
use JP\RestClient\Exceptions\AuthenticationException;
use JP\RestClient\Exceptions\BadRequestException;
use JP\RestClient\Exceptions\ForbiddenException;
use JP\RestClient\Exceptions\NotFoundException;
use JP\RestClient\Exceptions\RedirectionException;
use JP\RestClient\Exceptions\ResponseStatusException;
use Teze\Rest\Client\JsonParser;
use Tracy\IBarPanel;
use Tracy\Debugger;
/**
 * Rest
 * @author Jan Pospisil
 * @property ISender $sender
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
	private $headers = array();
	public $onException = array();

	public function setHeader($name, $value){
		$this->headers[$name] = $value;
	}

	public function setEndpointAsParameter($parameter){
		$this->endpointParameter = $parameter;
	}

	public function __construct($url){
		$this->url = $url;
		$this->queryDelimiter = preg_match('#\?#', $this->url) ? '&' : '?';
	}

	public function request($endpoint, array $params = array(), $method = self::GET, array $headers = array()){
		$headers = array_merge($this->headers, $headers);
		$request = new RestRequest($endpoint, $params, $method, $headers);
		$response = $this->getSender()->send($request);
		$this->getBar()->add($request, $response);
		// 2xx
		if($response->code < 300){
			return $response;
		}
		// 3xx
		if($response->code < 400){
			$e = new RedirectionException('Endpoint "'.$endpoint.'" at URL "'.$this->url.'" is redirected to "'.$response->getHeader('Location').'"', $response->code);
			$e->setRequest($request);
			$e->setResponse($response);
			$this->onException($this, $e);
			throw $e;
		}
		// 4xx
		if($response->code == 400){
			$e = new BadRequestException(isset($response->body['error']['message']) ? $response->body['error']['message'] : '');
			$e->setRequest($request);
			$e->setResponse($response);
			$this->onException($this, $e);
			throw $e;
		} elseif($response->code == 401){
			$e = new AuthenticationException(isset($response->body['error']['message']) ? $response->body['error']['message'] : '');
			$e->setRequest($request);
			$e->setResponse($response);
			$this->onException($this, $e);
			throw $e;
		} elseif($response->code == 403) {
			$e = new ForbiddenException(isset($response->body['error']['message']) ? $response->body['error']['message'] : '');
			$e->setRequest($request);
			$e->setResponse($response);
			$this->onException($this, $e);
			throw $e;
		} elseif($response->code == 404){
			$e = new NotFoundException(isset($response->body['error']['message']) ? $response->body['error']['message'] : '');
			$e->setRequest($request);
			$e->setResponse($response);
			$this->onException($this, $e);
			throw $e;
		} else {
			if(!Debugger::$productionMode && $response->getSource()){
				echo $response->getSource();
				die();
			} else {
				$e = new ResponseStatusException(isset($response->body['error']['message']) ? $response->body['error']['message'] : '', $response->code);
				$e->setRequest($request);
				$e->setResponse($response);
				$this->onException($this, $e);
				throw $e;
			}
		}
	}

	/**
	 * @return Sender
	 */
	public function getSender(){
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

	public function setBar(IBarPanel $bar){
		$this->bar = $bar;
	}

	public function setSender(ISender $sender){
		$this->sender = $sender;
	}


}
