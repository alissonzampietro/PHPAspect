<?php
// src/Controller/ContatoController.php

namespace Aspecto\Controller;

use Respect\Rest\Routable;

class ContatoController implements Routable {

	public function get($id) 
	{
		return "ok!";
	}

}