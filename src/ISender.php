<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient;

/**
 * ISender
 * @author Jan Pospisil
 */

interface ISender  {

	public function send(RestRequest $restRequest);

	
}
