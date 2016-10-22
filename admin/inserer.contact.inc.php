<?php
	statInsererPageSurf(__FILE__);
?>
<div class="insererContact">
<?php

	if ($_POST["action"]=="inserer"){

		$nom=validiteInsertionTextBd($_POST["nom"]);
		$prenom=validiteInsertionTextBd($_POST["prenom"]);
		$username=validiteInsertionTextBd($_POST["username"]);
		$password=md5($_POST["motDePasse"]);
		$email=validiteInsertionTextBd($_POST["email"]);
		$idClub=validiteInsertionTextBd($_POST["ClubsFstb"]);

		$requeteSQL = "INSERT INTO `Personne` ( `nom` , `prenom`, `username`,
						`password` , `email` , `idClub`)
					VALUES (
						'$nom', '$prenom', '$username', '$password',
					 '$email', '$idClub')";


		if(mysql_query($requeteSQL)===false){
			printErrorMessage("Erreur d'insertion. Contactez le webmaster.<br />" . mysql_error() . "<br />" . $requeteSQL);
		}
		else{
			printSuccessMessage("Insertion réussie");
		}
	}
?>



<?php
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

		<?php include "includes/javascript.controle.telephone.inc.php"; ?>

		if(mesInfos.email.value != "" && (mesInfos.email.value.indexOf("@") < 1 || mesInfos.email.value.indexOf("@") >= (mesInfos.email.value.lastIndexOf(".")))){
			nbErreur++;
			mesInfos.email.style.background=couleurErreur;
		}
		else{
			mesInfos.email.style.background=couleurValide;
		}

		if(mesInfos.prenom.value.length==0){
			nbErreur++;
			mesInfos.prenom.style.background=couleurErreur;
		}
		else{
			mesInfos.prenom.style.background=couleurValide;
		}

		if(mesInfos.nom.value.length==0){
			nbErreur++;
			mesInfos.nom.style.background=couleurErreur;
		}
		else{
			mesInfos.nom.style.background=couleurValide;
		}

		if(mesInfos.username.value.length==0){
			nbErreur++;
			mesInfos.username.style.background=couleurErreur;
		}
		else{
			mesInfos.username.style.background=couleurValide;
		}

		if(mesInfos.motDePasse.value.length<3){
			nbErreur++;
			mesInfos.motDePasse.style.background=couleurErreur;
		}
		else{
			mesInfos.motDePass.style.background=couleurValide;
		}

		if(mesInfos.contactClub.checked && mesInfos.idClub.value==0){
			nbErreur++;
			mesInfos.idClub.style.background=couleurErreur;
		}
		else{
			mesInfos.idClub.style.background=couleurValide;
		}

		return nbErreur==0;
	}
</SCRIPT>

<form class="adminForm" name="mesInfos" method="post" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>" onSubmit="return controlerSaisie();">
<label>Prénom</label>
<input name="prenom" type="text" value="" size="35" maxlength="35">
<label>Nom</label>
<input name="nom" type="text" value="" size="35" maxlength="35">
<label>Nom d'utilisateur</label>
<input name="username" type="text" value="" size="35" maxlength="35" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
<br />
<label>E-mail</label>
<input name="email" type="text" value="" size="35" maxlength="80" autocomplete="off">
<br />
<label>Club</label>
		<?php
			// attention, pour garder une validité des données, les présidents
			// de club ne peuvent pas modifier leur club s'il sont de simple utilsiateur

			/*$requeteSQLClub="SELECT * FROM ClubsFstb ORDER BY club";
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
			echo "</select>";	*/
			afficherListeClubs(0, "id");


		?>
<br />
<label>Mot de passe<br />(min 8 caractères)</label>
<input name="motDePasse" type="password" maxlength="255" size="35" autocomplete="off">
<br />
<input type="hidden" name="action" value="inserer">
<input type="submit" value="insérer">
</form>
</div>
