<?php

namespace Application\Controllers;

use Internal\Controllers\BaseController;
use Internal\Database\Adapters\MongoDBAdapter;
use Internal\Libraries\Validation;
use MongoDB\BSON;
use Internal\Libraries\JWT;
use Internal\Logger\Logger;

final class ToDo extends BaseController
{
	public function matchAll()
	{
		$this->response->send([
			"message" => "Route not found",
		], 404);
	}

	public function errorHandler()
	{
		$this->response->send([
			'error' => 'Internal Server Error',
		], 500);
	}
}
