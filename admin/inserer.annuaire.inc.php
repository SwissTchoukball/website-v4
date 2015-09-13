<?php
	statInsererPageSurf(__FILE__);
?>
<div class="insererAnnuaire">
<?php

	if ($_POST["action"]=="insererAnnuaire"){
	/*
			$idOrigineAdresse=validiteInsertionTextBd($_POST["DBDOrigineAdresse"]);
			$idStatus =validiteInsertionTextBd($_POST["DBDStatus"]);

			$derniereModification=date("Y-m-d H:i:s");
			$modificationPar=$_SESSION["__nom__"]." ".$_SESSION["__prenom__"];

			$idCivilite = validiteInsertionTextBd($_POST["DBDCivilite"]);
			$nom=validiteInsertionTextBd($_POST["nom"]);
			$prenom=validiteInsertionTextBd($_POST["prenom"]);
			$adresse=validiteInsertionTextBd($_POST["adresse"]);
			$npa=validiteInsertionTextBd($_POST["npa"]);
			$cp=validiteInsertionTextBd($_POST["cp"]);
			$ville=validiteInsertionTextBd($_POST["ville"]);
			$telPrive=validiteInsertionTextBd($_POST["telPrive"]);
			$telProf=validiteInsertionTextBd($_POST["telProf"]);
			$portable=validiteInsertionTextBd($_POST["portable"]);
			$fax=validiteInsertionTextBd($_POST["fax"]);
			$email=validiteInsertionTextBd($_POST["email"]);
			$dateNaissance=date_date2sql($_POST["jour"]."-".$_POST["mois"]."-".$_POST["annee"]);
			$remarque=validiteInsertionTextBd($_POST["remarque"]);

			$idLangue=validiteInsertionTextBd($_POST["DBDLangue"]);
			$idRaisonSociale=validiteInsertionTextBd($_POST["DBDRaisonSociale"]);
			$idClub=validiteInsertionTextBd($_POST["ClubsFstb"]);
			$idPays=validiteInsertionTextBd($_POST["DBDPays"]);
			$idCHTB=validiteInsertionTextBd($_POST["DBDCHTB"]);
			$idArbitre=validiteInsertionTextBd($_POST["DBDArbitre"]);

			$idMediaType=validiteInsertionTextBd($_POST["DBDMediaType"]);
			$idMediaCanton=validiteInsertionTextBd($_POST["DBDMediaCanton"]);
	*/
			$idOrigineAdresse=$_POST["DBDOrigineAdresse"];
			$idStatus =$_POST["DBDStatus"];

			$derniereModification=date("Y-m-d H:i:s");
			$modificationPar=$_SESSION["__nom__"]." ".$_SESSION["__prenom__"];

			$idCivilite = $_POST["DBDCivilite"];
			$nom=addslashes($_POST["nom"]);
			$prenom=addslashes($_POST["prenom"]);
			$adresse=addslashes($_POST["adresse"]);
			$npa=$_POST["npa"];
			$cp=$_POST["cp"];
			$ville=$_POST["ville"];
			$telPrive=$_POST["telPrive"];
			$telProf=$_POST["telProf"];
			$portable=$_POST["portable"];
			$fax=$_POST["fax"];
			$email=$_POST["email"];
			$dateNaissance=date_date2sql($_POST["jour"]."-".$_POST["mois"]."-".$_POST["annee"]);
			$raisonSociale=addslashes($_POST["raisonSociale"]);
			$remarque=addslashes($_POST["remarque"]);

			$idLangue=$_POST["DBDLangue"];
			//$idRaisonSociale=$_POST["DBDRaisonSociale"];
			$idClub=$_POST["ClubsFstb"];
			$idPays=$_POST["DBDPays"];
			$idCHTB=$_POST["DBDCHTB"];
			$idArbitre=$_POST["DBDArbitre"];
			$typeCompte=$_POST["DBDTypeCompte"];
			$numeroCompte=$_POST["numeroCompte"];

			$idMediaType=$_POST["DBDMediaType"];
			$idMediaCanton=$_POST["DBDMediaCanton"];

			// test s'il existe deja qqun de même nom pour avertir l'utilisateur d'un potentiel doublon
			$requeteSQL = "SELECT * FROM `DBDPersonne` WHERE `nom` LIKE '$nom' AND `prenom` LIKE '$prenom'";
			mysql_query($requeteSQL);
			if(mysql_affected_rows() > 0){
				printMessage("Attention doublon possible : Il existe deja quelqu'un de même nom et prénom ($nom $prenom). L'insertion continue...");
			}

			$requeteSQL = "INSERT INTO `DBDPersonne` (".
													"`idOrigineAdresse`,".
													"`idStatus`,".
													"`derniereModification`,".
													"`modificationPar`,".
													"`editor_id`,".
													"`idCivilite`,".
													"`nom`,".
													"`prenom`,".
													"`adresse`,".
													"`npa`,".
													"`cp`,".
													"`ville`,".
													"`telPrive`,".
													"`telProf`,".
													"`portable`,".
													"`fax`,".
													"`email`,".
													"`dateNaissance`,".
													"`raisonSociale`,".
													"`remarque`,".
													"`idLangue`,".
													"`idClub`,".
													"`idPays`,".
													"`idCHTB`,".
													"`idArbitre`,".
													"`typeCompte`,".
													"`numeroCompte`,".
													"`idMediaType`,".
													"`idMediaCanton`".
										") VALUES (".
													"'$idOrigineAdresse',".
													"'$idStatus',".
													"'$derniereModification',".
													"'$modificationPar',".
													$_SESSION['__idUser__'].",".
													"'$idCivilite',".
													"'$nom',".
													"'$prenom',".
													"'$adresse',".
													"'$npa',".
													"'$cp',".
													"'$ville',".
													"'$telPrive',".
													"'$telProf',".
													"'$portable',".
													"'$fax',".
													"'$email',".
													"'$dateNaissance',".
													"'$raisonSociale',".
													"'$remarque',".
													"'$idLangue',".
													"'$idClub',".
													"'$idPays',".
													"'$idCHTB',".
													"'$idArbitre',".
													"'$typeCompte',".
													"'$numeroCompte',".
													"'$idMediaType',".
													"'$idMediaCanton'".
										")";

			if(mysql_query($requeteSQL)===false){
				echo "<h4>Erreur d'insertion : contactez le webmaster.</h4>";
			}
			else{
				echo "<h4>Insertion réussie</h4>";
			}

			$idDbdPersonne= mysql_insert_id();

			// mise a jour des listes a options multiples
			// autres fonctions
			supprimerRelationMultiple("DBDRegroupementAutreFonction","idBDBPersonne",$idDbdPersonne);
			ajouterRelationMultiple("DBDRegroupementAutreFonction","idBDBPersonne","idAutreFonction",$idDbdPersonne, $DBDRegroupementAutreFonction);
			// formation
			supprimerRelationMultiple("DBDRegroupementFormation","idBDBPersonne",$idDbdPersonne);
			ajouterRelationMultiple("DBDRegroupementFormation","idBDBPersonne","idFormation",$idDbdPersonne, $DBDRegroupementFormation);
	}
?>



<?php

	echo "<SCRIPT language='JavaScript'>
	 var couleurErreur; couleurErreur='#".VAR_LOOK_COULEUR_ERREUR_SAISIE."';
	 var couleurValide; couleurValide='#".VAR_LOOK_COULEUR_SAISIE_VALIDE."';
	 </SCRIPT>";

	 // valeur par défaut
	 $record = array();

	 $record["idPays"]=42; // suisse
	 $record["idLangue"]=1; // francais
	 $record["idClub"]=15; // Non défini
?>
<SCRIPT language='JavaScript'>

	function controlerSaisie(){

		var nbError = 0;

		// nom
		if(modificationAnnuaire.nom.value.length == 0){
			modificationAnnuaire.nom.style.background=couleurErreur;
			if(nbError==0)modificationAnnuaire.nom.focus();
			nbError++;
		}
		else{
			modificationAnnuaire.nom.style.background=couleurValide;
		}

		// prenom
		if(modificationAnnuaire.prenom.value.length == 0){
			modificationAnnuaire.prenom.style.background=couleurErreur;
			if(nbError==0)modificationAnnuaire.prenom.focus();
			nbError++;
		}
		else{
			modificationAnnuaire.prenom.style.background=couleurValide;
		}

		var regEmail = new RegExp("^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$","g");

		if(regEmail.test(modificationAnnuaire.email.value) || modificationAnnuaire.email.value==""){
			modificationAnnuaire.email.style.background=couleurValide;
		}
		else{
			modificationAnnuaire.email.style.background=couleurErreur;
			if(nbError==0)modificationAnnuaire.email.focus();
			nbError++;
		}

		var dateN = new Date(modificationAnnuaire.annee.value,modificationAnnuaire.mois.value-1,modificationAnnuaire.jour.value);
		if(dateN.getFullYear() != modificationAnnuaire.annee.value || (dateN.getMonth() != modificationAnnuaire.mois.value-1) || dateN.getDate() != modificationAnnuaire.jour.value){
			modificationAnnuaire.annee.style.background=couleurErreur;
			modificationAnnuaire.mois.style.background=couleurErreur;
			modificationAnnuaire.jour.style.background=couleurErreur;
			if(nbError==0)modificationAnnuaire.mois.focus();
			nbError++;
		}
		else{
			modificationAnnuaire.annee.style.background=couleurValide;
			modificationAnnuaire.mois.style.background=couleurValide;
			modificationAnnuaire.jour.style.background=couleurValide;
		}

		return nbError==0;
	}

	function restreindreNumeroTelFax(input){
		var posCurseur = input.selectionStart;
		var newString = "";
		var regExp=new RegExp("[\,-\.\;\:_\*\+]", "g");
		for(var i=0;i<input.value.length;i++){
			if(input.value.charAt(i).match(regExp)){
				newString += " ";
			}
			else{
				newString += input.value.charAt(i);
			}
		}
		input.value = newString;
		input.selectionStart = posCurseur;
		input.selectionEnd = posCurseur;
	}
</SCRIPT>

<h3>Insertion d'un nouveau contact</h3>
<form class="formulaireAnnuaire" name="modificationAnnuaire" method="post" onSubmit="return controlerSaisie();" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>">
	<label>&nbsp;</label><input type="text" style="visibility:hidden;" />
	<label>Civilit&eacute;</label>
	<?php
	afficherdropDownListe("DBDCivilite","idCivilite","descriptionCivilite",$record["idCivilite"],true);
	?>
	<label>Nom<span style="color:red;font-size:10px">*</span></label>
	<input name="nom" type="text" value="<?php echo $record["nom"];?>" size="35" maxlength="35">
	<label>Prénom<span style="color:red;font-size:10px">*</span></label>
	<input name="prenom" type="text" value="<?php echo $record["prenom"];?>" size="35" maxlength="35">
	<label>Adresse</label>
	<input name="adresse" type="text" value="<?php echo $record["adresse"];?>" size="35" maxlength="35">
	<label>Case postale / option rue</label>
	<input name="cp" type="text" value="<?php echo $record["cp"];?>" size="35" maxlength="35">
	<label>Numéro postal</label>
	<input name="npa" type="text" value="<?php echo $record["npa"];?>" size="35" maxlength="35">
	<label>Ville</label>
	<input name="ville" type="text" value="<?php echo $record["ville"];?>" size="35" maxlength="35">
	<label>Pays</label>
	<?php
	afficherdropDownListe("DBDPays","idPays","descriptionPays",$record["idPays"],true);
	?>
	<br />
	<label>&nbsp;</label><input type="text" style="visibility:hidden;" />
	<label>Tél. privé</label>
	<input name="telPrive" onKeyUp="restreindreNumeroTelFax(this);" onChange="restreindreNumeroTelFax(this);" type="text" value="<?php echo $record["telPrive"];?>" size="35" maxlength="35">
	<label>Tél. prof.</label>
	<input name="telProf" onKeyUp="restreindreNumeroTelFax(this);" onChange="restreindreNumeroTelFax(this);" type="text" value="<?php echo $record["telProf"];?>" size="35" maxlength="35">
	<label>Portable</label>
	<input name="portable" onKeyUp="restreindreNumeroTelFax(this);" onChange="restreindreNumeroTelFax(this);" type="text" value="<?php echo $record["portable"];?>" size="35" maxlength="35">
	<label>Fax</label>
	<input name="fax" onKeyUp="restreindreNumeroTelFax(this);" onChange="restreindreNumeroTelFax(this);"  type="text" value="<?php echo $record["fax"];?>" size="35" maxlength="35">
	<label>Email</label>
	<input name="email" type="text" value="<?php echo $record["email"];?>" size="35" maxlength="80">
	<br />
	<label>&nbsp;</label><input type="text" style="visibility:hidden;" />
	<label>Club</label>
	<?php
	//afficherdropDownListe("ClubsFstb","nbIdClub","club",$record["idClub"],false);
	afficherListeClubs($record["idClub"], "nbIdClub");
	?>
	<label>Jour de naissance</label>
	<?php
	echo "<select name='jour'>";
	echo modif_liste_jour(jour($record["dateNaissance"]));
	echo "</select>";
	?>
	<label>Mois de naissance</label>
	<?php
	echo "<select name='mois'>";
	echo modif_liste_mois(mois($record["dateNaissance"]));
	echo "</select>";
	?>
	<label>Année de naissance</label>
	<?php
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
	<br />
	<label>&nbsp;</label><input type="text" style="visibility:hidden;" />
	<label>Langue</label>
	<?php
	afficherdropDownListe("DBDLangue","idLangue","descriptionLangue",$record["idLangue"],true);
	?>
	<label>Raison social</label>
	<input name="raisonSociale" type="text" value="<?php echo $record["raisonSociale"];?>" size="35" maxlength="80">
	<?php
	//afficherdropDownListe("DBDRaisonSociale","idRaisonSociale","descriptionRaisonSociale",$record["idCivilite"],true);
	?>
	<label>Status</label>
	<?php
	afficherdropDownListe("DBDStatus","idStatus","descriptionStatus",$record["idStatus"],true);
	?>
	<label>Origine de l'adresse</label>
	<?php
	afficherdropDownListe("DBDOrigineAdresse","idOrigineAdresse","descriptionOrigineAdresse",$record["idOrigineAdresse"],true);
	?>
	<label>tchouk<sup>up</sup></label>
	<?php
	afficherdropDownListe("DBDCHTB","idCHTB","descriptionCHTB",$record["idCHTB"],true);
	?>
	<label>Arbitre</label>
	<?php
	afficherdropDownListe("DBDArbitre","idArbitre","descriptionArbitre",$record["idArbitre"],true);
	?>
	<label>Type de compte bancaire</label>
	<?php
	afficherdropDownListe("DBDTypeCompte","idTypeCompte","TypeCompte",$record["typeCompte"],false);
	?>
	<label>Numéro de compte bancaire</label>
	<textarea name="numeroCompte" cols="50" rows="4"><?php echo $record["numeroCompte"];?></textarea>
	<label>M&eacute;dia type</label>
	<?php
	afficherdropDownListe("DBDMediaType","idMediaType","descriptionMediaType",$record["idMediaType"],true);
	?>
	<label>M&eacute;dia canton/&eacute;tat</label>
	<?php
	afficherdropDownListe("DBDMediaCanton","idMediaCanton","descriptionMediaCanton",$record["idMediaCanton"],true);
	?>
	<label>Formations</label>
	<?php afficherListeMultiple("DBDRegroupementFormation",
							 "idBDBPersonne",
							 "idFormation",
							 "DBDFormation",
							 "idFormation",
							 "descriptionFormation",
							 $modificationIdAnnuaire
							 ); ?>
	<p>ctrl+click : multi-selection</p>
	<label>Autres functions</label>
	<?php afficherListeMultiple("DBDRegroupementAutreFonction",
							 "idBDBPersonne",
							 "idAutreFonction",
							 "DBDAutreFonction",
							 "idAutreFonction",
							 "descriptionAutreFonction",
							 $modificationIdAnnuaire
							 ); ?>
	<p>ctrl+click : multi-selection</p>
	<label>Remarque</label>
	<textarea rows="7" cols="80" name="remarque"><?php echo $record["remarque"];?></textarea>
	<input type="hidden" name="action" value="insererAnnuaire">
	<input type="hidden" name="idDbdPersonne" value="<?php echo $modificationIdAnnuaire;?>">
	<input type="submit" value="<?php echo VAR_LANG_INSERER;?>">
	<span style="color:red;font-size:10px">*Obligatoire</span>
</form>
<form name="annuler" method="post" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>">
	<input type="submit" value="<?php echo VAR_LANG_ANNULER?>">
</form>
</div>
