<?php

namespace SONUser\Controller;

use Zend\Mvc\Controller\AbstractActionController,
		Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator,
		Zend\Paginator\Adapter\ArrayAdapter;
use Doctrine\ORM\EntityManager;

abstract class CrudController extends AbstractActionController
{
		protected $em;
		protected $service;
		protected $entity;
		protected $form;
		protected $route;
		protected $controller;
		
	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() 
	{
		$list = $this->getEm()->getRepository($this->entity)->findAll();

		//$pageNumber = $this->params()->fromRoute('page');
		
		$paginator = new Paginator(new ArrayAdapter($list));
		$paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1))
							->setDefaultItemCountPerPage(10);
		
		return new ViewModel(array('data' => $paginator));
	}
	public function newAction()
	{
		$form = new $this->form;
		$request = $this->getRequest();
		
		if($request->isPost())
		{
			$form->setData($request->getPost())->isValid();
			if($form->isValid())
			{
				$service = $this->getServiceLocator()->get($this->service);
				$service->insert($request->getPost()->toArray());
				
				return $this->redirect()->toRoute($this->route, array('controller'=>$this->controller));
			}
		}
		
		return new ViewModel(array('form'=>$form));
	}
		
		/**
		 * @return EntityManager
		 */
		protected function getEm()
		{
			if(null ===$this->em)
				return $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
}

?>