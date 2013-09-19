<?php

namespace SONUser\Controller;

use SONUser\Controller\CrudController;

class UsersController extends CrudController 
{
	public function __construct()
	{
		$this->entity = 'SONUser\Entity\User';
		$this->form = 'SONUser\Form\User';
		$this->service = 'SONUser\Service\User';
		$this->controller = 'users';
		$this->route = 'sonuser-admin';
	}
}

?>