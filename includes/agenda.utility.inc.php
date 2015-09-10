<?
	/**
		Module pour gérer des listes des catégories de l'agenda.
	*/
?>

<?
// la connection a la base de donnee doit déjà etre effectuée

	define("inclAgendafichier", "isset");	
	
	///////////////////////////////////////////////////////////////////////////////
	//Nom: creation_liste_categorie                                              //
	//But: créee la liste d'option pour les catégories                           //
	//Date: 9.11.2003                                                           //
	//Crée par: Schmocker Romain                                                 //
	//Remarques:                                                                 //
	///////////////////////////////////////////////////////////////////////////////
	function creation_liste_categorie(){
		$option = "";
	
		$requeteCategorie = "SELECT * FROM `Agenda_Categorie` ORDER BY `Nom` ASC";
		$recordset = mysql_query($requeteCategorie) or die ("<H1>mauvaise requete</H1>");
		
		// creer la liste
		while($record = mysql_fetch_array($recordset)){
			$nomOption = $record["Nom"];
			$idCategorie = $record["id_Categorie"];
			$option .= "<option value=$idCategorie>$nomOption</option>";
		}
		
		return $option;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//Nom: modif_liste_categorie                                                 //
	//But: créee la liste d'option pour les catégories                           //
	//     avec une option selectionne                                           //
	//Date: 9.11.2003                                                            //
	//Crée par: Schmocker Romain                                                 //
	//Remarques:                                                                 //
	///////////////////////////////////////////////////////////////////////////////
	function modif_liste_categorie($idCategorieSelectionne){
		$option = "";
	
		$requeteCategorie = "SELECT * FROM `Agenda_Categorie` ORDER BY `Nom` ASC";
		$recordset = mysql_query($requeteCategorie) or die ("<H1>mauvaise requete</H1>");
		
		// creer la liste
		while($record = mysql_fetch_array($recordset)){
			$nomOption = $record["Nom"];
			$idCategorie = $record["id_Categorie"];
			if($idCategorie == $idCategorieSelectionne){
				$option .= "<option selected value=$idCategorie>$nomOption</option>";
			}
			else{
				$option .= "<option value=$idCategorie>$nomOption</option>";
			}
		}
		
		return $option;
	}
?>