<?php

namespace Application\Controllers;

use Internal\Controllers\BaseController;
use Internal\Database\Adapters\MongoDBAdapter;
use Internal\Libraries\Validation;
use MongoDB\BSON;
use Internal\Libraries\JWT;
use Internal\Logger\Logger;

final class Auth extends BaseController
{
	public function Login()
	{
		if (!isset($this->request->body->username)) {
			$this->response->send(['result' => ['message' => "Username is required"]], 400);
			return;
		}

		if (gettype($this->request->body->username) != "string") {
			$this->response->send(['result' => ['message' => "Username is not string"]], 400);
			return;
		}

		if (!isset($this->request->body->password)) {
			$this->response->send(['result' => ['message' => "Password is required"]], 400);
			return;
		}

		if (gettype($this->request->body->password) != "string") {
			$this->response->send(['result' => ['message' => "Password is not string"]], 400);
			return;
		}

		$username = $this->request->body->username;
		$password = $this->request->body->password;

		$hash = password_hash($password, PASSWORD_DEFAULT);

		$db = new MongoDBAdapter([
			'uri' => 'mongodb://127.0.0.1:27017/',
			'database' => 'todo',
		]);

		$users = $db->Get('users', ['_id', 'username'], ['username' => $username]);

		if (empty($users)) {
			$this->response->send(['result' => ['message' => "User doesn't exist"]], 400);
			return;
		}

		$user = $users[0];

		if (!password_verify($user->username, $hash)) {
			$this->response->send(['result' => ['message' => "User doesn't exist"]], 400);
			return;
		}

		// Create token

		$token = JWT::Encode([
			'id' => $user->_id,
		], 'key', 'HS256');

		$this->response->send(['result' => ['message' => "Login success", "token" => $token]], 200);
	}

	public function Register()
	{
		if (!isset($this->request->body->username)) {
			$this->response->send(['result' => ['message' => "Username is required"]], 400);
			return;
		}

		if (gettype($this->request->body->username) != "string") {
			$this->response->send(['result' => ['message' => "Username is not string"]], 400);
			return;
		}

		if (!isset($this->request->body->password)) {
			$this->response->send(['result' => ['message' => "Password is required"]], 400);
			return;
		}

		if (gettype($this->request->body->password) != "string") {
			$this->response->send(['result' => ['message' => "Password is not string"]], 400);
			return;
		}

		$username = $this->request->body->username;
		$password = $this->request->body->password;

		$hash = password_hash($password, PASSWORD_DEFAULT);

		$db = new MongoDBAdapter([
			'uri' => 'mongodb://127.0.0.1:27017/',
			'database' => 'todo',
		]);

		$users = $db->Get('users', ['_id'], ['username' => $username]);

		if (!empty($users)) {
			$this->response->send(['result' => ['message' => "Username already exist"]], 400);
			return;
		}

		$success = $db->Insert('users', [
			'username' => $username,
			'password' => $hash
		]);

		if (!$success) {
			$this->response->send(['result' => ['message' => "Failed to register new user"]], 500);
			return;
		}

		$this->response->send(['result' => ['message' => "Register user success"]], 201);
	}
}
