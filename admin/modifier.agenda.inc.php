<div class="modifierAgenda">
<?php
		statInsererPageSurf(__FILE__);

		if(isset($_POST["action"]) && $_POST["action"]=="modifier"){
			include "modifier.un.event.inc.php";
		}
		else{
			// doit-on modifier la news...
			if(isset($_POST["action"]) && $_POST["action"]=="modifierEvent"){
				$dateDebut = "$debutAnnee-$debutMois-$debutJour";
				$dateFin = "$finAnnee-$finMois-$finJour";
				$heureDebut = "$debutHeure:$debutMinute:00";
				$heureFin = "$finHeure:$finMinute:00";

				$nbErreur=0;

				// test de la validité de la date de début
				if(!checkdate($debutMois,$debutJour,$debutAnnee)){
					echo "<h4>date incorrecte : debut = $debutJour.$debutMois.$debutAnnee</h4>";
					$nbErreur++;
				}

				// test de la validité de la date de fin
				if(!checkdate($finMois,$finJour,$finAnnee)){
					echo "<h4>date incorrecte : fin = $finJour.$finMois.$finAnnee</h4>";
					$nbErreur++;
				}

				// teste de la chronologie des dates
				if(date1_sup_date2($dateDebut, $dateFin)){
					echo "<h4>Chronologie des dates non respectée : debut = $debutJour.$debutMois.$debutAnnee, fin = $finJour.$finMois.$finAnnee</h4>";
					$nbErreur++;
				}

				// test si les heures sont dans un ordre chronologique
				if(($debutHeure > $finHeure) ||
					($debutHeure == $finHeure && $debutMinute > $finMinute)){
					echo "<h4>Chronologie des heures non respectée : debut = $heureDebut, fin = $heureFin</h4>";
					$nbErreur++;
				}
				// test si l'event doit être affiché ou pas
				if(isset($affiche)){
				    $ouvert=1;
				}
				else{
				    $ouvert=0;
				}
				$idEvent = $_POST["idEvent"];
				$description=addslashes($description);

				if($nbErreur==0){
					$requeteSQL = "UPDATE `Agenda_Evenement` SET `description` = '$description',
							`lieu` = '$lieu',
							`dateDebut` = '$dateDebut',
							`dateFin` = '$dateFin',
							`heureDebut` = '$heureDebut',
							`heureFin` = '$heureFin',
							`id_TypeEve` = '$type',
							`affiche` = '$ouvert',
							`utilisateur` = '".$_SESSION["__nom__"]." ".$_SESSION["prenom"]."' WHERE `NumeroEvenement` = '$idEvent'";			//echo "<BR>Voici la requete : $requeteInsertion<BR>";

					mysql_query($requeteSQL) or die ("<h4>Erreur, contacter le webmaster pour une erreur d'insertion de date</h4>");
					echo "<h4>Modification effectuée avec succès</h4><br />";
				}
			}

			include "modifier.selection.event.inc.php";
		}
?>
</div>
