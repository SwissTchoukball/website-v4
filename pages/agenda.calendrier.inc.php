<script lang="javascript">
	function showHideSubscriptions() {
		var subscriptionsList = document.getElementById("abonnementsCalendrier");
		var switchLink = document.getElementById("showHideSubscriptions");
		if (subscriptionsList.style.display == "none") {
			subscriptionsList.style.display = "block";
			switchLink.innerHTML = "Masquer les abonnements";
		} else {
			subscriptionsList.style.display = "none";
			switchLink.innerHTML = "Afficher les abonnements";
		}
	}
</script>
<?php
statInsererPageSurf(__FILE__);
if(isset($_GET['idEvenement'])){
	include('pages/agenda.evenement.inc.php');
}
else{

	// On fait un listing du nom des équipes avec leur ID.
	$requeteEquipes="SELECT * FROM Championnat_Equipes WHERE idEquipe!=11 ORDER BY idEquipe";
	$retourEquipes=mysql_query($requeteEquipes);
	$tableauEquipes=array();
	while($donneesEquipes=mysql_fetch_array($retourEquipes)){
		$tableauEquipes[$donneesEquipes['idEquipe']]=$donneesEquipes['equipe'];
	}

	// On défini quel mois afficher
	if(isset($_GET['mois']) AND $_GET['mois']>0 AND $_GET['mois']<=12){
		$mois=$_GET['mois'];
	}
	else{
		$mois=date('n');
	}
	// On défini quelle année
	if(isset($_GET['annee']) AND preg_match('#[0-9]{4}#',$_GET['annee'])){
		$annee=$_GET['annee'];
	}
	else{
		$annee=date('Y');
	}
	// On défini si l'affichage par semaine est activé
	if(isset($_GET['semaine']) AND isset($_GET['jour'])){
		$affichageSemaine=true;
	}
	else{
		$affichageSemaine=false;
	}
	if(isset($_GET['jour'])){
		$jour=$_GET['jour'];
		$affichageJour=true;
	}
	else{
		$jour=date('j');
		$affichageJour=false;
	}


	$timestampPremierJourMois=mktime(0,0,0,$mois,1,$annee); // timestamp du 1er $mois $annee à minuit.
	$timestampPremierJourMoisSuivant=mktime(0,0,0,$mois+1,1,$annee); // timestamp du 1er $mois+1 $annee à minuit.
	$timestampPremierJourMoisPrecedant=mktime(0,0,0,$mois-1,1,$annee); // timestamp du 1er $mois-1 $annee à minuit.

	//On défini le nombre de jour dans le mois (actuel, suivant et précédant)
	$nombreJoursMois=date('t', $timestampPremierJourMois);
	$nombreJoursMoisSuivant=date('t', $timestampPremierJourMoisSuivant);
	$nombreJoursMoisPrecedant=date('t', $timestampPremierJourMoisPrecedant);



	//On défini le jour de la semaine du premier jour du mois (0 (pour dimanche) à 6 (pour samedi))
	$jourSemainePremierJourMois=date('w', $timestampPremierJourMois);
	//On transforme en 1 (pour Lundi) à 7 (pour Dimanche)
	if($jourSemainePremierJourMois==0){
		$jourSemainePremierJourMois=7;
	}

	//On défini le nombre de cases vides la première semaine du mois
	$nombreCasesVidesPremiereSemaine=7-(8-$jourSemainePremierJourMois);


	$timestampDernierJourMois=mktime(0,0,0,$mois,$nombreJoursMois,$annee); // timestamp du dernier jour $mois $annee à minuit.

	//On défini le jour de la semaine du dernier jour du mois (0 (pour dimanche) à 6 (pour samedi))
	$jourSemaineDernierJourMois=date('w', $timestampDernierJourMois);
	//On transforme en 1 (pour Lundi) à 7 (pour Dimanche)
	if($jourSemaineDernierJourMois==0){
		$jourSemaineDernierJourMois=7;
	}

	//On défini le nombre de cases vides la dernière semaine du mois
	$nombreCasesVidesDerniereSemaine=7-$jourSemaineDernierJourMois;

	//On défini le premier jour de la semaine
	$timestampJour=mktime(0,0,0,$mois,$jour,$annee); // timestamp du $jour $mois $annee à minuit.
	$jourSemaineJour=date('w', $timestampJour);
	$numeroSemaine=date('W', $timestampJour);
	//On transforme en 1 (pour Lundi) à 7 (pour Dimanche)
	if($jourSemaineJour==0){
		$jourSemaineJour=7;
	}
	$jourAReculer=$jourSemaineJour-1;
	$jourPremierJourSemaine=$jour-$jourAReculer;


	//Variables
	$affichageParJour=false;
	$affichageParSemaine=true;


	/* //Désactivation de la sélection par semaine, quasi pas utilisée et buggée quand on est sur une semaine chevauchant deux mois.
	if(isset($_GET['semaine'])){
		$classSelectionSemaine="boutonAfficherSemaineSelected";
		$classSelectionMois="boutonAfficherMois";
	}
	else{
		$classSelectionSemaine="boutonAfficherSemaine";
		$classSelectionMois="boutonAfficherMoisSelected";
	}
	?>
	<p>
		<a href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&affichage=calendrier&mois=<?php echo $mois; ?>&annee=<?php echo $annee; ?>" title="Affichage par mois">
			<img class="<?php echo $classSelectionMois; ?>" src="pictures/spacer.gif" alt="Bouton pour afficher par mois" />
		</a><a href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&affichage=calendrier&semaine&jour=<?php echo $jourPremierJourSemaine; ?>&mois=<?php echo $mois; ?>&annee=<?php echo $annee; ?>" title="Affichage par semaine">
			<img class="<?php echo $classSelectionSemaine; ?>" src="pictures/spacer.gif" alt="Bouton pour afficher par semaine" />
		</a>
	</p>
	<?php
	*/
	?>
	<h4>Abonnements</h4>
	<p><strong>Abonnez-vous avec votre ordinateur ou votre smartphone aux calendriers de <?php echo VAR_LANG_ASSOCIATION_NAME_ARTICLE; ?> !</strong></p>
	<p><a id="showHideSubscriptions" href="#" onclick="showHideSubscriptions();">Afficher les abonnements</a></p>
	<ul id="abonnementsCalendrier" style="display: none;">
		<li><a href="webcal://<?php echo $_SERVER['HTTP_HOST']; ?>/fstb-calendar.php">Tous les autres événements sauf le championnat</a></li>
		<li><a href="webcal://<?php echo $_SERVER['HTTP_HOST']; ?>/fstb-calendar.php?championnat">Matchs de championnat</a></li>
		<li><a href="webcal://<?php echo $_SERVER['HTTP_HOST']; ?>/fstb-calendar.php?entrainements">Entraînements</a></li>
		<?php
		$getTeams = mysql_query("SELECT idEquipe, equipe FROM Championnat_Equipes WHERE actif=1 ORDER BY equipe");
		while ($team = mysql_fetch_assoc($getTeams)) {
			?>
			<li><a href="webcal://<?php echo $_SERVER['HTTP_HOST']; ?>/fstb-calendar.php?championnat&equipe=<?php echo $team['idEquipe']; ?>">Matchs de <?php echo $team['equipe']; ?></a></li>
			<?php
		}
		?>
	</ul>

	<?php

	$requeteCategories="SELECT * FROM Calendrier_Categories ORDER BY id";
	$retourCategories=mysql_query($requeteCategories);
	$categorieCochee=array();
	while($donneesCategories=mysql_fetch_array($retourCategories)){
		if(isset($_POST['envoiSelectionCategories'])){
			if(isset($_POST['categorie'.$donneesCategories['id']])){
				$categorieCochee[$donneesCategories['id']]=true;
			}
		}
		else{
			$categorieCochee[$donneesCategories['id']]=true;
		}
		$categorieCochee['max']=$donneesCategories['id'];
	}

	// Légende des catégories
	echo '<h4>' . VAR_LANG_CATEGORIES . '</h4>';

	$requeteCategories="SELECT * FROM Calendrier_Categories ORDER BY nom";
	$retourCategories=mysql_query($requeteCategories);
	$c=1;
	$listchkbx=array();
	echo "<form class='selectionCategoriesCalendrier' id='selectionCategoriesCalendrier' action='' method='post'>";
	while($donneesCategories=mysql_fetch_array($retourCategories)){
		if($c!=1){
			echo "&nbsp;&nbsp;&nbsp;";
		}
		if($categorieCochee[$donneesCategories['id']]){
			$checked=" checked='checked'";
		}
		else{
			$checked="";
		}
		echo "<input type='checkbox' name='categorie".$donneesCategories['id']."' id='categorie".$donneesCategories['id']."'".$checked." /> ";
		echo "<label for='categorie".$donneesCategories['id']."' style='color: #".$donneesCategories['couleur']."; font-weight: bold;'>".$donneesCategories['nom']."</label>";
		$listchkbx[$c]="categorie".$donneesCategories['id'];
		$c++;
	}
	?>
	<script language="javascript">
		function checkAll(mstrchkbx){
			if(mstrchkbx.checked){
				<?php
				for($k=1;$k<$c;$k++){ // Je met $k<$c et pas $k<=$c car il y a un $c de plus que de catégories.
					?>
					document.getElementById('categorie<?php echo $k; ?>').checked=true;

					<?php
				}
				?>
			}
			else{
				<?php
				for($k=1;$k<$c;$k++){ // Je met $k<$c et pas $k<=$c car il y a un $c de plus que de catégories.
					?>
					document.getElementById('categorie<?php echo $k; ?>').checked=false;

					<?php
				}
				?>
			}
		}
	</script>
	<?php
	echo "<input type='submit' name='envoiSelectionCategories' value='Trier' />&nbsp;&nbsp;&nbsp;
	<input type='checkbox' id='masterCheckbox' onclick='checkAll(this);' checked='checked' /><label for='masterCheckbox'>Tout (dé)cocher</label>";
	echo "</form>";

	if($affichageSemaine){ // Affichage par semaine
		include('pages/agenda.semaine.inc.php');
	}
	elseif($affichageJour){ // Affichage par jour
		include('pages/agenda.jour.inc.php');
	}
	else{ // Affichage par mois
		include('pages/agenda.mois.inc.php');
	}
}
?>
