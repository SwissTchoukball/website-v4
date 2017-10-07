<?php

$clubID = 0;
$shortName = isset($shortName) ? $shortName : "";
$fullName = isset($fullName) ? $fullName : "";
$nameForSorting = isset($nameForSorting) ? $nameForSorting : "";
$address = isset($address) ? $address : "";
$npa = isset($npa) ? $npa : "";
$city = isset($city) ? $city : "";
$cantonID = isset($cantonID) ? $cantonID : "";
$cantonName = "";
$status = isset($status) ? $status : 1;
$email = isset($email) ? $email : "";
$emailsOfficialComm = isset($emailsOfficialComm) ? $emailsOfficialComm : "";
$emailsTournamentComm = isset($emailsTournamentComm) ? $emailsTournamentComm : "";
$phone = isset($phone) ? $phone : "";
$url = isset($url) ? $url : "";
$committeeComposition = isset($committeeComposition) ? $committeeComposition : "";
$coachJSID = isset($coachJSID) ? $coachJSID : null;
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

$formLegend = "Nouveau club";
$sendButtonValue = VAR_LANG_INSERER;
$postType = "newClub";

if (isset($idClubToEdit)) {
    $clubRequest =
        "SELECT c.id, c.club, c.nomComplet, c.nomPourTri, c.statusId, c.adresse, c.npa, c.ville, c.telephone, c.url,
            c.email, c.emailsOfficialComm, c.emailsTournamentComm, c.committeeComposition, c.coachJSID,
            c.lastEdit, ct.id AS idCanton, ct.nomCanton{$_SESSION['__langue__']} AS nomCanton,
            pres.nom AS nomPresident, pres.prenom AS prenomPresident, pres.adresse AS adressePresident,
            pres.npa AS npaPresident, pres.ville AS villePresident, pres.email AS emailPresident,
            pres.telPrive AS telephonePresident, pres.portable AS portablePresident,
            CONCAT(editor.prenom, editor.nom) AS lastEditorName
        FROM clubs c
        LEFT OUTER JOIN Canton ct ON c.canton = ct.id
        LEFT OUTER JOIN DBDPersonne pres ON c.idPresident = pres.idDbdPersonne
        LEFT OUTER JOIN Personne editor ON c.lastEditorID = editor.id
        WHERE c.id={$idClubToEdit}";
    //echo '<p class="notification">'.$clubRequest.'</p>';
    if (!$clubResult = mysql_query($clubRequest)) {
        echo '<p class="notification notification--error">' . mysql_error() . '</p>';
    } else if (mysql_num_rows($clubResult) == 0) {
        echo '<p class="notification notification--error">Aucun club correspondant</p>';
    } else {
        $club = mysql_fetch_assoc($clubResult);
        if ($_SESSION['__idClub__'] == $club['id'] || $_SESSION['__userLevel__'] <= 5) {
            $clubID = $club['id'];
            $shortName = $club['club'];
            $fullName = $club['nomComplet'];
            $nameForSorting = $club['nomPourTri'];
            $address = $club['adresse'];
            $npa = $club['npa'];
            $city = $club['ville'];
            $cantonID = $club['idCanton'];
            $cantonName = $club['nomCanton'];
            $status = $club['statusId'];
            $email = $club['email'];
            $emailsOfficialComm = $club['emailsOfficialComm'];
            $emailsTournamentComm = $club['emailsTournamentComm'];
            $phone = $club['telephone'];
            $url = $club['url'];
            $committeeComposition = $club['committeeComposition'];
            $coachJSID = $club['coachJSID'];
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
        } else {
            echo "<br />";
            echo "<p class='notification notification--error'>Vous ne pouvez modifier que votre club.</p>";
        }
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
      action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>"
      class="st-form">
    <fieldset>
        <label for="shortName">Nom court</label>
        <?php
        if ($_SESSION['__userLevel__'] <= 0) {
            ?>
            <input type="text" name="shortName" id="shortName" value="<?php echo $shortName; ?>"/>
            <?php
        } else {
            ?>
            <p class="givenData"><?php echo $shortName; ?></p>
            <?php
        }
        ?>
        <label for="fullName">Nom complet</label>
        <?php
        if ($_SESSION['__userLevel__'] <= 0) {
            ?>
            <input type="text" name="fullName" id="fullName" value="<?php echo $fullName; ?>"/>
            <?php
        } else {
            ?>
            <p class="givenData"><?php echo $fullName; ?></p>
            <!-- Hidden inputs useful only for address preview -->
            <input type="hidden" id="fullName" value="<?php echo $fullName; ?>"/>
            <?php
        }

        if ($_SESSION['__userLevel__'] <= 0) {
            ?>
            <label for="nameForSorting">Nom pour tri</label>
            <input type="text" name="nameForSorting" id="nameForSorting" value="<?php echo $nameForSorting; ?>"/>
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
                  placeholder="<?php echo $presidentAddress; ?>"><?php echo $address; ?></textarea> <!-- Do not add line break as it will remove the palceholder -->
        <label for="npa">NPA</label>
        <input type="text"
               id="npa"
               name="npa"
               onkeyup="updateAddressPreview();"
               value="<?php echo $npa; ?>"
               placeholder="<?php echo $presidentNPA; ?>"/>
        <label for="city">Ville</label>
        <input type="text"
               id="city"
               name="city"
               onkeyup="updateAddressPreview();"
               value="<?php echo $city; ?>"
               placeholder="<?php echo $presidentCity; ?>"/>
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
        <span class="st-form__side-info tooltip">
            Si aucune information n'est indiquée, celles du ou de la président-e sont utilisées.
        </span>
        <span id="infoPreview" class="tooltip"><!-- rempli avec du Javascript --></span>
        <label for="phone">Téléphone</label>
        <input type="text"
               id="phone"
               name="phone"
               onkeyup="updateInfoPreview();"
               value="<?php echo $phone; ?>"
               placeholder="<?php echo $presidentPhone != "" ? $presidentPhone : $presidentMobile; ?>"/>
        <label for="email">E-mail</label>
        <input type="text"
               id="email"
               name="email"
               onkeyup="updateInfoPreview();"
               value="<?php echo $email; ?>"
               placeholder="<?php echo $presidentEmail; ?>"/>
        <label for="url">Site web</label>
        <input type="text"
               id="url"
               name="url"
               onkeyup="updateInfoPreview();"
               value="<?php echo $url; ?>"/>
    </fieldset>
    <fieldset>
        <span class="st-form__side-info tooltip">
            Indiquez nom, prénom, fonction, téléphone et e-mail de chaque membre de votre comité.
        </span>
        <label for="committeeComposition">Comité</label>
        <textarea id="committeeComposition"
                  name="committeeComposition"
                  class="st-form__big-textarea"><?php echo $committeeComposition; ?></textarea>

        <label for="coachJS">Coach J+S</label>
        <select id="coachJS" name="coachJSID">
            <option value="null">Pas de Coach J+S</option>
            <?php
            foreach ($JSCoaches as $JSCoach) {
                $selected = $JSCoach['personId'] == $coachJSID ? 'selected' : '';
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
               value="<?php echo $emailsOfficialComm; ?>"/>

        <label for="emailsTournamentComm">
            E-mails informations tournois<br>
            (pour info.tournois@tchoukball.ch)
        </label>
        <input type="text"
               id="emailsTournamentComm"
               name="emailsTournamentComm"
               value="<?php echo $emailsTournamentComm; ?>"/>
    </fieldset>
    <input type="hidden" name="clubID" value="<?php echo $clubID; ?>"/>
    <input type="hidden" name="postType" value="<?php echo $postType; ?>"/>

    <input type="submit" class="button button--primary" value="<?php echo $sendButtonValue; ?>"/>
    <!-- Hidden inputs useful only for address preview -->
    <input type="hidden" id="presidentFirstName" value="<?php echo $presidentFirstName; ?>"/>
    <input type="hidden" id="presidentLastName" value="<?php echo $presidentLastName; ?>"/>
    <input type="hidden" id="presidentAddress" value="<?php echo $presidentAddress; ?>"/>
    <input type="hidden" id="presidentNPA" value="<?php echo $presidentNPA; ?>"/>
    <input type="hidden" id="presidentCity" value="<?php echo $presidentCity; ?>"/>
    <input type="hidden" id="presidentEmail" value="<?php echo $presidentEmail; ?>"/>
    <input type="hidden" id="presidentPhone" value="<?php echo $presidentPhone; ?>"/>
    <input type="hidden" id="presidentMobile" value="<?php echo $presidentMobile; ?>"/>
</form>
<?php
if (!$newMember) {
    ?>
    <p>Dernière modification le <?php echo date_sql2date($lastEdit); ?> par <?php echo $lastEditorName; ?></p>
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
