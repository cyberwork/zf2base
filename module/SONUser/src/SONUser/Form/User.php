<?php

namespace SONUser\Form;

use Zend\Form\Form;

class User extends Form
{
	public function __construct($name = null, $options = array()) 
	{
			parent::__construct('user', $options);
			
			$this->setInputFilter(new UserFilter());
			$this->setAttribute('method', 'post');
			
			$id = new \Zend\Form\Element\Hidden('id');
			$this->add($id);
			
			$nome = new \Zend\Form\Element\Text('nome');
			$nome->setLabel('Nome')
					 ->setAttribute('placeholder','Digite o Nome')
					 ->setAttribute('class','form-control');
			$this->add($nome);
			
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
			
			$confirmation = new \Zend\Form\Element\Password('confirmation');
			$confirmation->setLabel('Redigite a Senha')
								   ->setAttribute('placeholder','Redigite a Senha')
								   ->setAttribute('class','form-control');
			$this->add($confirmation);
			
			$csrf = new \Zend\Form\Element\Csrf('security');
			$this->add($csrf);
			
			$this->add(array(
					'name' => 'submit',
					'type' => 'Zend\Form\Element\Submit',
					'attributes' => array(
							'value' => 'Salvar',
							'class' => 'btn-success'
					)
			));
	}
}

?>