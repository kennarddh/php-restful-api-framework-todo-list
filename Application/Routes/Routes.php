<?php

namespace Application\Routes;

use Internal\Routes\BaseRoutes;

use Internal\Middlewares\Default\Security;
use Application\Middlewares\Auth;

class Routes extends BaseRoutes
{
	public function __construct()
	{
		// Pass true as first argument if this is root route
		parent::__construct(true);

		$this->get('/', 'ToDo::getAll', ['before' => [Auth::CheckToken()], 'after' => [Security::CORS(['https://localhost:3000'])]]);
		$this->post('/', 'ToDo::create', ['before' => [Auth::CheckToken()], 'after' => [Security::CORS(['https://localhost:3000'])]]);
		$this->put('/:id', 'ToDo::edit', ['before' => [Auth::CheckToken()], 'after' => [Security::CORS(['https://localhost:3000'])]]);

		$this->group('auth', ['after' => [Security::CORS(['https://localhost:3000'])]], function ($router) {
			$router->post('register', 'Auth::Register');
			$router->post('login', 'Auth::Login');
		});

		$this->all('*', 'ToDo::matchAll', ['after' => [Security::CORS(['https://localhost:3000'])]]);

		$this->errorHandler('ToDo::errorHandler');
	}
}
