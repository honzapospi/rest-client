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
		$data = json_decode($string, true);
		if($data)
			return ArrayHash::from($data);
	}

}
