<?
?>
<h3>
<? echo VAR_LANG_ETAPE_3; ?>
</h3>
<?
$matchArray = $_POST['matchArray'];
if(is_array($matchArray)){
	$saison = $_POST['saison'];
	$idCategorie = $_POST['idCategorie'];
	$idTour = $_POST['idTour'];
	$idGroupe = $_POST['idGroupe'];
	$i=0;
    while( list(,$idMatch) = each($matchArray) ){
        $requete =	"DELETE FROM Championnat_Matchs WHERE idMatch='$idMatch'";
		// echo $requete;
		mysql_query($requete);
		$i++;
    }
    if($i<=0){
        echo "<h3>Aucun match supprimé</h3>";
    }
    elseif($i=1){
        echo "<h4>Match correctement supprimé</h4>";	
    }
    else{
        echo "<h4>".$i." matchs correctement supprimés</h4>";	
    }
    // Redénomination des variables pour l'include suivant.
	$categorie = $idCategorie;
	$tour = $idTour;
	$groupe = $idGroupe;
	include('championnat.miseajour.equipes.tour.inc.php');
	// l'include ne sert à rien là car on ne peut pas mettre de score en ajoutant un match, donc cela ne change rien aux calculs des points.
}