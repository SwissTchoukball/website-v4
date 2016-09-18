<?php

$canEdit = false;

if ($newClub) {
	$clubID = 0;
	if ($nbError == 0) { //Initialisation uniquement si premier remplissage.
		$name = "";
		$fullName = "";
		$nameForSorting = "";
		$address = "";
		$npa = "";
		$city = "";
		$cantonID = "";
		$cantonName = "";
		$email = "";
		$phone = "";
		$url = "";
		$status = 1;
		$lastEdit = "";
		$lastEditorName = "";
		$presidentFirstName = "";
		$presidentLastName = "";
		$presidentAddress = "";
		$presidentNPA = "";
		$presidentCity = "";
		$presidentEmail = "";
		$presidentPhone = "";
		$presidentMobile = "";
	}
	$formLegend = "Nouveau club";
	$sendButtonValue = VAR_LANG_INSERER;
	$postType = "newClub";
	$canEdit = true;
} else {
	$clubRequest = "SELECT c.id, c.club, c.nomComplet, c.nomPourTri, c.adresse, c.npa, c.ville, c.email, c.telephone, c.statusId, c.url, c.lastEdit,
						   ct.id AS idCanton, ct.nomCanton".$_SESSION['__langue__']." AS nomCanton,
						   pres.nom AS nomPresident, pres.prenom AS prenomPresident, pres.adresse AS adressePresident, pres.numPostal AS npaPresident, pres.ville AS villePresident, pres.email AS emailPresident, pres.telephone AS telephonePresident, pres.portable AS portablePresident,
						   CONCAT(editor.prenom, editor.nom) AS lastEditorName
					FROM ClubsFstb c
					LEFT OUTER JOIN Canton ct ON c.canton = ct.id
					LEFT OUTER JOIN Personne pres ON c.id = pres.idClub AND pres.contactClub=1
					LEFT OUTER JOIN Personne editor ON c.lastEditorID = editor.id
					WHERE c.id=".$idClubToEdit;
	//echo '<p class="info">'.$clubRequest.'</p>';
	if (!$clubResult = mysql_query($clubRequest)) {
		echo '<p class="error">'.mysql_error().'</p>';
	} else if (mysql_num_rows($clubResult) == 0) {
		echo '<p class="error">Aucun club correspondant</p>';
	} else {
		$club = mysql_fetch_assoc($clubResult);
		if	($_SESSION['__idClub__'] == $club['id'] || $_SESSION['__userLevel__'] <= 5) {
			$clubID = $club['id'];
			$name = $club['club'];
			$fullName = $club['nomComplet'];
			$nameForSorting = $club['nomPourTri'];
			$address = $club['adresse'];
			$npa = $club['npa'];
			$city = $club['ville'];
			$cantonID = $club['idCanton'];
			$cantonName = $club['nomCanton'];
			$email = $club['email'];
			$phone = $club['telephone'];
			$url = $club['url'];
			$status = $club['statusId'];
			$lastEdit = $club['lastEdit'];
			$lastEditorName = $club['lastEditorName'];
			$presidentFirstName = $club['prenomPresident'];
			$presidentLastName = $club['nomPresident'];
			$presidentAddress = $club['adressePresident'];
			$presidentNPA = $club['npaPresident'];
			$presidentCity = $club['villePresident'];
			$presidentEmail = $club['emailPresident'];
			$presidentPhone = $club['telephonePresident'];
			$presidentMobile = $club['portablePresident'];
			$formLegend = $fullName;
			$sendButtonValue = VAR_LANG_MODIFIER;
			$postType = "editClub";
			$canEdit = true;
		} else {
			echo "<br />";
			echo "<p class='error'>Vous ne pouvez modifier que votre club.</p>";
		}
	}
}
if	($canEdit) {
	?>
	<h3><?php echo $formLegend; ?></h3>
	<form method="post" onsubmit="return checkClubForm();" name="clubEdit" action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>" class="adminForm">
		<fieldset>
			<label for="shortName">Nom court</label>
			<?php
			if ($_SESSION['__userLevel__'] <= 0) {
				?>
				<input type="text" name="shortName" id="shortName" value="<?php echo $name; ?>" />
				<?php
			} else {
				?>
				<p class="givenData"><?php echo $name; ?></p>
				<?php
			}
			?>
			<label for="fullName">Nom complet</label>
			<?php
			if ($_SESSION['__userLevel__'] <= 0) {
				?>
				<input type="text" name="fullName" id="fullName" value="<?php echo $fullName; ?>" />
				<?php
			} else {
				?>
				<p class="givenData"><?php echo $fullName; ?></p>
				<!-- Hidden inputs useful only for address preview -->
				<input type="hidden" id="fullName" value="<?php echo $fullName; ?>" />
				<?php
			}

			if ($_SESSION['__userLevel__'] <= 0) {
				?>
				<label for="nameForSorting">Nom pour tri</label>
				<input type="text" name="nameForSorting" id="nameForSorting" value="<?php echo $nameForSorting; ?>" />
				<?php
			}
			?>
		</fieldset>
		<fieldset>
			<span class="infobulle">Si aucune adresse correcte n'est indiquée, celle du ou de la président-e est utilisée.</span>
			<span id="addressPreview"><!-- rempli avec du Javascript --></span>
			<label for="address1">Adresse</label>
			<textarea id="address" name="address" onkeyup="updateAddressPreview();"><?php echo $address; ?></textarea>
			<label for="zipCode">NPA</label>
			<input type="text" id="npa" name="npa" onkeyup="updateAddressPreview();" value="<?php echo $npa; ?>" />
			<label for="city">Ville</label>
			<input type="text" id="city" name="city" onkeyup="updateAddressPreview();" value="<?php echo $city; ?>" />
			<label>Canton</label>
			<?php
			if ($_SESSION['__userLevel__'] <= 0) {
				afficherdropDownListe("Canton", "id", "nomCanton", $cantonID, true); // => <select name="Canton" ...
			} else {
				?>
				<p class="givenData"><?php echo $cantonName; ?></p>
				<?php
			}

			if ($_SESSION['__userLevel__'] <= 0) {
				?>
				<label for="status">Statut</label>
				<?php
				afficherdropDownListe("clubs_status", "id", "name", $status, true);
			}
			?>
		</fieldset>
		<fieldset>
			<span class="infobulle">Si aucune information n'est indiquée, celles du ou de la président-e sont utilisées.</span>
			<span id="infoPreview"><!-- rempli avec du Javascript --></span>
			<label for="phone">Téléphone</label>
			<input type="text" id="phone" name="phone" onkeyup="updateInfoPreview();" value="<?php echo $phone; ?>" />
			<label for="email">E-mail</label>
			<input type="text" id="email" name="email" onkeyup="updateInfoPreview();" value="<?php echo $email; ?>" />
			<label for="url">Site web</label>
			<input type="text" id="url" name="url" onkeyup="updateInfoPreview();" value="<?php echo $url; ?>" />
		</fieldset>
		<input type="hidden" name="clubID" value="<?php echo $clubID; ?>" />
		<input type="hidden" name="postType" value="<?php echo $postType; ?>" />

		<input type="submit" value="<?php echo $sendButtonValue; ?>" />
		<!-- Hidden inputs useful only for address preview -->
		<input type="hidden" id="presidentFirstName" value="<?php echo $presidentFirstName; ?>" />
		<input type="hidden" id="presidentLastName" value="<?php echo $presidentLastName; ?>" />
		<input type="hidden" id="presidentAddress" value="<?php echo $presidentAddress; ?>" />
		<input type="hidden" id="presidentNPA" value="<?php echo $presidentNPA; ?>" />
		<input type="hidden" id="presidentCity" value="<?php echo $presidentCity; ?>" />
		<input type="hidden" id="presidentEmail" value="<?php echo $presidentEmail; ?>" />
		<input type="hidden" id="presidentPhone" value="<?php echo $presidentPhone; ?>" />
		<input type="hidden" id="presidentMobile" value="<?php echo $presidentMobile; ?>" />
	</form>
	<?php
	if (!$newMember) {
		?>
		<p>Dernière modification le <?php echo date_sql2date($lastEdit);?> par <?php echo $lastEditorName;?></p>
		<?php
	}
}
?>
<script lang="javascript">

	var couleurErreur;
	couleurErreur='#<?php echo VAR_LOOK_COULEUR_ERREUR_SAISIE; ?>';
	var couleurValide;
	couleurValide='#<?php echo VAR_LOOK_COULEUR_SAISIE_VALIDE; ?>';

	function checkClubForm(){

		var nbError = 0;


		// NPA
		var regZipCode = new RegExp("^.*?[0-9]{4,}$","g");

		if($("#npa").val().length == 0 || regZipCode.test($("#npa").val())){
			$("#npa").css('background-color', couleurValide);
		} else{
			$("#npa").css('background-color', couleurErreur);
			if (nbError == 0) $("#npa").focus();
			nbError++;
		}


		//email
		var regEmail = new RegExp("^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$","g");

		if($("#email").val() == "" || regEmail.test($("#email").val())){
			$("#email").css('background-color', couleurValide);
		}
		else{
			$("#email").css('background-color', couleurErreur);
			if (nbError == 0) $("#email").focus();
			alert("L'adresse e-mail est invalide. Elle peut contenir des caractères interdits.");
			nbError++;
		}

		return nbError==0;
	}


	function updateAddressPreview() {
		var addressPreview = $("#addressPreview");
		addressPreview.text("");
		addressPreview.append($("#fullName").val() + "<br />");
		if ($("#npa").val().length == 4 && $("#city").val().length >= 3) {
			if($("#address").val() != "") {
				addressPreview.append($("#address").val() + "<br />");
			}
			addressPreview.append($("#npa").val() + " " + $("#city").val());
		} else {
			addressPreview.append($("#presidentFirstName").val() + " " + $("#presidentLastName").val() + "<br />");
			addressPreview.append($("#presidentAddress").val() + "<br />");
			addressPreview.append($("#presidentNPA").val() + " " + $("#presidentCity").val());
		}
	}

	function updateInfoPreview() {
		var infoPreview = $("#infoPreview");
		infoPreview.text("");
		if ($("#phone").val() != "") {
			infoPreview.append($("#phone").val() + "<br />");
		} else {
			if($("#presidentPhone").val() != "") {
				infoPreview.append($("#presidentPhone").val() + "<br />");
			}
			if($("#presidentMobile").val() != "") {
				infoPreview.append($("#presidentMobile").val() + "<br />");
			}
		}
		if ($("#email").val() != "") {
			infoPreview.append($("#email").val() + "<br />");
		} else {
			infoPreview.append($("#presidentEmail").val() + "<br />");
		}
		infoPreview.append($("#url").val());
	}
	updateAddressPreview();
	updateInfoPreview();
</script>
