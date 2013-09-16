<?php

namespace SONUser\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;
use Zend\Stdlib\Hydrator;
use Zend\Stdlib\Hydrator\ClassMethods;
/**
 * SonuserUsers
 *
 * @ORM\Table(name="sonuser_users", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class User {
	/**
	 *
	 * @var integer @ORM\Column(name="id", type="integer", nullable=false)
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;
	
	/**
	 *
	 * @var string @ORM\Column(name="nome", type="string", length=255, nullable=false)
	 */
	private $nome;
	
	/**
	 *
	 * @var string @ORM\Column(name="email", type="string", length=255, nullable=false)
	 */
	private $email;
	
	/**
	 *
	 * @var string @ORM\Column(name="password", type="string", length=255, nullable=false)
	 */
	private $password;
	
	/**
	 *
	 * @var string @ORM\Column(name="salt", type="string", length=255, nullable=false)
	 */
	private $salt;
	
	/**
	 *
	 * @var boolean @ORM\Column(name="active", type="boolean", nullable=true)
	 */
	private $active;
	
	/**
	 *
	 * @var string @ORM\Column(name="activation_key", type="string", length=255, nullable=false)
	 */
	private $activationKey;
	
	/**
	 *
	 * @var \DateTime @ORM\Column(name="updated_at", type="datetime", nullable=false)
	 */
	private $updatedAt;
	
	/**
	 *
	 * @var \DateTime @ORM\Column(name="created_at", type="datetime", nullable=false)
	 */
	private $createdAt;
	
	public function __construct(array $data = array()) {
		// Setando os Sets com o Hydrator
			//$hydrator = new ClassMethods();
			//$hydrator->hydrate($data, $this);
		(new Hydrator\ClassMethods)->hydrate($data, $this);
		// Setando o createdAt e UpdatedAt para o horário atual
		$this->createdAt = new \DateTime ( 'NOW' );
		$this->updatedAt = new \DateTime ( 'NOW' );
		
		// Gerando um salt aleatório
		$this->salt = base64_encode(Rand::getBytes(8,true));
		// Gerando o activationKey concatenando email e salt e encriptando via MD5
		$this->activationKey = md5($this->email.$this->salt);
	}
	
	public function encryptPassword ($password) 
	{
		return (new Bcrypt)->setSalt($this->salt)->setCost(13)->create($password);
	}
	protected function geraHash ($hash) {
		$gerahash = new Bcrypt();
		$gerahash->setSalt(substr(sha1(mt_rand()),0,22))
	    	->setCost('13');
		
	}
	/**
	 *
	 * @return the integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @param integer $id        	
	 */
	public function setId(integer $id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getNome() {
		return $this->nome;
	}
	
	/**
	 *
	 * @param string $nome        	
	 */
	public function setNome(string $nome) {
		$this->nome = $nome;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 *
	 * @param string $email        	
	 */
	public function setEmail(string $email) {
		$this->email = $email;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 *
	 * @param string $password        	
	 */
	public function setPassword(string $password) {
		$this->password = $this->encryptPassword($password);
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getSalt() {
		return $this->salt;
	}
	
	/**
	 *
	 * @param string $salt        	
	 */
	public function setSalt(string $salt) {
		$this->salt = $salt;
		return $this;
	}
	
	/**
	 *
	 * @return the boolean
	 */
	public function getActive() {
		return $this->active;
	}
	
	/**
	 *
	 * @param boolean $active        	
	 */
	public function setActive(boolean $active) {
		$this->active = $active;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getActivationKey() {
		return $this->activationKey;
	}
	
	/**
	 *
	 * @param string $activationKey        	
	 */
	public function setActivationKey(string $activationKey) {
		$this->activationKey = $activationKey;
		return $this;
	}
	
	/**
	 *
	 * @return the DateTime
	 */
	public function getUpdatedAt() {
		return $this->updatedAt;
	}
	
	/**
	 *
	 * @param \DateTime $updatedAt
	 * @ORM\prePersist        	
	 */
	public function setUpdatedAt() {
		$this->updatedAt = new \DateTime('NOW');
		return $this;
	}
	
	/**
	 *
	 * @return the DateTime
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}
	
	/**
	 *
	 * @param \DateTime $createdAt        	
	 */
	public function setCreatedAt() {
		$this->createdAt = new \DateTime('NOW');
		return $this;
	}
}
