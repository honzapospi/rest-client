<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient\Exceptions;

/**
 * ForbiddenException
 * @author Jan Pospisil
 */

class AuthenticationException extends ResponseStatusException {

	public function __construct($message, $code = 401, \Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}

}
