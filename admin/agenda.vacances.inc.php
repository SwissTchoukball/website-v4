<div><a href="?menuselection=<? echo $_GET['menuselection']; ?>&smenuselection=<? echo $_GET['smenuselection']; ?>&ajouter"><img src="admin/images/ajouter.png" alt="Ajouter des vacances" /> Ajouter des vacances</a></div>
<div><a href="?menuselection=<? echo $_GET['menuselection']; ?>&smenuselection=<? echo $_GET['smenuselection']; ?>"><img src="admin/images/liste.png" alt="Liste des vacances" /> Liste des vacances</a></div>
<form name="formRechercheVacances" action="?menuselection=<? echo $_GET['menuselection']; ?>&smenuselection=<? echo $_GET['smenuselection']; ?>" method="post">
	<div><img src="admin/images/rechercher.png" alt="Rechercher des vacances" /> <input type="search" name="rechercheVacances" /></div><br />
</form>

<?

if(isset($_GET['modifier']) OR isset($_GET['ajouter'])){
	if(isset($_GET['modifier'])){
		$requeteAModifier="SELECT Calendrier_Vacances.id AS idVacances, idCanton, Calendrier_Vacances.nom AS nomVacances, dateDebut, dateFin FROM Calendrier_Vacances, Calendrier_Cantons WHERE Calendrier_Vacances.id=".$_GET['modifier']." AND Calendrier_Cantons.id=idCanton";
		$retourAModifier=mysql_query($requeteAModifier);
		$donneesAModifier=mysql_fetch_array($retourAModifier);
		$idVacances=$donneesAModifier['idVacances'];
		$idCanton=$donneesAModifier['idCanton'];
		$nomVacances=stripslashes($donneesAModifier['nomVacances']);
		$dateDebut=$donneesAModifier['dateDebut'];
		$dateFin=$donneesAModifier['dateFin'];
		$jourDebut=substr($dateDebut,8,2);
		$moisDebut=substr($dateDebut,5,2);
		$anneeDebut=substr($dateDebut,0,4);
		$jourFin=substr($dateFin,8,2);
		$moisFin=substr($dateFin,5,2);
		$anneeFin=substr($dateFin,0,4);

	}
	elseif(isset($_GET['ajouter'])){
		$idVacances="";
		$idCanton=13;
		$nomVacances="";
		$jourDebut=date('d');
		$moisDebut=date('m');
		$anneeDebut=date('Y');
		$jourFin=date('d');
		$moisFin=date('m');
		$anneeFin=date('Y');
	}
	?>

	<form class="formulaireAligne" name="editerVacances" method="post" action="?menuselection=<? echo $_GET['menuselection']; ?>&smenuselection=<? echo $_GET['smenuselection']; ?>">
		<fieldset>
			<label for="nom">Nom : </label>
			<input type="text" id="nom" name="nom" size="30" autofocus="autofocus" value="<? echo $nomVacances ?>" /><br /><br />
			<label> Date de d�but : </label>
			<select name="jourDebut">
				<?
				for($jour=1;$jour<=31;$jour++){
					if($jourDebut==$jour){
						$selected=" selected='selected'";
					}
					else{
						$selected="";
					}
					echo "<option value='".$jour."'".$selected.">".$jour."</option>";
				}
				?>
			</select>
			<select name="moisDebut">
				<?
				for($mois=1;$mois<=12;$mois++){
					if($moisDebut==$mois){
						$selected=" selected='selected'";
					}
					else{
						$selected="";
					}
					echo "<option value='".$mois."'".$selected.">".$mois."</option>";
				}
				?>
			</select>
			<select name="anneeDebut">
				<?
				for($annee=2009;$annee<=2030;$annee++){
					if($anneeDebut==$annee){
						$selected=" selected='selected'";
					}
					else{
						$selected="";
					}
					echo "<option value='".$annee."'".$selected.">".$annee."</option>";
				}
				?>
			</select><br /><br />
			<label> Date de fin : </label>
			<select name="jourFin">
				<?
				for($jour=1;$jour<=31;$jour++){
					if($jourFin==$jour){
						$selected=" selected='selected'";
					}
					else{
						$selected="";
					}
					echo "<option value='".$jour."'".$selected.">".$jour."</option>";
				}
				?>
			</select>
			<select name="moisFin">
				<?
				for($mois=1;$mois<=12;$mois++){
					if($moisFin==$mois){
						$selected=" selected='selected'";
					}
					else{
						$selected="";
					}
					echo "<option value='".$mois."'".$selected.">".$mois."</option>";
				}
				?>
			</select>
			<select name="anneeFin">
				<?
				for($annee=2009;$annee<=2030;$annee++){
					if($anneeFin==$annee){
						$selected=" selected='selected'";
					}
					else{
						$selected="";
					}
					echo "<option value='".$annee."'".$selected.">".$annee."</option>";
				}
				?>
			</select><br /><br />
			<label for="idCanton">Canton : </label>
			<select id="idCanton" name="idCanton">
				<?
				$requeteCantons="SELECT * FROM Calendrier_Cantons WHERE id!=100 ORDER BY id";
				$retourCantons=mysql_query($requeteCantons);
				while($donneesCantons=mysql_fetch_array($retourCantons)){
					if($idCanton==$donneesCantons['id']){
						$selected=" selected='selected'";
					}
					else{
						$selected="";
					}
					echo "<option value='".$donneesCantons['id']."'".$selected.">".$donneesCantons['nom']." (".$donneesCantons['abreviation'].")</option>";
				}
				?>
			</select>
			<input type="hidden" name="idVacances" value="<? echo $idVacances; ?>" /><br />
			<label>&nbsp;</label>
			<input type="submit" value="Enregistrer" />
		</fieldset>
	</form>

	<?

}
else{
	if(isset($_POST['idVacances'])){
		if($_POST['idVacances']==""){ // Ajout
			$requeteAjout="INSERT INTO Calendrier_Vacances (`id`, `nom`, `dateDebut`, `dateFin`, `idCanton`) VALUES (NULL, '".addslashes($_POST['nom'])."', '".$_POST['anneeDebut']."-".$_POST['moisDebut']."-".$_POST['jourDebut']."', '".$_POST['anneeFin']."-".$_POST['moisFin']."-".$_POST['jourFin']."', '".$_POST['idCanton']."')";
			mysql_query($requeteAjout);
			printSuccessMessage("Vacances correctement ajout�es.");
		}
		else{ // Modification
			$requeteModification="UPDATE Calendrier_Vacances SET nom='".addslashes($_POST['nom'])."', dateDebut='".$_POST['anneeDebut']."-".$_POST['moisDebut']."-".$_POST['jourDebut']."', dateFin='".$_POST['anneeFin']."-".$_POST['moisFin']."-".$_POST['jourFin']."', idCanton='".$_POST['idCanton']."' WHERE id=".$_POST['idVacances'];
			//echo $requeteModification;
			mysql_query($requeteModification);
			echo "<p class='succes'>Vacances correctement modifi�es.</p><br />";
		}
	}
	if(isset($_GET['supprimer'])){
		$requeteSuppression="DELETE FROM Calendrier_Vacances WHERE id=".$_GET['supprimer']." LIMIT 1";
		mysql_query($requeteSuppression);
		echo "<p class='succes'>Vacances correctement supprim�es.</p><br />";
	}
	if(isset($_POST['rechercheVacances'])){
		$termeRecherche=$_POST['rechercheVacances'];
		$termeRecherche = strtr($termeRecherche, "��������������������", "aeiouaeiouaeiouaeiou");
		$termeRecherche=htmlentities($termeRecherche);
		echo "<p>Terme recherch� : \"".$termeRecherche."\"";
		$triRecherche=" AND (`Calendrier_Vacances`.`nom` LIKE CONVERT( _utf8 '%".$termeRecherche."%' USING latin1) OR `Calendrier_Cantons`.`nom` LIKE CONVERT( _utf8 '%".$termeRecherche."%' USING latin1) OR `Calendrier_Cantons`.`abreviation` LIKE CONVERT( _utf8 '%".$termeRecherche."%' USING latin1) COLLATE latin1_swedish_ci)";
	}
	else{
		$triRecherche="";
	}
	$requete="SELECT Calendrier_Vacances.nom AS nom, abreviation, Calendrier_Vacances.id AS id, dateDebut, dateFin FROM Calendrier_Vacances, Calendrier_Cantons WHERE Calendrier_Vacances.idCanton=Calendrier_Cantons.id AND Calendrier_Vacances.nom!='false'".$triRecherche." ORDER BY dateDebut, Calendrier_Vacances.nom, Calendrier_Cantons.nom";
	$retour=mysql_query($requete);
	$nbVacancesAffiche=mysql_num_rows($retour);
	if($nbVacancesAffiche==0){
		echo "<h4>Il n'y a rien � afficher.</h4>";
	}
	else{
		?>
		<table class="listeTableau">
			<tr>
				<th>Nom</th>
				<th>Canton</th>
				<th>Dates</th>
				<th>Modifier</th>
				<th>Supprimer</th>
			</tr>
		<?
		while($donnees=mysql_fetch_array($retour)){
			if (dateAvantAujourdhui($donnees['dateFin'])) {
				$attributsLigne = 'class="depasse"';
			} elseif (periodeContientAujourdhui($donnees['dateDebut'], $donnees['dateFin'])) {
				$attributsLigne = 'class="encours"';
			} else {
				$attributsLigne = 'class="avenir"';
			}
			?>
			<tr <? echo $attributsLigne; ?>>
				<td><? echo $donnees['nom']; ?></td>
				<td><? echo $donnees['abreviation']; ?></td>
				<td><? echo date_sql2date($donnees['dateDebut'])." - ".date_sql2date($donnees['dateFin']); ?></td>
				<td class="modifier"><a href="?menuselection=<? echo $_GET['menuselection']; ?>&smenuselection=<? echo $_GET['smenuselection']; ?>&modifier=<? echo $donnees['id']; ?>"><img src="/admin/images/modifier.png" alt="modifier" /></a></td>
				<td class="supprimer"><a href="?menuselection=<? echo $_GET['menuselection']; ?>&smenuselection=<? echo $_GET['smenuselection']; ?>&supprimer=<? echo $donnees['id']; ?>" onclick='return confirm("Vous &ecirc;tes sur le point de supprimer ces vacances \n OK pour supprimer, Annuler pour abandonner.")'><img src="/admin/images/supprimer.png" alt="supprimer" /></a></td>
			</tr>
			<?
		}
		?>
		</table>
		<?
	}
}
?>
<script>
	$(function(){
		var jourDebut = $('select[name=jourDebut]');
		var jourFin = $('select[name=jourFin]');
		var moisDebut = $('select[name=moisDebut]');
		var moisFin = $('select[name=moisFin]');
		var anneeDebut = $('select[name=anneeDebut]');
		var anneeFin = $('select[name=anneeFin]');

		var diffJours = jourFin.val() - jourDebut.val();
		var diffMois = moisFin.val() - moisDebut.val();
		var diffAnnees = anneeFin.val() - anneeDebut.val();

		jourDebut.change(function(){
			jourFin.val(parseInt(jourDebut.val()) + diffJours);
		});

		moisDebut.change(function(){
			moisFin.val(parseInt(moisDebut.val()) + diffMois);
		});

		anneeDebut.change(function(){
			anneeFin.val(parseInt(anneeDebut.val()) + diffAnnees);
		});
	});
</script>