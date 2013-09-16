<?php

namespace SONUser\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture,
		Doctrine\Common\Persistence\ObjectManager;

use SONUser\Entity\User;

class LoadUser extends AbstractFixture 
{
	public function load(ObjectManager $manager)
	{
		$object = new User();
		$object->setNome('Admin')
					 ->setEmail('admin@admin.com')
					 ->setPassword('123')
					 ->setActive(true);
		$manager->persist($object);
		$manager->flush();
	}

}

?>