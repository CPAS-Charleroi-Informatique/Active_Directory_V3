<?php

/**
 *  @Author : Gantois Olivier 
 *  @Date mise à jour : 28/06/2024
 *  @Php :  8.2.4
 *  @Composer + directorytree/ldaprecord
 * 
 */


require 'vendor/autoload.php';
require_once dirname(__FILE__) . "/app/class/controllers/active_directoryv3.php";

error_reporting(0);

// On désactive le cache
ini_set("soap.wsdl_cache_enabled", "0");

$wsdl = dirname(__FILE__) . '/include/wsdl/active_directoryv3.wsdl';

$soapServer = new SoapServer($wsdl);

$soapServer->setClass("ActiveDirectoryV3Webservice");

// On lance le serveur SOAP
$soapServer->handle();

class ActiveDirectoryv3Webservice
{
	public function authentification($login, $password)
	{
		include_once 'app/config/active_directoryv3.php';
		$activeDirectoryv3 = new ActiveDirectoryv3($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $login , $_config['ldap']['accountSuffix'], $password, $_config['ldap']['port']);
		return $activeDirectoryv3->authentification();
	}
	public function getGroupesParSamaccountname($samaccountname)
	{
		include_once 'app/config/active_directoryv3.php';
		$activeDirectoryV3 = new ActiveDirectoryV3($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $_config['ldap']['login'], $_config['ldap']['accountSuffix'], $_config['ldap']['password'], $_config['ldap']['port']);
		if ($activeDirectoryV3->authentification()) {
			// Récupère les groupes de la personne
            $groupes = $activeDirectoryV3->getGroupesParSamaccountname($samaccountname);
            foreach ($groupes as $id=>$groupe) {
                $cn = str_replace('CN=','',explode(",",$groupe));
                $groupes[$id] = $cn[0];
            } unset ($groupe, $id);
            return $groupes;
		} else {
			return  null;
		}
	}
    public function getEmailParSamaccountname($samaccountname)
	{
		include_once 'app/config/active_directoryv3.php';
		$activeDirectoryV3 = new ActiveDirectoryV3($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $_config['ldap']['login'], $_config['ldap']['accountSuffix'], $_config['ldap']['password'], $_config['ldap']['port']);
		if ($activeDirectoryV3->authentification()) {
			// Récupère l'adresse Email de la personne
			return  $activeDirectoryV3->getEmailParSamaccountname($samaccountname);
 
		}
		return null;
	}
	public function getGroupesParMachine($machine)
	{
		include_once 'app/config/active_directoryV3.php';
		$activeDirectoryV3 = new ActiveDirectoryV3($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $_config['ldap']['login'], $_config['ldap']['accountSuffix'], $_config['ldap']['password'], $_config['ldap']['port']);
		if ($activeDirectoryV3->authentification()) {
			// Récupère les groupes de la machine
			$groupes = $activeDirectoryV3->getGroupesParMachine($machine);
            foreach ($groupes as $id=>$groupe) {
                $cn = str_replace('CN=','',explode(",",$groupe));
                $groupes[$id] = $cn[0];
            } unset ($groupe, $id);
            return $groupes;
		} else {
			return  null;
		}
	}

	public function getMembresParGroupe($groupe)
	{
		// Récupère les membres du groupe
		include_once 'app/config/active_directoryV3.php';
		$activeDirectoryV3 = new ActiveDirectoryV3($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $_config['ldap']['login'], $_config['ldap']['accountSuffix'], $_config['ldap']['password'], $_config['ldap']['port']);		if ($activeDirectoryV3->authentification()) {
			// Récupère les membres du groupe
			$membres = $activeDirectoryV3->getMembresParGroupe($groupe);
			if (count($membres)>0) {
				foreach ($membres as $id=>$membre) {
					$cn = str_replace('CN=','',explode(",",$membre));
					$membres[$id] = $cn[0];
				} unset ($membre, $id);
				return $membres;
			} else {
				return null;
			}
		} else {
			return null;
		}
		
	}	


}
