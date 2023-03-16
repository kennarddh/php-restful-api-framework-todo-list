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

	public function getAll()
	{
		$db = new MongoDBAdapter([
			'uri' => 'mongodb://127.0.0.1:27017/',
			'database' => 'todo',
		]);

		$this->response->send(['result' => $db->Get('todo', ['name', '_id', 'isDone'], [])], 200);
	}

	public function create()
	{
		$db = new MongoDBAdapter([
			'uri' => 'mongodb://127.0.0.1:27017/',
			'database' => 'todo',
		]);

		if (!isset($this->request->body->name)) {
			$this->response->send(['result' => ['message' => "Name is required"]], 400);
			return;
		}

		if (!isset($this->request->body->isDone)) {
			$this->response->send(['result' => ['message' => "IsDone is required"]], 400);
			return;
		}

		if (gettype($this->request->body->name) != "string") {
			$this->response->send(['result' => ['message' => "Name is not string"]], 400);
			return;
		}

		if (gettype($this->request->body->isDone) != "boolean") {
			$this->response->send(['result' => ['message' => "IsDone is not boolean"]], 400);
			return;
		}

		$name = $this->request->body->name;
		$isDone = $this->request->body->isDone;

		$result = $db->Insert('todo', ['name' => $name, 'isDone' => $isDone]);

		$this->response->send(['result' => $result], 200);
	}

	public function edit()
	{
		$db = new MongoDBAdapter([
			'uri' => 'mongodb://127.0.0.1:27017/',
			'database' => 'todo',
		]);

		if (!isset($this->request->body->name)) {
			$this->response->send(['result' => ['message' => "Name is required"]], 400);
			return;
		}

		if (!isset($this->request->body->isDone)) {
			$this->response->send(['result' => ['message' => "IsDone is required"]], 400);
			return;
		}

		if (!isset($this->request->params->id)) {
			$this->response->send(['result' => ['message' => "Id is required"]], 400);
			return;
		}

		if (gettype($this->request->body->name) != "string") {
			$this->response->send(['result' => ['message' => "Name is not string"]], 400);
			return;
		}

		if (gettype($this->request->body->isDone) != "boolean") {
			$this->response->send(['result' => ['message' => "IsDone is not boolean"]], 400);
			return;
		}

		$name = $this->request->body->name;
		$isDone = $this->request->body->isDone;
		$id = $this->request->params->id;

		$result = $db->Update(
			'todo',
			['name' => $name, 'isDone' => $isDone],
			['_id' => new BSON\ObjectID($id)]
		);

		$this->response->send(['result' => $result], 200);
	}
}
