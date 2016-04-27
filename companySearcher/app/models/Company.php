<?php

use Phalcon\Mvc\Model;

class Company extends Model
{


	public function initialize()
	{
		$this->setSource('companies');
	}
}
