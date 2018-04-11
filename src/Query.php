<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient;
use Nette\SmartObject;

/**
 * Query
 * @author Jan Pospisil
 */

class Query {
	use SmartObject;

	public $query;
	public $time;
	public $method;
	public $data;
	public $code;

}
