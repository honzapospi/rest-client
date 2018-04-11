<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace Teze\Rest\Client;
use JP\RestClient\IBodyParser;
use Nette\Utils\ArrayHash;
use Nette\SmartObject;

/**
 * BodyParser
 * @author Jan Pospisil
 */

class JsonParser implements IBodyParser {
	use SmartObject;

	public function parse($string){
		$data = json_decode($string, true);
		if($data)
			return ArrayHash::from($data);
	}

}
