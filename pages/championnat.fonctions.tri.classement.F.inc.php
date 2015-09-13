<?php

function triEgaliteParfaite($informations,$tableau,$debug){
	$nouveauTableau=array();
	$groupeEgalite=0;
	$annee=$informations['annee'];
	$idCategorie=$informations['idCategorie'];
	$idTour=$informations['idTour'];
	$noGroupe=$informations['noGroupe'];

	for($k=1;$k<=$tableau[0];$k++){
		if(count($tableau[$k])>1){
			if($debug){
				echo "<br /><strong>Il y a une égalité de points marqués.</strong><br />";
			}

			$ordningEquipesEgalitesPoints=array();
			$ordningEquipesEgalitesId=array();
			$l=0;

			$requeteNomsEquipes="SELECT DISTINCT Championnat_Equipes.idEquipe, equipe, egaliteParfaite FROM Championnat_Equipes, Championnat_Equipes_Tours WHERE Championnat_Equipes.idEquipe=Championnat_Equipes_Tours.idEquipe AND saison=".$annee." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$noGroupe." AND (";

			for($i=1;$i<=count($tableau[$k]);$i++){ // Une boucle par équipe à égalité ==>> $i = EQUIPE EGALITE
				$requeteNomsEquipes.="Championnat_Equipes.idEquipe=".$tableau[$k][$i]." ";
				if($i!=count($tableau[$k])){
					$requeteNomsEquipes.="OR ";
				}
			} // Fin boucle par équipe égalité

			$requeteNomsEquipes.=") ORDER BY egaliteParfaite, equipe";

			if($debug){
				echo "Tri par ordre alphabétique (ou en fonction de la résolution d'égalité parfaite): ". $requeteNomsEquipes;
			}

			$retourNomsEquipes=mysql_query($requeteNomsEquipes);
			$nbEquipesEgaliteParfaite=0;
			while($donneesNomsEquipes=mysql_fetch_array($retourNomsEquipes)){ // Classement des équipes à égalité parfaite par ordre alphabétique (ou en fonction de la résolution d'égalité parfaite).
				$groupeEgalite++;
				if($donneesNomsEquipes['egaliteParfaite']<=1){
					$nbEquipesEgaliteParfaite++;
				}
				$nouveauTableau[$groupeEgalite][1]=$donneesNomsEquipes['idEquipe'];
				$nomEquipeEgaliteParfaite[$nbEquipesEgaliteParfaite]=$donneesNomsEquipes['equipe'];
				$idEquipeEgaliteParfaite[$nbEquipesEgaliteParfaite]=$donneesNomsEquipes['idEquipe'];
			}


			/* Requête pour vérifier si le mail a déjà été envoyé en allant voir dans la table Championnat_Equipes_Tours si le champ egaliteParfaite est à 1 */

			if($nbEquipesEgaliteParfaite!=0){
				$requeteVerificationDejaEnvoye="SELECT DISTINCT egaliteParfaite FROM Championnat_Equipes_Tours WHERE saison=".$annee." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$noGroupe." AND (";
				for($j=1;$j<=$nbEquipesEgaliteParfaite;$j++){
					$requeteVerificationDejaEnvoye.="idEquipe=".$idEquipeEgaliteParfaite[$j];
					if($j!=$nbEquipesEgaliteParfaite){
						$requeteVerificationDejaEnvoye.=" OR ";
					}
				}
				$requeteVerificationDejaEnvoye.=")";
				if($debug){
					echo "<br />Requête pour vérifier si le champ egaliteParfaite est déjà à 1 : ".$requeteVerificationDejaEnvoye;
				}
				$retourVerificationDejaEnvoye=mysql_query($requeteVerificationDejaEnvoye);
				$donneesVerificationDejaEnvoye=mysql_fetch_array($retourVerificationDejaEnvoye);
				if($debug){
					echo "<br /><br />egaliteParfaite : ".$donneesVerificationDejaEnvoye['egaliteParfaite']."<br /><br />";
				}
				$champEgaliteParfaite=$donneesVerificationDejaEnvoye['egaliteParfaite'];
			}
			else{
				$champEgaliteParfaite=1;
			}
			if(mysql_num_rows($retourVerificationDejaEnvoye)>1 OR $champEgaliteParfaite=='0'){ //Si il y a plusieurs lignes ou que le champ egaliteParfaite est à 0, envoyer le mail.

				/* Envoi du mail */

				$boutMail="";
				for($j=1;$j<=$nbEquipesEgaliteParfaite;$j++){
					$boutMail.="<strong>".$nomEquipeEgaliteParfaite[$j]."</strong>";
					if($j!=$nbEquipesEgaliteParfaite){
						$boutMail.=" et ";
					}
				}
				$from = "From:no-reply@tchoukball.ch\n";
				$from .= "MIME-version: 1.0\n";
				$from .= "Content-type: text/html; charset= iso-8859-1\n";
				$destinataireMail ="technique@tchoukball.ch, webmaster@tchoukball.ch";
				mail($destinataireMail, "Égalité parfaite au championnat", "Les équipes ".$boutMail." sont à égalité parfaite au championnat.",$from);
				if($debug){
					echo "<br /><br />Un mail a été envoyé à ".$destinataireMail." vu qu'il y a une égalité parfaite et qu'un tirage au sort doit être effectué.</strong>";
				}
			}

			/* le champ egaliteParfaite dans la table Championnat_Equipes_Tours et établi à 1 pour différencier les équipes où l'on doit régler une égalité parfaite. */

			for($j=1;$j<=$nbEquipesEgaliteParfaite;$j++){
				$requeteDifferenciation="UPDATE Championnat_Equipes_Tours SET egaliteParfaite='1' WHERE saison=".$annee." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$noGroupe." AND idEquipe=".$idEquipeEgaliteParfaite[$j];
				if($debug){
					echo "<br />Mise en évidence des équipes à égalité parfaite dans la base de données : ".$requeteDifferenciation;
				}
				mysql_query($requeteDifferenciation);
			}
			if($debug){
				echo "<br />Mise du champ egaliteParfaite des équipes PAS à égalité à 0 : ".$requeteEquipesPasEgaliteParfaite;
			}

		}
		elseif(count($tableau[$k])==1){
			$groupeEgalite++; // Nouveau groupe a égalité
			$nouveauTableau[$groupeEgalite][1]=$tableau[$k][1];
		}
		else{
			echo "<br /><strong>ERREUR F1</strong><br />";
		}
	}
	$nouveauTableau[0]=$groupeEgalite;
	if($pasChangerClassement){
		$nouveauTableau=$tableau;
	}
	return $nouveauTableau;

}
?>
