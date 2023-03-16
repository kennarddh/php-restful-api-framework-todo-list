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

		$this->get('/', 'ToDo::getAll', ['after' => [Security::CORS(['https://localhost:3000'])]]);
		$this->post('/', 'ToDo::create', ['after' => [Security::CORS(['https://localhost:3000'])]]);
		$this->put('/:id', 'ToDo::edit', ['after' => [Security::CORS(['https://localhost:3000'])]]);

		$this->all('*', 'ToDo::matchAll', ['after' => [Security::CORS(['https://localhost:3000'])]]);

		$this->errorHandler('ToDo::errorHandler');
	}
}
