<?php 
require 'vendor/autoload.php';
require_once dirname(__FILE__) . "/app/class/controllers/active_directoryv2.php";

include_once 'app/config/active_directoryv2.php';
$activeDirectoryV2 = new ActiveDirectoryV2($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $_config['ldap']['accountSuffix'], $_config['ldap']['login'].$_config['ldap']['accountSuffix'] , $_config['ldap']['password']);
if ($activeDirectoryV2->authentification()) {
    // Récupère les groupes de la personne
    $groupes = $activeDirectoryV2->getMembresParGroupe("wd_Compta_ECHEANCIER");
    var_dump($groupes);//echo "<SUCCES>1</SUCCES>";

} else {
    //echo "<SUCCES>0</SUCCES>";
}

