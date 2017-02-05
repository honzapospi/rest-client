<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient\Exceptions;
use JP\RestClient\Response;
use JP\RestClient\RestRequest;

/**
 * RestResponseStatusException
 * @author Jan Pospisil
 */

class ResponseStatusException extends \Exception {

	/**
	 * @var RestRequest
	 */
	private $request;

	/**
	 * @var Response
	 */
	private $response;

	/**
	 * @param RestRequest $request
	 */
	public function setRequest(RestRequest $request){
		$this->request = $request;
	}

	/**
	 * @return RestRequest
	 */
	public function getRequest(){
		return $this->request;
	}

	/**
	 * @param Response $response
	 */
	public function setResponse(Response $response){
		$this->response = $response;
	}

	/**
	 * @return Response
	 */
	public function getResponse(){
		return $this->response;
	}

}
