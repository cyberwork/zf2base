<?php

namespace SONUser;

use Zend\Mvc\MvcEvent;

use Zend\Mail\Transport\Smtp as SmtpTransport, 
	  Zend\Mail\Transport\SmtpOptions;

use SONBase\Auth\Adapter as AuthAdapter;

use Zend\Authentication\AuthenticationService,
		Zend\Authentication\Storage\Session as SessionStorage;

use Zend\ModuleManager\ModuleManager;

class Module {
	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}
	public function getAutoloaderConfig() {
		return array (
				'Zend\Loader\StandardAutoloader' => array (
						'namespaces' => array (
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__ 
						) 
				) 
		);
	}
	public function init(ModuleManager $moduleManager)
	{
		$sharedEvents = $moduleManager->getEventManager()->getSharedManager();
		$sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, array($this,'validaAuth'),100);
	}
	public function validaAuth($e)
	{
		$auth = new AuthenticationService;
		$auth->setStorage(new SessionStorage('SONUser'));
		
		$controller = $e->getTarget();
		$matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();
		
		if(!$auth->hasIdentity() && ($matchedRoute == 'sonuser-admin' || $matchedRoute == 'sonuser-admin/paginator'))
			return $controller->redirect()->toRoute('sonuser-auth');
	}
	public function getServiceConfig() {
		return array (
				'factories' => array (
						 'Zend\Log\FirePhp' => function($sm) {
                $writer_firebug = new \Zend\Log\Writer\FirePhp();
                $logger = new \Zend\Log\Logger();
                $logger->addWriter($writer_firebug);
                return $logger;
            },
						'SONUser\Mail\Transport' => function($sm) {
                $config = $sm->get('Config');
                
                $transport = new SmtpTransport;
                $options = new SmtpOptions($config['mail']);
                $transport->setOptions($options);
                
                return $transport;
              },
              'SONUser\Service\User' => function($sm) {
                  return new Service\User($sm->get('Doctrine\ORM\EntityManager'),
                                          $sm->get('SONUser\Mail\Transport'),
                                          $sm->get('View'));
              },
              'SONBase\Auth\Adapter' => function ($sm) {
              		return new AuthAdapter($sm->get('Doctrine\ORM\EntityManager'));
              }
				) 
		);
	}
	public function getViewHelperConfig()
	{
		return array(
			'invokables' => array(
					'UserIdenty' => new View\Helper\UserIdentity()
			)
		);
	}
}
