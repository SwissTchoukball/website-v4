<div class="afficherAnnuaire">
<?
	if (isset($_GET['modificationIdAnnuaire']) && is_numeric($_GET['modificationIdAnnuaire'])) {
		$modificationIdAnnuaire = $_GET['modificationIdAnnuaire'];
	}
	if (isset($_GET['suppressionIdAnnuaire']) && is_numeric($_GET['suppressionIdAnnuaire'])) {
		$suppressionIdAnnuaire = $_GET['suppressionIdAnnuaire'];
	}
	// page intermediaire pour les modifs
	if($_SESSION["__userLevel__"]<=5 && $modificationIdAnnuaire!=""){
			include "modifier.annuaire.inc.php";
		}
	// passe normale
	else{
		// suppression
		if($suppressionIdAnnuaire!=""){
			$requeteSQL = "DELETE FROM DBDPersonne WHERE idDbdPersonne='".$suppressionIdAnnuaire."'";
			mysql_query($requeteSQL) or die ("<p class='error'>Erreur de suppression</p>");

			// mise a jour des listes a options multiples
			// autres fonctions
			supprimerRelationMultiple("DBDRegroupementAutreFonction","idBDBPersonne",$suppressionIdAnnuaire);
			// formation
			supprimerRelationMultiple("DBDRegroupementFormation","idBDBPersonne",$suppressionIdAnnuaire);
		}
		// modification
		if ($_POST["action"]=="modifierAnnuaire"){

			$idDbdPersonne = $_POST['idDbdPersonne'];

			$idOrigineAdresse=$_POST["DBDOrigineAdresse"];
			$idStatus =$_POST["DBDStatus"];

			$derniereModification=date("Y-m-d H:i:s");
			$modificationPar=$_SESSION["__nom__"]." ".$_SESSION["__prenom__"];

			$idCivilite = $_POST["DBDCivilite"];
			$nom=addslashes($_POST["nom"]);
			$prenom=addslashes($_POST["prenom"]);
			$adresse=addslashes($_POST["adresse"]);
			$npa=$_POST["npa"];
			$cp=$_POST["cp"];
			$ville=$_POST["ville"];
			$telPrive=$_POST["telPrive"];
			$telProf=$_POST["telProf"];
			$portable=$_POST["portable"];
			$fax=$_POST["fax"];
			$email=$_POST["email"];
			$dateNaissance=date_date2sql($_POST["jour"]."-".$_POST["mois"]."-".$_POST["annee"]);
			$raisonSociale=addslashes($_POST["raisonSociale"]);
			$remarque=addslashes($_POST["remarque"]);

			$idLangue=$_POST["DBDLangue"];
			//$idRaisonSociale=$_POST["DBDRaisonSociale"];
			$idClub=$_POST["ClubsFstb"];
			$idPays=$_POST["DBDPays"];
			$idCHTB=$_POST["DBDCHTB"];
			$idArbitre=$_POST["DBDArbitre"];
			$typeCompte=$_POST["DBDTypeCompte"];
			$numeroCompte=$_POST["numeroCompte"];
			$idMediaType=$_POST["DBDMediaType"];
			$idMediaCanton=$_POST["DBDMediaCanton"];

			// test s'il existe deja qqun de même nom pour avertir l'utilisateur d'un potentiel doublon
			$requeteSQL = "SELECT * FROM `DBDPersonne` WHERE `nom` LIKE '$nom' AND `prenom` LIKE '$prenom'";
			mysql_query($requeteSQL);
			if(mysql_affected_rows() > 1){
				printMessage("Attention doublon possible : Il existe deja quelqu'un de m&ecirc;me nom et pr&eacute;nom ($nom $prenom). L'insertion continue...");
			}

			// selection pour le prochain affichage par lettre
			$lettre = substr($record["nom"],0,1);

			$requeteSQL="UPDATE DBDPersonne SET ".
										"`idOrigineAdresse`='$idOrigineAdresse',".
										"`idStatus`='$idStatus',".
										"`derniereModification`='$derniereModification',".
										"`editor_id`=".$_SESSION["__idUser__"].",".
										"`modificationPar`='$modificationPar',".
										"`idCivilite`='$idCivilite',".
										"`nom`='$nom',".
										"`prenom`='$prenom',".
										"`adresse`='$adresse',".
										"`npa`='$npa',".
										"`cp`='$cp',".
										"`ville`='$ville',".
										"`telPrive`='$telPrive',".
										"`telProf`='$telProf',".
										"`portable`='$portable',".
										"`fax`='$fax',".
										"`email`='$email',".
										"`dateNaissance`='$dateNaissance',".
										"`raisonSociale`='$raisonSociale',".
										"`remarque`='$remarque',".
										"`idLangue`='$idLangue',".
									//	"`idRaisonSociale`='$idRaisonSociale',".
										"`idClub`='$idClub',".
										"`idPays`='$idPays',".
										"`idCHTB`='$idCHTB',".
										"`idArbitre`='$idArbitre',".
										"`typeCompte`='$typeCompte',".
										"`numeroCompte`='$numeroCompte',".
										"`idMediaType`='$idMediaType',".
										"`idMediaCanton`='$idMediaCanton'".
									"WHERE idDbdPersonne=$idDbdPersonne";

			if(mysql_query($requeteSQL)===false){
				echo "<p class='error'>Erreur de modification : contactez le webmaster en pr&eacute;cisant les modifications posant probl&egrave;mes.</p>";
			}
			else{
				echo "<p class='success'>Modification r&eacute;ussie</p>";
			}

			// mise a jour des listes a options multiples
			// autres fonctions
			supprimerRelationMultiple("DBDRegroupementAutreFonction","idBDBPersonne",$idDbdPersonne);
			ajouterRelationMultiple("DBDRegroupementAutreFonction","idBDBPersonne","idAutreFonction",$idDbdPersonne, $DBDRegroupementAutreFonction);
			// formation
			supprimerRelationMultiple("DBDRegroupementFormation","idBDBPersonne",$idDbdPersonne);
			ajouterRelationMultiple("DBDRegroupementFormation","idBDBPersonne","idFormation",$idDbdPersonne, $DBDRegroupementFormation);
		}

		// affichage du carnet
		include "annuaire.inc.php";
	}
?>
</div>