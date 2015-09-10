<?php

// Class objet Menu
// Represente un objet du menu (exemple news)
class Menu {
	var $nom; //string
	var $fichier; //string
	var $lienMenu; //string
	var $lienSiteExterne; // bool
	var $userLevelNeeded; // int (100 => non logué, 10 user normal,...)
	
	// string $nomMenu : le nom du menu
	// string $fichierInclude : le fichier attache au menu (chemin)
	// string $lienMenu : le lien externe (http://...)	
	// boolean $lienEstExterne : si vrai => $fichierInclude, sinon => $lienMenu
	// int $userLevel : niveau utilisateur requis pour entrer dans le menu
	// int $idBdMenu : l'id du menu qui se trouve dans la bd, ceci pour creer une table indexee sur les ids pour les liens.	
	// int $avecPartieQuick : int (0,1) pour savoir s'il y a un quick menu
	// int $idTypeInformation : type du quick menu
	// string $action special : s'ajoute dans l'uri un mot pouvant provoque une action special : exempl creation de graphe.
	// un lien externe au site s'ouvre dans une nouvelle fenetre exemple
	// http://tchoukball.ch/photos doit s'ouvrir dans une nouvelle fenêtre
	function Menu($nomMenu, $fichierInclude, $lienMenu, $lienEstExterne, $userLevel, $idBdMenu,$avecPartieQuick,
								$idTypeInformation, $actionSpeciale) {
		$this->nom = $nomMenu;
		$this->fichier = $fichierInclude;
		$this->lien = $lienMenu;
		$this->lienSiteExterne = $lienEstExterne;
		$this->userLevelNeeded = $userLevel;
		$this->idMenu = $idBdMenu;		
		$this->avecQuickMenu = $avecPartieQuick;
		$this->idTypeInfo = $idTypeInformation;
		$this->specialeAction = $actionSpeciale;
	}
	
	function getNom(){	
		return $this->nom;
	}
	
	function getFichier(){
		return $this->fichier;
	}

	// pour avoir le lien, seulement si lienExterne = vrai	
	function getLien() {
		return $this->lien;
	}
	
	function lienEstExterne(){
		return $this->lienSiteExterne;
	}
	
	function getUserLevel(){
		return $this->userLevelNeeded;
	}	
	
	function getIdBdMenu(){
		return $this->idMenu;
	}	
	
	function getAvecPartieQuick(){
		return $this->avecQuickMenu;
	}
	
	function getIdTypeInfo(){
		return $this->idTypeInfo;
	}

	function getActionSpeciale(){
		return $this->specialeAction;
	}
}

// classe Personne
class Personne{
	var $nom; // string
	var $prenom; // string
	var $adresse; // string
	var $npa; // numero postal : int 
	var $ville; // string	
	var $email; // string
	var $telephone; // string
	var $portable; // string
	var $dateNaissance; // string
			
}
?>


