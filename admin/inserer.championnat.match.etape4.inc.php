<?php
?>
<h3>
<?php echo VAR_LANG_ETAPE_4; ?>
</h3>
<?php
if(!isset($_POST['nbMatchs'])){
	echo"Erreur: il manque des informations.";
	$nbMatchs=0;
}
else{
	$nbMatchs = $_POST['nbMatchs'];
	$saison = $_POST['saison'];
	$idCategorie = $_POST['idCategorie'];
	$idTour = $_POST['idTour'];
	$idGroupe = $_POST['idGroupe'];

    for($k=1;$k<=$nbMatchs;$k++){
    $idTypeMatchValide = false;

        if($idCategorie == 0){ // Promotion / Relegation
            $idTypeMatch = 1000;
            $idTypeMatchValide = true;
        }
        elseif($idTour == 1 OR $idTour == 2 OR $idTour == 3 OR $idTour == 4){
            $idTypeMatch = 0;
            $idTypeMatchValide = true;
        }
        elseif(!isset($_POST['typeMatch'.$k])){
            echo "<h4>Erreur: type de match indéfini</h4>";
            $idTypeMatchValide = false;
        }
        else{
            $idTypeMatch = $_POST['typeMatch'.$k];
            $idTypeMatchValide = true;
        }
        if(isset($_POST['necessiteDefraiementArbitre'])){
        	$necessiteDefraiementArbitre=1;
        }
        else{
        	$necessiteDefraiementArbitre=0;
        }

        $idLieu = isValidVenueID($_POST['idLieu'.$k]) ? $_POST['idLieu'.$k] : 'NULL';

        if($idTypeMatchValide){
            $requeteA = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=".$_POST['equipeA'.$k]."";
            $retourA = mysql_query($requeteA);
            $donneesA = mysql_fetch_array($retourA);
            $nomEquipeA = $donneesA['equipe'];

            $requeteB = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=".$_POST['equipeB'.$k]."";
            $retourB = mysql_query($requeteB);
            $donneesB = mysql_fetch_array($retourB);
            $nomEquipeB = $donneesB['equipe'];

            //Dernière modification (9.1.15) (pas vérifié si ça fonctionnait) : utilisation de liste de lieux plutôt que d'entrer le lieu manuellement.
            $requete = "INSERT INTO `Championnat_Matchs` (`idMatch`,`equipeA`,`equipeB`,`pointsA`,`pointsB`,`forfait`,`saison`,`idCategorie`,`idTour`,`idTypeMatch`,`noGroupe`,`journee`,`dateDebut`,`dateFin`,`heureDebut`,`heureFin`,`dateReportDebut`,`dateReportFin`,`heureReportDebut`,`heureReportFin`,`necessiteDefraiementArbitre`,`idLieu`,`utilisateur`) VALUES('', ".$_POST['equipeA'.$k].", ".$_POST['equipeB'.$k].", 0, 0, 0, ".$saison.", ".$idCategorie.", ".$idTour.", ".$idTypeMatch.", ".$idGroupe.", ".$_POST['journee'.$k].", '".$_POST['debutAnnee'.$k]."-".$_POST['debutMois'.$k]."-".$_POST['debutJour'.$k]."', '".$_POST['finAnnee'.$k]."-".$_POST['finMois'.$k]."-".$_POST['finJour'.$k]."', '".$_POST['debutHeure'.$k].":".$_POST['debutMinute'.$k].":00','".$_POST['finHeure'.$k].":".$_POST['finMinute'.$k].":00', '0000-00-00', '0000-00-00', '00:00:00', '00:00:00', ".$necessiteDefraiementArbitre.", '".$idLieu."', '".$_SESSION['__prenom__'].$_SESSION['__nom__']."')";
            //echo $requete."<br />";
            mysql_query($requete) or die("Erreur, le match ".$nomEquipeA." - ".$nomEquipeB." et suivants n'ont pas été ajouté.<br />Erreur SQL : ".mysql_error());
            echo $nomEquipeA." - ".$nomEquipeB." : OK !<br />";
        }
    }
    // Redénomination des variables pour l'include suivant.
	$categorie = $idCategorie;
	$tour = $idTour;
	$groupe = $idGroupe;
	include('championnat.miseajour.equipes.tour.inc.php');
	// l'include ne sert à rien là car on ne peut pas mettre de score en ajoutant un match, donc cela ne change rien aux calculs des points.
}
?>
<p class="center"><a href="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>">Insérer d'autres matchs</a></p>

