<?php
?>
<h3>
<?php echo VAR_LANG_ETAPE_2; ?>
</h3>
<?php
if(!isset($_GET['saison']) OR !isset($_GET['idCat']) OR !isset($_GET['idTour']) OR !isset($_GET['idGroupe'])){
	echo "Erreur: il manque des informations.";
}
else{
	$saison = $_GET['saison'];
	$idCategorie = $_GET['idCat'];
	$idTour = $_GET['idTour'];
	$idGroupe = $_GET['idGroupe'];

	$debutSaison = $saison;
	$finSaison = $saison+1;
	$nomSaison = $debutSaison."-".$finSaison;

	if($idCategorie==-1){
	   $nomCategorie = " sans ligues";
    }
    else{
        $requete = "SELECT categorie".$_SESSION['__langue__']." FROM Championnat_Categories WHERE idCategorie=".$idCategorie."";
        $retour = mysql_query($requete);
        $donnees = mysql_fetch_array($retour) or die($requete."<br />".mysql_error());
        $nomCategorie = " de ".$donnees["categorie".$_SESSION['__langue__']]."";
    }

    if($idTour==2000){ // Promotion Relegation
        $nomTour = "";
    }
    elseif($idTour==3000 OR $idTour==4000){ //Playout ou Playoff
        $requete = "SELECT tour".$_SESSION['__langue__']." FROM Championnat_Types_Tours WHERE idTour=".$idTour."";
        $retour = mysql_query($requete);
        $donnees = mysql_fetch_array($retour);
        $nomTour = " de ".$donnees["tour".$_SESSION['__langue__']]."";
    }
    else{
        $requete = "SELECT tour".$_SESSION['__langue__']." FROM Championnat_Types_Tours WHERE idTour=".$idTour."";
        $retour = mysql_query($requete);
        $donnees = mysql_fetch_array($retour) or die($requete."<br />".mysql_error());
        $nomTour = " du ".$donnees["tour".$_SESSION['__langue__']]."";

    }
    if($idGroupe==0){
        $nomGroupe = "";
    }
    else{
        $nomGroupe = " du groupe ".$idGroupe."";
    }
	echo "<h4>Matchs de la saison ".$nomSaison.$nomCategorie.$nomTour.$nomGroupe."</h4><br />";
    echo "<table class='tableauModifierMatch'>";
	   echo "<tr>";
	       echo "<th>Match</th>";
	       echo "<th>".VAR_LANG_JOURNEE."</th>";
	   echo "</tr>";
	$requete = "SELECT * FROM Championnat_Matchs WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$idGroupe." ORDER BY journee";
	// echo $requete;
	$retour = mysql_query($requete);
	$i=1;
	while($donnees = mysql_fetch_array($retour)){

	   // Choix de la couleur de fond.
        if($donnees['dateReportFin']!="0000-00-00"){
            if($donnees['dateReportFin'] < date('Y\-m\-d') AND $donnees['pointsA']==0 AND $donnees['pointsB']==0){
                $couleur = "#ffb0a8";
            }
            elseif($donnees['dateReportFin'] < date('Y\-m\-d') AND ($donnees['pointsA']!=0 OR $donnees['pointsB']!=0)){
                $couleur = "#b7ffb4";
            }
            else{
                if($i%2==0){
                    $couleur = "#dbf4ff";
                }
                else{
                    $couleur = "white";
                }
            }
        }
        elseif($donnees['dateFin']!="0000-00-00"){
            if($donnees['dateFin'] < date('Y\-m\-d') AND $donnees['pointsA']==0 AND $donnees['pointsB']==0){
                $couleur = "#ffb0a8";
            }
            elseif($donnees['datFin'] < date('Y\-m\-d') AND ($donnees['pointsA']!=0 OR $donnees['pointsB']!=0)){
                $couleur = "#b7ffb4";
            }
            else{
                if($i%2==0){
                    $couleur = "#dbf4ff";
                }
                else{
                    $couleur = "white";
                }
            }

        }
        else{ // la fin du match est programmé à 0000-00-00
            $couleur = "yellow";
        }
        echo "<tr style='background-color: ".$couleur.";'>";
            // Le premier match de la liste est sélectionné par défaut.
            if($i==1){
                $checked = "checked='checked'";
            }
            else{
                $checked = "";
            }
            $requeteA = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=".$donnees['equipeA']."";
            $retourA = mysql_query($requeteA);
            $donneesA = mysql_fetch_array($retourA);
            $nomEquipeA = $donneesA['equipe'];
            $requeteB = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=".$donnees['equipeB']."";
            $retourB = mysql_query($requeteB);
            $donneesB = mysql_fetch_array($retourB);
            $nomEquipeB = $donneesB['equipe'];
            echo "<td class='center'><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&idMatch=".$donnees['idMatch']."'>".$nomEquipeA."-".$nomEquipeB."</a></td>";
            echo "<td class='center'>".$donnees['journee']."</td>";
        echo "</tr>";
        $i++;
    }
    echo "</table>";
}
?>
