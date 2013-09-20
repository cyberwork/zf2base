<?php
namespace SONAcl\Service;

use SONBase\Service\AbstractService;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;

class Role extends AbstractService
{

	public function __construct(\Doctrine\ORM\EntityManager $em) 
	{
		parent::__construct($em);
		$this->entity = 'SONAcl\Entity\Role';
	}
	public function insert(array $data)
	{
		$entity = new $this->entity($data);
		
		// Caso um parent seja enviado procura a entidade do mesmo
		if($data['parent'])
		{
		    $parent = $this->em->getReference($this->entity, $data['parent']);
		    $entity->setParent($parent);
		}
		else 
		    $entity->setParent(null);
		
		$this->em->persist($entity);
		$this->em->flush();
		return $entity;
	}
	public function update(array $data)
	{
		$entity = $this->em->getReference($this->entity, $data['id']);
		(new Hydrator\ClassMethods())->hydrate($data, $entity);
		
		// Caso um parent seja enviado procura a entidade do mesmo
		if($data['parent'])
		{
			$parent = $this->em->getReference($this->entity, $data['parent']);
			$entity->setParent($parent);
		}
		else
			$entity->setParent(null);
		
		$this->em->persist($entity);
		$this->em->flush();
		return $entity;
	}
}

?>