<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace Teze\Rest\Client;
use JP\RestClient\IBodyParser;
use Nette\Utils\ArrayHash;

/**
 * BodyParser
 * @author Jan Pospisil
 */

class JsonParser extends \Nette\Object implements IBodyParser {

	public function parse($string){
		return ArrayHash::from(json_decode($string, true));
	}

}
