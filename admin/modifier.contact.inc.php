<?
	statInsererPageSurf(__FILE__);
?>

<?
	$requeteSQL="SELECT *, p.adresse, p.ville, p.email, p.telephone FROM Personne p, ClubsFstb c WHERE p.id='".$modificationId."' AND p.idClub=c.id";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete A</H1>");
	$record = mysql_fetch_array($recordset);

	echo "<SCRIPT language='JavaScript'>
	 var couleurErreur; couleurErreur='#".VAR_LOOK_COULEUR_ERREUR_SAISIE."';
	 var couleurValide; couleurValide='#".VAR_LOOK_COULEUR_SAISIE_VALIDE."';
	 </SCRIPT>";
?>
<SCRIPT language='JavaScript'>

	function controlerSaisie(){

		var nbErreur;
		nbErreur=0;

		mesInfos.adresse.style.background=couleurValide;
		mesInfos.ville.style.background=couleurValide;

		<? include "includes/javascript.controle.telephone.inc.php"; ?>

		if(mesInfos.email.value != "" && (mesInfos.email.value.indexOf("@") < 1 || mesInfos.email.value.indexOf("@") >= (mesInfos.email.value.lastIndexOf(".")))){
			nbErreur++;
			mesInfos.email.style.background=couleurErreur;
		}
		else{
			mesInfos.email.style.background=couleurValide;
		}
		if(mesInfos.numPostal.value==""){
			mesInfos.numPostal.style.background=couleurValide;
		}
		else{
			if(isNaN(mesInfos.numPostal.value) || mesInfos.numPostal.value<1000 || mesInfos.numPostal.value.length!=4){
				nbErreur++;
				mesInfos.numPostal.style.background=couleurErreur;

			}
			else{
				mesInfos.numPostal.style.background=couleurValide;
			}
		}

		var epressionReguliereMotDePasse=new RegExp(["^[a-zA-Z0-9_]{8,}"]);
		var invaliditePassword = !epressionReguliereMotDePasse.test(mesInfos.nouveauPass.value) && mesInfos.nouveauPass.value.length!=0
		if(mesInfos.nouveauPass.value != mesInfos.nouveauPassBis.value || invaliditePassword){
			if(invaliditePassword) {
				alert("Les caractères spéciaux ne sont pas admis dans le mot de passe (sont également exclus les caractères à accents)");
			}
			mesInfos.nouveauPass.style.background=couleurErreur;
			mesInfos.nouveauPassBis.style.background=couleurErreur;
			nbErreur++;
		}
		else{
			mesInfos.nouveauPass.style.background=couleurValide;
			mesInfos.nouveauPassBis.style.background=couleurValide;
		}

		if(mesInfos.contactClub.checked && mesInfos.idClub.value==0){
			nbErreur++;
			mesInfos.idClub.style.background=couleurErreur;
		}
		else{
			mesInfos.idClub.style.background=couleurValide;
		}

		var dateN = new Date(mesInfos.annee.value,mesInfos.mois.value-1,mesInfos.jour.value);
		if(dateN.getFullYear() != mesInfos.annee.value || (dateN.getMonth() != mesInfos.mois.value-1) || dateN.getDate() != mesInfos.jour.value){
			nbErreur++;
			mesInfos.annee.style.background=couleurErreur;
			mesInfos.mois.style.background=couleurErreur;
			mesInfos.jour.style.background=couleurErreur;
		}
		else{
			mesInfos.annee.style.background=couleurValide;
			mesInfos.mois.style.background=couleurValide;
			mesInfos.jour.style.background=couleurValide;
		}

		return nbErreur==0;
	}
</SCRIPT>

<form name="mesInfos" class="adminForm" method="post" onSubmit="return controlerSaisie();" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>">
<fieldset>
	<label>Nom d'utilisateur</label>
	<p><? echo stripslashes($record["username"]);?></p>
	<?php
	if (isAdmin()) {
		?>
		<label>Niveau d'accès</label>
	    <p><?php echo $record["userLevel"]; ?></p>
		<?php
	}
	?>
	<label>Nom</label>
	<p><? echo stripslashes($record["nom"]);?></p>
	<label>Prénom</label>
	<p><? echo stripslashes($record["prenom"]);?></p>
</fieldset>
<fieldset>
	<label>Adresse</label>
	<input name="adresse" type="text" value="<? echo $record["adresse"];?>" size="35" maxlength="35">
	<label>Numéro postal</label>
	<input name="numPostal" type="text" value="<? echo $record["numPostal"]==0?"":$record["numPostal"];?>" size="35" maxlength="35">
	<label>Ville</label>
	<input name="ville" type="text" value="<? echo $record["ville"];?>" size="35" maxlength="35">
	<label>Tél. privé</label>
	<input name="telephone" type="text" value="<? echo $record["telephone"];?>" size="35" maxlength="35">
	<label>Tél. mobile</label>
	<input name="portable" type="text" value="<? echo $record["portable"];?>" size="35" maxlength="35">
	<label>Email</label>
	<input name="email" type="text" value="<? echo $record["email"];?>" size="35" maxlength="80" autocomplete="off">
</fieldset>
<fieldset>
	<label>Club</label>
		<?
			afficherListeClubs($record["idClub"], "id");
		?>
	<label>Date de naissance</label>
	<div class="birthDate">
		<?
		echo "<select name='jour'>";
		echo modif_liste_jour(jour($record["dateNaissance"]));
		echo "</select>.";

		echo "<select name='mois'>";
		echo modif_liste_mois(mois($record["dateNaissance"]));
		echo "</select>.";

		echo "<select name='annee'>";
		for ($i = 1900; $i <= date('Y'); $i++){
			if($i == annee($record["dateNaissance"])){
		    echo "<option selected value='$i'>$i</option>\n";
			}
			else{
			    echo "<option value='$i'>$i</option>\n";
			}
		}
		echo "</select>";
		?>
	</div>
</fieldset>
<fieldset>
	<span class="infobulle">Minimum 8 caractères</span>
	<label>Nouveau mot de passe</label>
	<input name="nouveauPass" type="password" maxlength="255" size="35" autocomplete="off">
	<label>Encore une fois</label>
	<input name="nouveauPassBis" type="password" maxlength="255" size="35" autocomplete="off">
</fieldset>
<input type="hidden" name="action" value="modifierContact">
<input type="hidden" name="idPersonne" value="<? echo $modificationId;?>">
<input type="submit" value="Modifier">
</form>