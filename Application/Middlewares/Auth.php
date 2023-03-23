<?php

namespace Application\Middlewares;

use Exception;
use Internal\Libraries\JWT;
use Internal\Logger\Logger;
use Internal\Middlewares\BaseMiddleware;

class Auth extends BaseMiddleware
{
	public static function CheckToken()
	{
		return function ($request, $response) {
			if (empty($request->header('token'))) {
				return $response->send(['message' => 'Token required'], 401);
			}

			$decoded = null;

			try {
				$decoded = JWT::Decode($request->header('token'), 'key', 'HS256');
			} catch (Exception) {
				return $response->send(['message' => 'Invalid token'], 401);
			}

			$request->data['id'] = $decoded['id'];
		};
	}
}
