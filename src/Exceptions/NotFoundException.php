<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient\Exceptions;

/**
 * NotFoundException
 * @author Jan Pospisil
 */

class NotFoundException extends ResponseStatusException {

	public function __construct($message, $code = 404, \Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}

}
