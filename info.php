<?php 

require __DIR__.'/vendor/autoload.php';
require_once dirname(__FILE__) . "/app/class/controllers/active_directoryV3.php";
include_once 'app/config/active_directoryV3.php';
$activeDirectoryV3 = new ActiveDirectoryV3($_config['ldap']['hostname'], $_config['ldap']['baseDn'], $_config['ldap']['login'], $_config['ldap']['accountSuffix'], $_config['ldap']['password'],$_config['ldap']['port']);
if ($activeDirectoryV3->authentification()) {
    $user = $activeDirectoryV3->getMembresParGroupe('CS_TEmplates');
    //$user = $activeDirectoryV3->getGroupesParMachine('CPAS2933');

    var_dump($user);
} else {
    var_dump($activeDirectoryV3->getErreur());
}

