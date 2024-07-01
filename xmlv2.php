<?php

require 'vendor/autoload.php';
require_once dirname(__FILE__) . "/app/class/controllers/active_directoryv3.php";

error_reporting(0);

// On désactive le cache
ini_set("soap.wsdl_cache_enabled", "0");

$wsdl = dirname(__FILE__) . '/include/wsdl/active_directoryv3_xml.wsdl';

$soapServer = new SoapServer($wsdl);

$soapServer->setClass("ActiveDirectoryv3XmlWebservice");

// On lance le serveur SOAP
$soapServer->handle();

class ActiveDirectoryv3XmlWebservice
{
	public function authentification($login, $password)
	{
		include_once 'app/config/active_directoryv3.php';
		$activeDirectoryv3 = new ActiveDirectoryv3($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $_config['ldap']['accountSuffix'], $login.$_config['ldap']['accountSuffix'] , $password);
		if ($activeDirectoryv3->authentification()) {
			
			return "<SUCCES>1</SUCCES>";

		} else {
			return "<SUCCES>0</SUCCES>";

		}
	}
	public function getGroupesParSamaccountname($samaccountname)
	{
		include_once 'app/config/active_directoryv3.php';
		$activeDirectoryv3 = new ActiveDirectoryv3($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $_config['ldap']['accountSuffix'], $_config['ldap']['login'].$_config['ldap']['accountSuffix'] , $_config['ldap']['password']);
		if ($activeDirectoryv3->authentification()) {
			// Récupère les groupes de la personne
			$groupes = $activeDirectoryv3->getGroupesParSamaccountname($samaccountname);
			// Génération du retour
			$retour = "<SUCCES>0</SUCCES>";
			if (!is_null($groupes)) {
				$retour = "<SUCCES>1</SUCCES>";
				if (count($groupes) > 0) {
					$retour .= "<GROUPES>";
					foreach ($groupes as $id=>$groupe) {
						$cn = str_replace('CN=','',explode(",",$groupe));
						$retour .= "<GROUPE>" . $cn[0] . "</GROUPE>";
					} unset ($groupe, $id);
					$retour .= "</GROUPES>";
				}
			}
		} else {
			$retour = "<SUCCES>0</SUCCES>";
		}
		return $retour;
	}
	
	public function getEmailParSamaccountname($samaccountname)
	{
		include_once 'app/config/active_directoryv3.php';
		$activeDirectoryv3 = new ActiveDirectoryv3($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $_config['ldap']['accountSuffix'], $_config['ldap']['login'].$_config['ldap']['accountSuffix'] , $_config['ldap']['password']);
		// Récupère l'email de la personne
		$retour = "<SUCCES>0</SUCCES>";
		if ($activeDirectoryv3->authentification()) {
			$email = $activeDirectoryv3->getEmailParSamaccountname($samaccountname);
			if (!empty($email)) {
				$retour = "<SUCCES>1</SUCCES>";
				$retour .= "<EMAIL>" . $email . "</EMAIL>";
			}
		}
		return $retour;
	}
	public function getGroupesParMachine($machine)
	{
		include_once 'app/config/active_directoryv3.php';
		$activeDirectoryv3 = new ActiveDirectoryv3($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $_config['ldap']['accountSuffix'], $_config['ldap']['login'].$_config['ldap']['accountSuffix'] , $_config['ldap']['password']);
		$retour = "<SUCCES>0</SUCCES>";
		if ($activeDirectoryv3->authentification()) {
			// Récupère les groupes de la machine
			$retour = "<SUCCES>1</SUCCES>";
			$groupes = $activeDirectoryv3->getGroupesParMachine($machine);
			if (count($groupes)>0) {
				$retour .= "<GROUPES>";
				foreach ($groupes as $id=>$groupe) {
					$cn = str_replace('CN=','',explode(",",$groupe));
					$retour .= "<GROUPE>" . $cn[0] . "</GROUPE>";
				} unset ($groupe, $id);
				$retour .= "</GROUPES>";
			}
		} 
		return $retour;
	}
	public function getMembresParGroupe($groupe)
	{
		// Récupère les membres du groupe
		include_once 'app/config/active_directoryv3.php';
		$activeDirectoryv3 = new ActiveDirectoryv3($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $_config['ldap']['accountSuffix'], $_config['ldap']['login'].$_config['ldap']['accountSuffix'] , $_config['ldap']['password']);

		$retour = "<SUCCES>0</SUCCES>";
		if ($activeDirectoryv3->authentification()) {
			// Récupère les groupes de la machine
			$retour = "<SUCCES>1</SUCCES>";
			$membres = $activeDirectoryv3->getMembresParGroupe($groupe);
			if (count($membres)>0) {
				$retour .= "<MEMBRES>";
				foreach ($membres as $id=>$membre) {
					$cn = str_replace('CN=','',explode(",",$membre));
					$retour .= "<MEMBRE>" . $cn[0] . "</MEMBRE>";
				} unset ($groupe, $id);
				$retour .= "</MEMBRES>";
			}
		} 
		return $retour;
	}
}