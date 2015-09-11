<?
	statInsererPageSurf(__FILE__);
?>

<?
//	$tableRequises = "`DBDPersonne`, `DBDStatus`, `DBDOrigineAdresse`, `ClubsFstb`, `DBDLangue`, `DBDRaisonSociale`, `DBDCivilite`, `DBDPays`, `DBDCHTB`, `DBDArbitre`, `DBDMediaType`, `DBDMediaCanton`";
	$tableRequises = "`DBDPersonne`, `DBDStatus`, `DBDOrigineAdresse`, `ClubsFstb`, `DBDLangue`, `DBDCivilite`, `DBDPays`, `DBDCHTB`, `DBDArbitre`, `DBDTypeCompte`, `DBDMediaType`, `DBDMediaCanton`";
	$jointure = "`DBDPersonne`.`idStatus`=`DBDStatus`.`idStatus`"." AND ".
							"`DBDPersonne`.`idOrigineAdresse`=`DBDOrigineAdresse`.`idOrigineAdresse`"." AND ".
							"`DBDPersonne`.`idClub`=`ClubsFstb`.`nbIdClub`"." AND ".
							"`DBDPersonne`.`idLangue`=`DBDLangue`.`idLangue`"." AND ".
							"`DBDPersonne`.`idCivilite`=`DBDCivilite`.`idCivilite`"." AND ".
							"`DBDPersonne`.`idPays`=`DBDPays`.`idPays`"." AND ".
							"`DBDPersonne`.`idCHTB`=`DBDCHTB`.`idCHTB`"." AND ".
							//"`DBDPersonne`.`idRaisonSociale`=`DBDRaisonSociale`.`idRaisonSociale`"." AND ".							
							"`DBDPersonne`.`idArbitre`=`DBDArbitre`.`idArbitre`"." AND ".							
							"`DBDPersonne`.`typeCompte`=`DBDTypeCompte`.`idTypeCompte`"." AND ".						
							"`DBDPersonne`.`idMediaType`=`DBDMediaType`.`idMediaType`"." AND ".	
							"`DBDPersonne`.`idMediaCanton`=`DBDMediaCanton`.`idMediaCanton`";							


	$requeteSQL = "SELECT * FROM ".$tableRequises." WHERE ".$jointure." AND `DBDPersonne`.`idDbdPersonne`='".$modificationIdAnnuaire."'";

	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1><br />".$requeteSQL);
	$record = mysql_fetch_array($recordset);
	
	echo "<SCRIPT language='JavaScript'>
	 var couleurErreur; couleurErreur='#".VAR_LOOK_COULEUR_ERREUR_SAISIE."';
	 var couleurValide; couleurValide='#".VAR_LOOK_COULEUR_SAISIE_VALIDE."';
	 </SCRIPT>";
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
		var regExpSpace=new RegExp("[\,-\.\;\:_\*]", "g");
		var regExpDelete=new RegExp("[a-zA-Z]", "g");
		for(var i=0;i<input.value.length;i++){
			if(input.value.charAt(i).match(regExpSpace)){
				newString += " ";
			}
			else if(input.value.charAt(i).match(regExpDelete)){
				newString += "";
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

<h3>Modification d'un contact</h3>
<p>Dernière modification le <? echo date_sql2date($record["derniereModification"]);?> &agrave; <? echo substr($record["derniereModification"],11);?> par <? echo $record["modificationPar"];?></p>
<form class="formulaireAnnuaire" name="modificationAnnuaire" method="post" onSubmit="return controlerSaisie();" action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>">
	<label>&nbsp;</label><input type="text" style="visibility:hidden;" />
	<label>Civilit&eacute;</label>
	<?		
	afficherdropDownListe("DBDCivilite","idCivilite","descriptionCivilite",$record["idCivilite"],true);	
	?>
	<label>Nom<span style="color:red;font-size:10px">*</span></label>
	<input name="nom" type="text" value="<? echo $record["nom"];?>" size="35" maxlength="35">
	<label>Prénom<span style="color:red;font-size:10px">*</span></label>
	<input name="prenom" type="text" value="<? echo $record["prenom"];?>" size="35" maxlength="35">		
	<label>Adresse</label>
	<input name="adresse" type="text" value="<? echo $record["adresse"];?>" size="35" maxlength="35">
	<label>Case postale / option rue</label>
	<input name="cp" type="text" value="<? echo $record["cp"];?>" size="35" maxlength="35">
	<label>Numéro postal</label>
	<input name="npa" type="text" value="<? echo $record["npa"];?>" size="35" maxlength="35">	
	<label>Ville</label>
	<input name="ville" type="text" value="<? echo $record["ville"];?>" size="35" maxlength="35">	
	<label>Pays</label>		
	<?		
	afficherdropDownListe("DBDPays","idPays","descriptionPays",$record["idPays"],true);	
	?>
	<br />
	<label>&nbsp;</label><input type="text" style="visibility:hidden;" />
	<label>Tél. privé</label>
	<input name="telPrive" onKeyUp="restreindreNumeroTelFax(this);" onChange="restreindreNumeroTelFax(this);" type="text" value="<? echo $record["telPrive"];?>" size="35" maxlength="35">	
	<label>Tél. prof.</label>
	<input name="telProf" onKeyUp="restreindreNumeroTelFax(this);" onChange="restreindreNumeroTelFax(this);" type="text" value="<? echo $record["telProf"];?>" size="35" maxlength="35">		
	<label>Portable</label>
	<input name="portable" onKeyUp="restreindreNumeroTelFax(this);" onChange="restreindreNumeroTelFax(this);" type="text" value="<? echo $record["portable"];?>" size="35" maxlength="35">		
	<label>Fax</label>
	<input name="fax" onKeyUp="restreindreNumeroTelFax(this);" onChange="restreindreNumeroTelFax(this);"  type="text" value="<? echo $record["fax"];?>" size="35" maxlength="35">		
	<label>Email</label>
	<input name="email" type="text" value="<? echo $record["email"];?>" size="35" maxlength="80">		
	<br />
	<label>&nbsp;</label><input type="text" style="visibility:hidden;" />
	<label>Club</label>		
	<?		
	//afficherdropDownListe("ClubsFstb","nbIdClub","club",$record["idClub"],false);
	afficherListeClubs($record["idClub"], "nbIdClub");	
	?>
	<label>Jour de naissance</label>
	<?
	echo "<select name='jour'>";
	echo modif_liste_jour(jour($record["dateNaissance"]));
	echo "</select>";	
	?>
	<label>Mois de naissance</label>
	<?
	echo "<select name='mois'>";				
	echo modif_liste_mois(mois($record["dateNaissance"]));
	echo "</select>";	
	?>
	<label>Année de naissance</label>
	<?
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
	<?		
	afficherdropDownListe("DBDLangue","idLangue","descriptionLangue",$record["idLangue"],true);
	?>				
	<label>Raison social</label>
	<input name="raisonSociale" type="text" value="<? echo $record["raisonSociale"];?>" size="35" maxlength="80">
	<?		
	//afficherdropDownListe("DBDRaisonSociale","idRaisonSociale","descriptionRaisonSociale",$record["idCivilite"],true);	
	?>
	<label>Status</label>		
	<?		
	afficherdropDownListe("DBDStatus","idStatus","descriptionStatus",$record["idStatus"],true);
	?>		
	<label>Origine de l'adresse</label>		
	<?		
	afficherdropDownListe("DBDOrigineAdresse","idOrigineAdresse","descriptionOrigineAdresse",$record["idOrigineAdresse"],true);
	?>		
	<label>tchouk<sup>up</sup></label>		
	<?		
	afficherdropDownListe("DBDCHTB","idCHTB","descriptionCHTB",$record["idCHTB"],true);
	?>		
	<label>Arbitre</label>		
	<?		
	afficherdropDownListe("DBDArbitre","idArbitre","descriptionArbitre",$record["idArbitre"],true);
	?>		
	<label>Type de compte bancaire</label>		
	<?		
	afficherdropDownListe("DBDTypeCompte","idTypeCompte","TypeCompte",$record["typeCompte"],false);
	?>
	<label>Numéro de compte bancaire</label>
	<textarea name="numeroCompte" cols="50" rows="4"><? echo $record["numeroCompte"];?></textarea>	
	<label>M&eacute;dia type</label>		
	<?		
	afficherdropDownListe("DBDMediaType","idMediaType","descriptionMediaType",$record["idMediaType"],true);
	?>			
	<label>M&eacute;dia canton/&eacute;tat</label>		
	<?		
	afficherdropDownListe("DBDMediaCanton","idMediaCanton","descriptionMediaCanton",$record["idMediaCanton"],true);
	?>			
	<label>Formations</label>		
	<? afficherListeMultiple("DBDRegroupementFormation",
							 "idBDBPersonne",
							 "idFormation",
							 "DBDFormation",
							 "idFormation",
							 "descriptionFormation",
							 $modificationIdAnnuaire
							 ); ?>		
	<p>ctrl+click : multi-selection</p>	
	<label>Autres functions</label>
	<? afficherListeMultiple("DBDRegroupementAutreFonction",
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
	<input type="hidden" name="action" value="modifierAnnuaire">
	<input type="hidden" name="idDbdPersonne" value="<? echo $modificationIdAnnuaire;?>">
	<input type="submit" value="<? echo VAR_LANG_MODIFIER;?>">
	<span style="color:red;font-size:10px">*Obligatoire</span>
</form>
<div align="center"><form name="annuler" method="post" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>"><input type="submit" value="<? echo VAR_LANG_ANNULER?>"></form></div>