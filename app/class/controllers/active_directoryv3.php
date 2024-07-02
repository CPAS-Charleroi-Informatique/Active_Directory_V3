<?php

//namespace App\controllers\ActiveDirectoryV3;

use LdapRecord\Container;
use LdapRecord\Connection;
use LdapRecord\Models\Entry;
use LdapRecord\Models\ActiveDirectory\Group;


class ActiveDirectoryV3
{
	private $estConnecte;
	private $erreur;
	private $connexion;

	public function __construct($hostname, $baseDn, $login, $accountSuffix, $password, $port = 389)
	{
		// Création de l'objet LdapRecord depuis la librairie LdapRecord
		$this->connexion = new Connection([
			'hosts' => [$hostname],
			'port' => $port,
			'base_dn' => $baseDn,
			'username' => $login.$accountSuffix,
			'password' => $password
		]);
		try {
			$this->connexion->connect();
			$this->estConnecte = true;
			$this->erreur = "";

		} catch (LdapRecord\Auth\BindException $e) {
			$this->estConnecte = false;
			$error = $e->getDetailedError();
			$this->erreur = "Erreur : " . $error->getErrorMessage();

		}

	}

	public function authentification()
	{

		return $this->estConnecte;
	}
	public function getErreur()
	{

		return $this->erreur;
	}
	public function getGroupesParSamaccountname($samaccountname)
	{
		// Retourne les groupes d'un utilisateur

		$groupes = array();
		if ($this->estConnecte) {
			// Add the connection into the container:
			Container::addConnection($this->connexion);
			$groupes = Entry::findBy('samaccountname',$samaccountname)->memberof;
			
		} else {
			return null;
		}

		return $groupes;
	}
	public function getEmailParSamaccountname($samaccountname)
	{
		$email = '';

		if ($this->estConnecte) {
			// Add the connection into the container:
			Container::addConnection($this->connexion);
			$user = Entry::findBy('samaccountname',$samaccountname)->mail;
			if (!is_null($user) && isset($user[0])) {
				$email = $user[0];
				unset ($user);	
			}
		} else {
			return null;
		}

		return $email;
	}
	public function getGroupesParMachine($machine) 
	{
		// Initialisation des variables
		$computer = array();

		if ($this->estConnecte) {
			Container::addConnection($this->connexion);
			$computer = Entry::findBy('cn',$machine)->memberof;
		} else {
			return null;
		}

		return $computer;
		
	}
	public function getMembresParGroupe($groupe)
	{
		// Initialisation des variables
		$membres = array();
		if ($this->estConnecte) {
			Container::addConnection($this->connexion);
			$membres = Group::findBy('cn',$groupe)->member;

		} else {
			return null;
		}

		return $membres;
	}

}
