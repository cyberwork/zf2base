<?php

namespace SONUser\Form;

use Zend\Form\Form;

class Login extends Form
{
	public function __construct($name = null, $options = array()) 
	{
			parent::__construct('user', $options);
			
			//$this->setInputFilter(new UserFilter());
			$this->setAttribute('method', 'post');
			
			$email = new \Zend\Form\Element\Text('email');
			$email->setLabel('Email')
						->setAttribute('placeholder','Digite o Email')
						->setAttribute('class','form-control');
			$this->add($email);
			
			$password = new \Zend\Form\Element\Password('password');
			$password->setLabel('Senha')
								->setAttribute('placeholder','Digite a Senha')
								->setAttribute('class','form-control');
			$this->add($password);
			
			
			//$csrf = new \Zend\Form\Element\Csrf('security');
			//$this->add($csrf);
			
			$this->add(array(
					'name' => 'submit',
					'type' => 'Zend\Form\Element\Submit',
					'attributes' => array(
							'value' => 'Login',
							'class' => 'btn-success'
					)
			));
	}
}

?>