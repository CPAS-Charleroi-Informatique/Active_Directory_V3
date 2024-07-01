<h1  align="center">ActiveDicrectoryV2</h1>

<h3  align="center">Webservice SOAP</h3>

<hr>

  

<p  align="center">


  

<center><strong>Permet d'obtenir différentes informations sur l'ActiveDirectory.</strong> </center>

  

<p  align="center">

<a  href="https://github.com/CPAS-Charleroi-Informatique/Active_directory_V2.git">Github</a>

</p>

  
  

##  Prérequis

  - PHP 7.3.12.
  - Composer require adldap2/adldap2

  

##  Configuration

  

- Recopier le fichier app/config/_active_directoryv2.php en **active_directory2.php**.
- Indiquer les données d'authentification dans le fichier app/config/active_directoryv2.php.

## Ressources 

Il y a deux façons d'obtenir les renseignements.   

Environnement de test. 
 - https://librarytest.cpas.intra/activedirectoryv2/index.php?wsdl
 - https://librarytest.cpas.intra/activedirectoryv2/xmlv2.php?wsdl

Environnement de production. 
 - https://library.cpas.intra/activedirectoryv2/index.php?wsdl
 - https://library.cpas.intra/activedirectoryv2/xmlv2.php?wsdl

Les réponses pour "Xmlv2" sont de type chaine de caractères qui représentent un format de type XML.

## Méthodes

<strong>authentification : </strong> Vérifier si le couple login/mot de passe est valide.

	Paramètres d'entrées : 

	- login ( string) : 
	- password (string)

	Réponse :
	- (index.php) Vrai ou Faux (boolean).
	ou
	- (xmlv2.php) "< SUCCES>1</ SUCCES>"  ou  "< SUCCES>0</ SUCCES>".

<strong>getGroupesParSamaccountname : </strong> Retourne les groupes de l'ad d'un utilisateur par son samaccountname.

	Paramètres d'entrées : 

	- samaccountname ( string) : 

	Réponse :
	- (index.php) tableau de chaines.
	ou
	- (xmlv2.php) "< SUCCES>1</ SUCCES>< GROUPES>< GROUPE>GG_O365TeamsGiphyAllowed</ GROUPE>< GROUPE>GG_MRListeAttente</ GROUPE>< /GROUPES>]".

<strong>getEmailParSamaccountname : </strong> Retourne l'adresse mail d'un utilisateur par son samaccountname.

	Paramètres d'entrées : 

	- samaccountname ( string) : 

	Réponse :
	- (index.php) String.
	ou
	- (xmlv2.php) "< SUCCES>1</ SUCCES>< EMAIL>marcel.dupont@cpascharleroi.be< /EMAIL>]".

<strong>getGroupesParMachine: </strong> Retourne les groupes ad d'une machine par son nom.

	Paramètres d'entrées : 

	- nomMachine( string) : 

	Réponse :
	- (index.php) Tableau de chaines.
	ou
	- (xmlv2.php) "< SUCCES>1</ SUCCES>< GROUPES>< GROUPE>GG_O365TeamsGiphyAllowed</ GROUPE>< GROUPE>GG_MRListeAttente</ GROUPE>< /GROUPES>]".

<strong>getMembresParGroupe: </strong> Retourne les membres appartenant à un groupe.

	Paramètres d'entrées : 

	- nomGroupe( string) : 

	Réponse :
	- (index.php) Tableau de chaine.
	ou
	- (xmlv2.php) "< SUCCES>1</ SUCCES>< MEMBRES>< MEMBRE>marcel.dupont>< MEMBRE>jean.tartempion</ MEMBRE>< /MEMBRES>]".


## Utilisation par 

- Module MyDllCompta (Composant WD 17 ) : Composants utilisées dans les applications Compta.
- Module cptapplicatif.php ( WS service Prod et Test ) : Utiliser par les outils WD compta.
- Sociabili partie d'envois d'email des PV de séance.

## Auteur 

- Olivier Gantois 
- Dernière modification par Olivier Gantois : 19/07/2023.
 
			
	
	
