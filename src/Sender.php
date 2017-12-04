<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient;
use JP\RestClient\Exceptions\ConnectionException;
use Teze\Rest\Client\BodyParser;
use Tracy\Debugger;

/**
 * Sender
 * @author Jan Pospisil
 */

class Sender extends \Nette\Object implements ISender {

	public $options = array(
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT        => 60,
		CURLOPT_USERAGENT      => 'rest-client',
		CURLOPT_HEADER         => true,
		CURLOPT_VERBOSE        => true
	);

	private $bodyParser;
	private $url;
	private $sendEndpointAsParameter;
	private $queryDelimiter;

	public function __construct($url, $sendEndpointAsParameter = NULL, $queryDelimiter = '?', array $options = array()){
		$this->options = $this->options + $options;
		$this->queryDelimiter = $queryDelimiter;
		$this->sendEndpointAsParameter = $sendEndpointAsParameter;
		$this->url = $url;
	}

	public function send(RestRequest $restRequest){
		$curl = curl_init();
		$options = $this->options;
		// CONSTRUCT FINAL URL
		$url = $this->url;
		if(!$this->sendEndpointAsParameter)
			$url .= $restRequest->endpoint;
		else
			$url .= $this->queryDelimiter.$this->sendEndpointAsParameter.'='.$restRequest->endpoint;

		// PARAMETERS
		if($restRequest->method == Rest::POST)
			$options[CURLOPT_POSTFIELDS] = http_build_query($restRequest->params);
		if($restRequest->method == Rest::GET && $restRequest->params){
			if($this->sendEndpointAsParameter){
				$url .= '&'.http_build_query($restRequest->params, null, '&');
			} else {
				$url .= $this->queryDelimiter.http_build_query($restRequest->params, null, '&');
			}
		}
		if($restRequest->method == Rest::DELETE){
			$options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
		}
		$options[CURLOPT_URL] = $url;

		// HEADERS
		if($restRequest->headers){
			$headers = array();
			foreach($restRequest->headers as $name => $value)
				$headers[] = $name.': '.$value;
			$options[CURLOPT_HTTPHEADER] = $headers;
		}

		// EXECUTE
		curl_setopt_array($curl, $options);
		Debugger::timer();
		$result = curl_exec($curl);
		$time = Debugger::timer();

		// FORMAT RESPONSE
		$headersSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$headers = substr($result, 0, $headersSize);
		$body = substr($result, $headersSize);
		$headersParts = explode("\n", $headers);
		$headersValues = array();
		foreach($headersParts as $part){
			$p = explode(':', $part);
			if(count($p) > 1)
				$headersValues[trim($p[0])] = trim(substr($part, strlen($p[0]) + 1));
		}
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($httpCode == 0){
			throw new ConnectionException('No response');
		}
		$body = trim($body);
		return new Response($httpCode, $this->bodyParser ? $this->bodyParser->parse($body) : $body, $headersValues, $time, $body);
	}

	public function setBodyParser(IBodyParser $bodyParser){
		$this->bodyParser = $bodyParser;
	}

	/**
	 * @param array $options
	 */
	public function setOptions(array $options){
		$this->options = $options;
	}

	/**
	 * @param $name
	 * @param $value
	 */
	public function addOption($name, $value){
		$this->options[$name] = $value;
	}
}
