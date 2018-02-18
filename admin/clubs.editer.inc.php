<?php

$formLegend = "Nouveau club";
$sendButtonValue = VAR_LANG_INSERER;
$postType = "newClub";

if (isset($idClubToEdit) && isValidClubID($idClubToEdit)) {
    try {
        $retrievedClub = ClubService::getClub($idClubToEdit);
        if ($retrievedClub) {
            if ($_SESSION['__idClub__'] == $retrievedClub->id || $_SESSION['__userLevel__'] <= 5) {
                $club = $retrievedClub;
                $formLegend = $club->fullName;
                $sendButtonValue = VAR_LANG_MODIFIER;
                $postType = "editClub";
            } else {
                printErrorMessage('Vous ne pouvez pas modifier ce club.');
            }
        } else {
            printErrorMessage('Aucun club correspondant.');
        }
    } catch (Exception $exception) {
        printErrorMessage($exception->getMessage());
    }
}

// Retrieving J+S coaches
$JSCoachesQuery =
    "SELECT CONCAT_WS(' ', p.prenom, p.nom) AS fullname, cjs.personId
    FROM coach_js cjs
    LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne = cjs.personId
    ORDER BY p.prenom";

$JSCoachesResource = mysql_query($JSCoachesQuery);
$JSCoaches = array();
while($row = mysql_fetch_assoc($JSCoachesResource)) {
    array_push($JSCoaches, $row);
}

?>
<h3><?php echo $formLegend; ?></h3>
<form method="post" onsubmit="return checkClubForm();" name="clubEdit"
      action="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>"
      class="st-form">
    <fieldset>
        <label for="shortName">Nom court</label>
        <?php
        if ($_SESSION['__userLevel__'] <= 0) {
            ?>
            <input type="text" name="shortName" id="shortName" value="<?php echo $club->shortName; ?>"/>
            <?php
        } else {
            ?>
            <p class="givenData"><?php echo $club->shortName; ?></p>
            <?php
        }
        ?>
        <label for="fullName">Nom complet</label>
        <?php
        if ($_SESSION['__userLevel__'] <= 0) {
            ?>
            <input type="text" name="fullName" id="fullName" value="<?php echo $club->fullName; ?>"/>
            <?php
        } else {
            ?>
            <p class="givenData"><?php echo $club->fullName; ?></p>
            <!-- Hidden inputs useful only for address preview -->
            <input type="hidden" id="fullName" value="<?php echo $club->fullName; ?>"/>
            <?php
        }

        if ($_SESSION['__userLevel__'] <= 0) {
            ?>
            <label for="sortName">Nom pour tri</label>
            <input type="text" name="sortName" id="sortName" value="<?php echo $club->sortName; ?>"/>
            <?php
        }
        ?>
    </fieldset>
    <fieldset>
        <span class="st-form__side-info tooltip">Si aucune adresse correcte n'est indiquée, celle du ou de la président-e est utilisée.</span>
        <span id="addressPreview" class="tooltip"><!-- rempli avec du Javascript --></span>
        <label for="address">Adresse</label>
        <textarea id="address"
                  name="address"
                  onkeyup="updateAddressPreview();"
                  placeholder="<?php echo $club->president['address']; ?>"><?php echo $club->address; ?></textarea> <!-- Do not add line break as it will remove the palceholder -->
        <label for="npa">NPA</label>
        <input type="text"
               id="npa"
               name="npa"
               onkeyup="updateAddressPreview();"
               value="<?php echo $club->npa; ?>"
               placeholder="<?php echo $club->president['npa']; ?>"/>
        <label for="city">Ville</label>
        <input type="text"
               id="city"
               name="city"
               onkeyup="updateAddressPreview();"
               value="<?php echo $club->city; ?>"
               placeholder="<?php echo $club->president['city']; ?>"/>
        <label>Canton</label>
        <?php
        if ($_SESSION['__userLevel__'] <= 0) {
            afficherdropDownListe("Canton", "id", "nomCanton", $club->cantonId, true); // => <select name="Canton" ...
        } else {
            ?>
            <p class="givenData"><?php echo $club->cantonName; ?></p>
            <?php
        }

        if ($_SESSION['__userLevel__'] <= 0) {
            ?>
            <label for="status">Statut</label>
            <?php
            afficherdropDownListe("clubs_status", "id", "name", $club->statusId, true);
        }
        ?>
    </fieldset>
    <fieldset>
        <span class="st-form__side-info tooltip">
            Si aucune information n'est indiquée, celles du ou de la président-e sont utilisées.
        </span>
        <span id="infoPreview" class="tooltip"><!-- rempli avec du Javascript --></span>
        <label for="phone">Téléphone</label>
        <input type="text"
               id="phone"
               name="phone"
               onkeyup="updateInfoPreview();"
               value="<?php echo $club->phoneNumber; ?>"
               placeholder="<?php echo $club->president['phoneNumber'] != "" ? $club->president['phoneNumber'] : $club->president['mobilePhoneNumber']; ?>"/>
        <label for="email">E-mail</label>
        <input type="text"
               id="email"
               name="email"
               onkeyup="updateInfoPreview();"
               value="<?php echo $club->email; ?>"
               placeholder="<?php echo $club->president['email']; ?>"/>
        <label for="url">Site web</label>
        <input type="text"
               id="url"
               name="url"
               onkeyup="updateInfoPreview();"
               value="<?php echo $club->url; ?>"/>
    </fieldset>
    <fieldset>
        <span class="st-form__side-info tooltip">
            Indiquez nom, prénom, fonction, téléphone et e-mail de chaque membre de votre comité.
        </span>
        <label for="committeeComposition">Comité</label>
        <textarea id="committeeComposition"
                  name="committeeComposition"
                  class="st-form__big-textarea"><?php echo $club->committeeComposition; ?></textarea>

        <label for="coachJS">Coach J+S</label>
        <select id="coachJS" name="coachJSID">
            <option value="null">Pas de Coach J+S</option>
            <?php
            foreach ($JSCoaches as $JSCoach) {
                $selected = $JSCoach['personId'] == $club->coachJSID ? 'selected' : '';
                echo "<option value='{$JSCoach['personId']}' {$selected}>{$JSCoach['fullname']}</option>";
            }
            ?>
        </select>
    </fieldset>
    <fieldset>
        <span class="st-form__side-info tooltip">
            Séparez les adresses e-mails par des virgules.
        </span>
        <label for="emailsOfficialComm">
            E-mails communication officielles<br>
            (pour clubs@tchoukball.ch)
        </label>
        <input type="text"
               id="emailsOfficialComm"
               name="emailsOfficialComm"
               value="<?php echo $club->officialCommMailingList; ?>"/>

        <label for="emailsTournamentComm">
            E-mails informations tournois<br>
            (pour info.tournois@tchoukball.ch)
        </label>
        <input type="text"
               id="emailsTournamentComm"
               name="emailsTournamentComm"
               value="<?php echo $club->tournamentsCommMailingList; ?>"/>
    </fieldset>
    <input type="hidden" name="clubID" value="<?php echo $club->id; ?>"/>
    <input type="hidden" name="postType" value="<?php echo $postType; ?>"/>

    <input type="submit" class="button button--primary" value="<?php echo $sendButtonValue; ?>"/>
    <!-- Hidden inputs useful only for address preview -->
    <input type="hidden" id="presidentFirstName" value="<?php echo $club->president['firstName']; ?>"/>
    <input type="hidden" id="presidentLastName" value="<?php echo $club->president['lastName']; ?>"/>
    <input type="hidden" id="presidentAddress" value="<?php echo $club->president['address']; ?>"/>
    <input type="hidden" id="presidentNPA" value="<?php echo $club->president['npa']; ?>"/>
    <input type="hidden" id="presidentCity" value="<?php echo $club->president['city']; ?>"/>
    <input type="hidden" id="presidentEmail" value="<?php echo $club->president['email']; ?>"/>
    <input type="hidden" id="presidentPhone" value="<?php echo $club->president['phoneNumber']; ?>"/>
    <input type="hidden" id="presidentMobile" value="<?php echo $club->president['mobilePhoneNumber']; ?>"/>
</form>
<?php
if (!$newMember) {
    ?>
    <p>Dernière modification le <?php echo date_sql2date($club->lastEdit); ?> par <?php echo $club->lastEditorName; ?></p>
    <?php
}
?>
<script lang="javascript">

    var npaField = $("#npa");
    var emailField = $("#email");

        function checkClubForm() {

        var nbError = 0;


        // NPA
        var regZipCode = new RegExp("^.*?[0-9]{4,}$", "g");

        if (npaField.val().length !== 0 && !regZipCode.test(npaField.val())) {
            npaField.addClass('.st-invalid');
            if (nbError === 0) npaField.focus();
            nbError++;
        } else {
            npaField.removeClass('.st-invalid');
        }


        //email
        var regEmail = new RegExp("^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$", "g");

        if (emailField.val() !== "" && !regEmail.test(emailField.val())) {
            emailField.addClass('.st-invalid');
            if (nbError === 0) emailField.focus();
            alert("L'adresse e-mail est invalide. Elle peut contenir des caractères interdits.");
            nbError++;
        } else {
            emailField.removeClass('.st-invalid');
        }

        return nbError === 0;
    }


    function updateAddressPreview() {
        var addressPreview = $("#addressPreview");
        addressPreview.text("");
        addressPreview.append($("#fullName").val() + "<br />");
        if (npaField.val().length === 4 && $("#city").val().length >= 3) {
            if ($("#address").val() !== "") {
                addressPreview.append($("#address").val() + "<br />");
            }
            addressPreview.append(npaField.val() + " " + $("#city").val());
        } else {
            addressPreview.append($("#presidentFirstName").val() + " " + $("#presidentLastName").val() + "<br />");
            addressPreview.append($("#presidentAddress").val() + "<br />");
            addressPreview.append($("#presidentNPA").val() + " " + $("#presidentCity").val());
        }
    }

    function updateInfoPreview() {
        var infoPreview = $("#infoPreview");
        infoPreview.text("");
        if ($("#phone").val() !== "") {
            infoPreview.append($("#phone").val() + "<br />");
        } else {
            if ($("#presidentPhone").val() !== "") {
                infoPreview.append($("#presidentPhone").val() + "<br />");
            }
            if ($("#presidentMobile").val() !== "") {
                infoPreview.append($("#presidentMobile").val() + "<br />");
            }
        }
        if (emailField.val() !== "") {
            infoPreview.append(emailField.val() + "<br />");
        } else {
            infoPreview.append($("#presidentEmail").val() + "<br />");
        }
        infoPreview.append($("#url").val());
    }
    updateAddressPreview();
    updateInfoPreview();
</script>
