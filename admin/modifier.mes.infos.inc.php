<?php
	statInsererPageSurf(__FILE__);
?>
<div class="modifierMesInfos">
<?php
	if ($_POST["action"]=="modifier"){

		$requeteSQL = "SELECT * FROM `Personne` WHERE `Personne`.`nom`='".addslashes($_SESSION["__nom__"])."' AND `Personne`.`prenom`='".addslashes($_SESSION["__prenom__"])."'";

		$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");
		$record = mysql_fetch_array($recordset);

		$requeteModifierInfos = "UPDATE `Personne` SET `adresse`='".$_POST["adresse"]."',
																							     `numPostal`='".$_POST["numPostal"]."',
																									 `ville`='".$_POST["ville"]."',
																									 `telephone`='".$_POST["telephone"]."',
																									 `portable`='".$_POST["portable"]."',
																									 `email`='".$_POST["email"]."',
																									 `idClub`='".$_POST["idClub"]."',
																									 `dateNaissance`='".$_POST["annee"]."-".$_POST["mois"]."-".$_POST["jour"]."'
																				WHERE `Personne`.`id`='".$record["id"]."'";
		mysql_query($requeteModifierInfos);

		if($_POST["ancienPass"]!="" || $_POST["nouveauPass"]!=""){
			if(md5($_POST["ancienPass"])==$record["password"]){
				$requeteModifierMotDePasse = "UPDATE `Personne` SET `password`='".md5($_POST["nouveauPass"])."' WHERE `Personne`.`id`='".$record["id"]."'";
				mysql_query($requeteModifierMotDePasse);
				echo "<h4>Modification du mot de passe r�ussi</h4>";
			}
			else{
				echo "<h4>Votre ancien mot de passe n'est pas valide, impossible de modifier votre mot de passe</h4>";
			}
		}
	}
?>



<?php
	$requeteSQL = "SELECT * FROM `Personne` WHERE `Personne`.`nom`='".addslashes($_SESSION["__nom__"])."' AND `Personne`.`prenom`='".addslashes($_SESSION["__prenom__"])."'";

	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	$record = mysql_fetch_array($recordset);
	/*
	action="<?php echo VAR_HREF_PATH_ADMIN; ?>enregistrer.modification.infos.inc.php"
	*/
	echo "<SCRIPT language='JavaScript'>
	 var couleurErreur; couleurErreur='#".VAR_LOOK_COULEUR_ERREUR_SAISIE."';
	 var couleurValide; couleurValide='#".VAR_LOOK_COULEUR_SAISIE_VALIDE."';
	 </SCRIPT>";
	//<SCRIPT language='JavaScript'>
?>
<SCRIPT language='JavaScript'>

	function controlerSaisie(){

		var nbErreur;
		nbErreur=0;

		mesInfos.adresse.style.background=couleurValide;
		mesInfos.ville.style.background=couleurValide;
		mesInfos.ancienPass.style.background=couleurValide;

		<?php include "includes/javascript.controle.telephone.inc.php"; ?>

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
			if(invaliditePassword)alert("Les caract�res sp�ciaux ne sont pas admis dans le mot de passe (sont �galement exclus les caract�res � accents)");
			nbErreur++;
			mesInfos.nouveauPass.style.background=couleurErreur;
			mesInfos.nouveauPassBis.style.background=couleurErreur;
		}
		else{
			mesInfos.nouveauPass.style.background=couleurValide;
			mesInfos.nouveauPassBis.style.background=couleurValide;
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

<form name="mesInfos" class="adminForm" method="post" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>" onSubmit="return controlerSaisie();">
<fieldset>
	<label>Nom d'utilisateur</label>
	<div class="givenData"><?php echo stripslashes($record["username"]);?></div>
	<label>Nom</label>
	<div class="givenData"><?php echo stripslashes($record["nom"]);?></div>
	<label>Pr�nom</label>
	<div class="givenData"><?php echo stripslashes($record["prenom"]);?></div>
</fieldset>
<fieldset>
	<label>Adresse</label>
	<input name="adresse" type="text" value="<?php echo $record["adresse"];?>" size="35" maxlength="35">
	<label>Num�ro postal</label>
	<input name="numPostal" type="text" value="<?php echo $record["numPostal"]==0?"":$record["numPostal"];?>" size="35" maxlength="35">
	<label>Ville</label>
	<input name="ville" type="text" value="<?php echo $record["ville"];?>" size="35" maxlength="35">
	<label>T�l. priv�</label>
	<input name="telephone" type="text" value="<?php echo $record["telephone"];?>" size="35" maxlength="35">
	<label>T�l. mobile</label>
	<input name="portable" type="text" value="<?php echo $record["portable"];?>" size="35" maxlength="35">
	<label>Email</label>
	<input name="email" type="text" value="<?php echo $record["email"];?>" size="35" maxlength="80" autocomplete="off">
</fieldset>
<fieldset>
	<label>Club</label>
		<?php
			// attention, pour garder une validit� des donn�es, les pr�sidents
			// de club ou gestionnaire de membres du club ne peuvent pas modifier leur club s'il sont de simple utilsiateur
			if(($record['gestionMembresClub'] == 0 && $record["contactClub"] == 0) || $_SESSION["__userLevel__"] < 10){
				$requeteSQLClub="SELECT * FROM ClubsFstb ORDER BY club";
				$recordsetClub = mysql_query($requeteSQLClub) or die ("<H1>mauvaise requete</H1>");

				echo "<select name='idClub'>";

					while($recordClub = mysql_fetch_array($recordsetClub)){

						$club = $recordClub["club"];
						if($club==""){
							$club=VAR_LANG_NON_SPECIFIE;
						}

						if($recordClub["id"] == $record["idClub"]){
							echo "<option selected value='".$recordClub["id"]."'>".$club."</option>";
						}
						else{
							echo "<option value='".$recordClub["id"]."'>".$club."</option>";
						}

					}
				echo "</select>";
			}
			// interdiction de modifier le club
			else{
				$requeteSQLClub="SELECT * FROM `Personne`, `ClubsFstb` WHERE `Personne`.`id`='".$record["id"]."' AND `Personne`.`idClub`=`ClubsFstb`.`id`";
				$recordsetClub = mysql_query($requeteSQLClub) or die ("<H1>mauvaise requete</H1>");

				$recordClub = mysql_fetch_array($recordsetClub);
				$club = $recordClub["club"];
				if($club==""){
					$club=VAR_LANG_NON_SPECIFIE;
				}
				echo "<input name='idClub' type='hidden' value='".$recordClub["id"]."'>";
				// affiche le club
				echo "<p>".$club./*" : ".VAR_LANG_OPTION_VEROUILLEE.*/"</p>";
			}

		?>
	<label>Date de naissance</label>
	<div class="birthDate">
		<?php
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
	<span class="infobulle">Pour changer de mot de passe, sinon laisser vide. Minimum 8 caract�res</span>
	<label>Ancien mot de passe</label>
	<input name="ancienPass" type="password" maxlength="255" size="35" autocomplete="off">
	<label>Nouveau mot de passe</label>
	<input name="nouveauPass" type="password" maxlength="255" size="35" autocomplete="off">
	<label>Encore une fois</label>
	<input name="nouveauPassBis" type="password" maxlength="255" size="35" autocomplete="off">
</fieldset>
<input type="hidden" name="action" value="modifier">
<input type="submit" value="modifier">
</form>
</div>
