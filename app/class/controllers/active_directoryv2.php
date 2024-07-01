<?php

//namespace App\controllers\ActiveDirectoryV2;

use Adldap\Adldap;
use Adldap\Auth\BindException;
use Adldap\Configuration\DomainConfiguration;
use Adldap\Models\Concerns;


class ActiveDirectoryV2
{
	private $adLDAP;
	private $domainConfig;
	private $provider;
	private $estConnecte;
	private $erreur;

	public function __construct($hostname, $baseDn, $accountSuffix, $login, $password)
	{
		// Création de l'objet adLDAP (depuis la libraire adLDAPV2)
		$this->domainConfig = new DomainConfiguration([
					'hosts' => [
						$hostname
					],
					'base_dn' => $baseDn,
					'account_suffix' => $accountSuffix,
					'username' => $login,
					'password' => $password
		]);

		try {
			$this->adLDAP = new Adldap();
			$this->adLDAP->addProvider($this->domainConfig,'connection-one');
			$this->provider = $this->adLDAP->connect('connection-one');
			$this->estConnecte = true;
			$this->erreur = "";

		} catch (BindException $e) {
			$this->adLDAP = null;
			$this->estConnecte = false;
			$this->erreur = "Erreur : " . $e->getMessage();

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
			$user = $this->provider->search()->users()->where("samaccountname","=",$samaccountname)->first();
			if (!is_null($user)) {
				$user = $user->getAttributes();
				if (isset($user['memberof'])) {
					$groupes = $user['memberof'];
				}
				unset ($user);	
			}
		} else {
			return null;
		}

		return $groupes;
	}
	public function getEmailParSamaccountname($samaccountname)
	{
		$email = '';

		if ($this->estConnecte) {
			$user = $this->provider->search()->users()->find($samaccountname);
			if (!is_null($user)) {
				$email = $user->getEmail();
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
		$groupes = array();

		if ($this->estConnecte) {
			$computer = $this->provider->search()->computers()->find($machine);
			if (!is_null($computer)) {
				$computer = $computer->getAttributes();
				if (isset($computer['memberof'])) {
					$groupes = $computer['memberof'];
				}
				unset ($computer);	
			}

		} else {
			return null;
		}

		return $groupes;
		
	}
	public function getMembresParGroupe($groupe)
	{
		// Initialisation des variables
		$membres = array();

		if ($this->estConnecte) {
			$user = $this->provider->search()->groups()->where('CN','=',$groupe)->first();
			if (!is_null($user)) {
				$user = $user->getAttributes();
				if (isset($user['member'])) {
					$membres = $user['member'];
				}
				unset ($user);	
			}
		} else {
			return null;
		}

		return $membres;
	}

}
