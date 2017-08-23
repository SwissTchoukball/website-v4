<?php
include "date.inc.php";

function printMessage($message, $type = 'info')
{
    $class = 'notification';
    if ($type == 'error' || $type == 'success') {
        $class .= ' notification--' . $type;
    }
    echo '<p class="' . $class . '">' . $message . '</p>';
    return $message;
}

function printErrorMessage($message)
{
    return printMessage($message, 'error');
}

function printSuccessMessage($message)
{
    return printMessage($message, 'success');
}

//nom du fichier
function tailleFichier($file)
{
    $size = filesize($file);
    $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
    $ext = $sizes[0];
    for ($i = 1; (($i < count($sizes)) && ($size >= 1024)); $i++) {
        $size = $size / 1024;
        $ext = $sizes[$i];
    }
    return round($size, 2) . " " . $ext;
}

function formatPhoneNumber($rawPhoneNumber)
{
    $phoneNumber = $rawPhoneNumber;
    $phoneNumber = str_replace(' ', '', $phoneNumber);
    $phoneNumber = str_replace('/', '', $phoneNumber);
    if (substr($phoneNumber, 0, 2) != '00' || substr($phoneNumber, 0, 1) != '+') {
        // If no international prefix, we consider it is a Swiss phone number.
        // But if it doesn't start with 0, then we better not touch it.
        if (substr($phoneNumber, 0, 1) == 0) {
            $phoneNumber = substr($phoneNumber, 1);
            $phoneNumber = '+41' . $phoneNumber;
        }
    }

    return $phoneNumber;
}

function encryptageEmail($text)
{

    $text = str_replace("<A", "<a", $text);
    $text = str_replace("</A>", "</a>", $text);

    $chaineATrouver = "mailto:";
    $finTagA = "</a>";
    $nbCharAvantMailto = 10;
    // tant qu'il reste des mailtos dans le text
    $debut = strpos($text, $chaineATrouver);
    while (!($debut === false)) {
        // position du tag a... quelques chars avant...
        $debut = $debut < $nbCharAvantMailto ? $debut - $debut : $debut - $nbCharAvantMailto;
        // recherche dans les environe du mailto pour trouver le tag <a
        $positionTagA = strpos($text, "<a", $debut);
        // position du tag de fin
        $positionFinTagA = strpos($text, $finTagA, $debut) + strlen($finTagA);
        // premiere partie de text
        $textJusquaTagA = substr($text, 0, $positionTagA);
        // partie de text depuis la fin de la balise
        $textDepuisFinTagA = substr($text, $positionFinTagA, strlen($text) - $positionFinTagA);
        // la balise complete avec le mailto
        $baliseTotaleA = substr($text, $positionTagA, $positionFinTagA - $positionTagA);

        // affiche la premiere partie du text
        echo formatterTextEnHTML($textJusquaTagA);

        // affiche le javascript pour le mailto
        codage($baliseTotaleA);
        // tronquage du text affiché
        $text = substr($text, $positionFinTagA, strlen($text) - $positionFinTagA);
        // rehcerche d'un mailto dans la suite du text
        $debut = strpos($text, $chaineATrouver);
    }

    // affiche la fin du text ou le text entier
    return formatterTextEnHTML($text);

}

function afficherAvecEncryptageEmail($text)
{
    echo encryptageEmail($text) . "<br />";
}


// record d'une personne de la table BD
// mode : "FULL" : nom, prenom, adresse,npa ville, email, tel, natel
function afficherPersonne($record, $mode = "FULL")
{
    if ($mode == "FULL") {
        echo stripslashes($record["prenom"]) . "&nbsp;" . stripslashes($record["nom"]) . "<br />";
        echo $record["adresse"] . "<br>";
        echo $record["numPostal"] . "&nbsp;" . $record["ville"] . "<br /><br />";
        if ($record["email"] != "") {
            echo email($record["email"]);
            echo "<br />";
        }
        if ($record["telephone"] != "") {
            echo "<a class='phone side-icon-left' href='tel:" . formatPhoneNumber($record["telephone"]) . "'>" . $record["telephone"] . "</a><br />";
        }
        if ($record["portable"] != "") {
            echo "<a class='mobile side-icon-left' href='tel:" . formatPhoneNumber($record["portable"]) . "'>" . $record["portable"] . "</a><br />";
        }
    }
}

function showPerson($person, $hidePicture = false, $hideLanguages = false)
{
    echo '<div class="person-panel">';
    // Affichage de la photo
    if (!$hidePicture) {
        $imageFile = nomFichierPhotoValide(VAR_IMAGE_PORTRAITS_PATH . rewrite($person["prenom"]) . "_" . rewrite($person["nom"]) . ".png");
        strtolower(nomPhotoValide($person["nom"], $person["prenom"], null, "png"));
        if (is_file($imageFile)) {
            echo "<div class='person-panel__photo'>";
            echo "<img src='/" . $imageFile . "' alt='" . $person["prenom"] . " " . $person["nom"] . "' />";
            echo "</div>";
        } else {
            echo '<!--' . $imageFile . '-->';
        }
    }

    // Affichage des coordonnées
    echo '<span class="person-panel__name">' . stripslashes($person["prenom"]) . "&nbsp;" . stripslashes($person["nom"]) . "</span><br />";
    echo $person["adresse"] . "<br>";
    echo $person["cp"] != '' ? $person["cp"] . "<br>" : '';
    echo $person["npa"] . "&nbsp;" . $person["ville"] . "<br /><br />";
    if ($person["emailFSTB"] != '') {
        $email = $person["emailFSTB"];
    } else if ($person["email"] != '') {
        $email = $person["email"];
    } else {
        $email = '';
    }
    echo email($email);
    echo "<br />";
    echo $person['telPrive'] != '' ? "<a class='phone side-icon-left' href='tel:" . formatPhoneNumber($person["telPrive"]) . "'>" . $person["telPrive"] . "</a><br />" : '';
    echo $person['portable'] != '' ? "<a class='mobile side-icon-left' href='tel:" . formatPhoneNumber($person["portable"]) . "'>" . $person["portable"] . "</a><br />" : '';

    // Affichage des langues parlées
    if (!$hideLanguages) {
        $spokenLanguagesQuery = "SELECT l.idLangue, l.descriptionLangue" . $_SESSION['__langue__'] . " as descriptionLangue
                                 FROM DBDLangue l, RegroupementLangueParle rlp
                                 WHERE l.idLangue = rlp.idLangue
                                 AND rlp.idPersonne = '" . $person['idDbdPersonne'] . "'";
        $spokenLanguagesData = mysql_query($spokenLanguagesQuery);
        if (mysql_num_rows($spokenLanguagesData) > 0) {
            echo '<div class="person-panel__spoken-languages">';
            while ($spokenLanguages = mysql_fetch_array($spokenLanguagesData)) {
                echo "<img src='" . VAR_IMAGE_LANGUE . $spokenLanguages["idLangue"] . ".png' alt='" . $spokenLanguages['descriptionLangue'] . "' /> ";
            }
            echo '</div>';
        }
    }
    echo '<div style="clear: both;"></div>';
    echo '</div>';
}

function getReferees($orderByLevel = false)
{
    $referees = array();

    $queryPartOrderBy = ' ORDER BY ';
    if ($orderByLevel) {
        $queryPartOrderBy .= 'a.levelId DESC, ';
    }
    $queryPartOrderBy .= 'p.nom, p.prenom';

    $refereesQuery = "SELECT a.personId, a.levelId, a.hidden, a.public,
                             p.nom, p.prenom, p.telPrive, p.portable, p.email, p.emailFSTB AS emailFederation, p.numeroCompte,
                             cl.club AS clubName
                      FROM Arbitres a
                      LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne = a.personId
                      LEFT OUTER JOIN ClubsFstb cl ON cl.nbIdClub = p.idClub" .
        $queryPartOrderBy;
    if (!$referessData = mysql_query($refereesQuery)) {
        $errorReferee = array();
        $errorReferee['levelId'] = 0;
        $errorReferee['nom'] = 'Erreur lors de la récupération des arbitres';
        $referees[0] = $errorReferee;
    } else {
        while ($referee = mysql_fetch_assoc($referessData)) {
            $referees[$referee['personId']] = $referee;
        }
    }
    return $referees;
}

/**
 * To use this function, it is better that for the $referees array to be sorted by level.
 *
 */
function printRefereesOptionsList($referees, $selectedRefereeID, $addslashes = false)
{
    $refereeLevel = null;
    foreach ($referees as $referee) {
        if ($referee['levelId'] != $refereeLevel) {
            $refereeLevel = $referee['levelId'];
            echo '<optgroup label="Arbitres ' . chif_rome($refereeLevel - 1) . '">';
        }
        if ($referee['personId'] == $selectedRefereeID) {
            $selected = ' selected';
        } else {
            $selected = '';
        }
        if ($addslashes) {
            $fullname = addslashes($referee['nom'] . ' ' . $referee['prenom']);
        } else {
            $fullname = $referee['nom'] . ' ' . $referee['prenom'];
        }
        echo '<option value="' . $referee['personId'] . '"' . $selected . '>' . $fullname . '</option>';
    }
}

function afficherArbitre($referee)
{
    if ($referee['public'] == 1 || $_SESSION['__userLevel__'] <= 10) {
        echo '<div class="referee">';
        echo "<strong>", $referee["nom"] . "&nbsp;" . $referee["prenom"] . "</strong><br />";
        if ($referee['clubName'] != '') {
            echo "Club : " . $referee['clubName'] . "<br />";
        }
        if ($referee["telPrive"] != "" && $_SESSION['__userLevel__'] <= 10) {
            echo VAR_LANG_TELEPHONE . " : ";
            echo $referee["telPrive"];
            echo "<br />";
        }
        if ($referee["portable"] != "" && $_SESSION['__userLevel__'] <= 10) {
            echo VAR_LANG_PORTABLE . " : ";
            echo $referee["portable"];
            echo "<br />";
        }
        if (($referee["email"] != "" || $referee["emailFederation"] != "") && $_SESSION['__userLevel__'] <= 10) {
            $email = $referee["email"];
            if ($referee["emailFederation"] != "") {
                $email = $referee["emailFederation"];
            }
            echo email($email);
            echo "<br />";
        }
        echo "</div>";
    }
}

function computeAndSaveRefereeChampionshipPoints($season, $categoryID)
{
    if ($categoryID == 1 || $categoryID == 2) { // League A or B, the only championship leagues accounted for currently
        $pointsComputationQuery = "SELECT a.personId, SUM(tp.pointsArbitrage) AS pointsArbitrageTotal
                                   FROM Arbitres a
                                   LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne = a.personId
                                   LEFT OUTER JOIN Championnat_Periodes cp ON p.idDbdPersonne = cp.idArbitreA OR p.idDbdPersonne = cp.idArbitreB OR p.idDbdPersonne = cp.idArbitreC
                                   LEFT OUTER JOIN Championnat_Types_Periodes tp ON cp.idTypePeriode=tp.id
                                   LEFT OUTER JOIN Championnat_Matchs m ON cp.idMatch=m.idMatch
                                   WHERE m.saison=" . $season . " AND m.idCategorie=" . $categoryID . "
                                   GROUP BY p.idDbdPersonne
                                   ORDER BY p.nom, p.prenom";

        if ($referees = mysql_query($pointsComputationQuery)) {
            while ($referee = mysql_fetch_assoc($referees)) {
                // With only league A and B, we can use the $categoryID as idTypePoints, but with new leagues it won't be possible anymore
                $removingPreviousPointsRecord = "DELETE FROM Arbitres_Points
                                                 WHERE idArbitre = " . $referee['personId'] . "
                                                 AND idSaison = " . $season . "
                                                 AND idTypePoints = " . $categoryID;
                if (mysql_query($removingPreviousPointsRecord)) {
                    $savePointsQuery = "INSERT INTO Arbitres_Points (idArbitre, idSaison, idTypePoints, points, date, creator, lastEditor)
                                        VALUES (" . $referee['personId'] . ",
                                                " . $season . ", " . $categoryID . ",
                                                " . $referee['pointsArbitrageTotal'] . ",
                                                '" . date('Y-m-d') . "',
                                                " . $_SESSION['__idUser__'] . ",
                                                " . $_SESSION['__idUser__'] . ")";
                    if (!mysql_query($savePointsQuery)) {
                        printErrorMessage('Problème lors de l\'ajout des nouvelles valeurs.');
                        die();
                    }
                } else {
                    printErrorMessage('Problème lors de la suppression des anciennes valeurs.');
                    die();
                }
            }
            // If we get here, it means everything was done without problems
            printSuccessMessage('Les points d\'arbitres ont été correctement comptabilisés.');
        } else {
            printErrorMessage('Problème lors du calcul des points d\'arbitre.');
        }
    } else {
        printMessage('Les points d\'arbitres ne sont pas comptés pour la catégorie souhaitée.');
    }
}

// pour valider du text avant d'inserer dans la bd avec tag html.
function validiteInsertionTextBd($text)
{
    $text = ltrim($text);
    $text = rtrim($text);
    $text = strip_tags($text);
    $text = mysql_real_escape_string($text);
    $text = htmlspecialchars($text, ENT_COMPAT, 'ISO-8859-1');
    return $text;
}

// pour valider du text avant d'inserer dans la bd avec tag html
function validiteInsertionTextAvecTagHTML($text)
{
    $text = ltrim($text);
    $text = rtrim($text);
    //$text = mysql_real_escape_string($text);
    //$text = htmlspecialchars($text,ENT_QUOTES);
    return $text;
}


// pour la generation des fichiers des langues.
function formatterText($text)
{

    $text = str_replace('à', '&agrave;', $text);
    $text = str_replace('é', '&eacute;', $text);
    $text = str_replace('è', '&egrave;', $text);
    $text = str_replace('ü', '&uuml;', $text);
    $text = str_replace('ö', '&ouml;', $text);
    $text = str_replace('ä', '&auml;', $text);
    $text = str_replace('ç', '&ccedil;', $text);
    $text = htmlspecialchars($text, ENT_QUOTES, 'ISO-8859-1');

    $text = nl2br($text);
    return str_replace('"', '\"', $text);
}

// les representations html des carctère speciaux seront a nouveau direct
function nomsNormauxSansCodageHTML($text)
{

    $text = str_replace('&agrave;', 'à', $text);
    $text = str_replace('&eacute;', 'é', $text);
    $text = str_replace('&egrave;', 'è', $text);
    $text = str_replace('&uuml;', 'ü', $text);
    $text = str_replace('&ouml;', 'ö', $text);
    $text = str_replace('&auml;', 'ä', $text);
    $text = str_replace('&ccedil;', 'ç', $text);

    return $text;
}

;

// pour la generation des fichiers des langues.
function formatterTextEnHTML($text)
{

    $text = str_replace('à', '&agrave;', $text);
    $text = str_replace('é', '&eacute;', $text);
    $text = str_replace('è', '&egrave;', $text);
    $text = str_replace('ü', '&uuml;', $text);
    $text = str_replace('ö', '&ouml;', $text);
    $text = str_replace('ä', '&auml;', $text);
    $text = str_replace('ç', '&ccedil;', $text);

    $text = nl2br($text);
    return $text;
}

function gardemenuselection($menuselection, $smenuselection)
{
    $text = "";
    if ($menuselection != "") {
        $text = "&menuselection=" . $menuselection;
    }
    if ($menuselection != "" && $smenuselection != "") {
        $text = $text . "&smenuselection=" . $smenuselection;
    }
    return $text;
}


///////////////////////////////////////////////// FONCTIONS AVEC SQL ///////////////////////////////////////////////////

function afficherdropDownListe($nomTable, $nomIdOption, $nomDescriptionOption, $idSelect, $blnLangue)
{
    afficherdropDownListeDesactivable($nomTable, $nomIdOption, $nomDescriptionOption, $idSelect, $blnLangue, false);
}

function afficherdropDownListeDesactivable(
    $nomTable,
    $nomIdOption,
    $nomDescriptionOption,
    $idSelect,
    $blnLangue,
    $desactive
) {
    if ($desactive) {
        $disabled = " readonly='readonly'";
    } else {
        $disabled = '';
    }


    if ($blnLangue) {
        $requeteSQLOptions = "SELECT * FROM " . $nomTable . " ORDER BY $nomDescriptionOption" . $_SESSION["__langue__"];
        $recordsetOptions = mysql_query($requeteSQLOptions) or die(printErrorMessage("afficherListe: mauvaise requete sur : $nomIdOption"));

        echo "<select id='" . $nomTable . "' name='" . $nomTable . "'" . $disabled . ">";

        while ($recordOption = mysql_fetch_array($recordsetOptions)) {
            $option = $recordOption["$nomDescriptionOption" . $_SESSION["__langue__"]];
            if ($option == "") {
                $option = VAR_LANG_NON_SPECIFIE;
            }

            if ($recordOption[$nomIdOption] == $idSelect) {
                echo "<option selected value='" . $recordOption[$nomIdOption] . "'>" . $option . "</option>";
            } else {
                echo "<option value='" . $recordOption[$nomIdOption] . "'>" . $option . "</option>";
            }
        }
        echo "</select>";
    } else {
        $requeteSQLOptions = "SELECT * FROM " . $nomTable . " ORDER BY $nomDescriptionOption";
        $recordsetOptions = mysql_query($requeteSQLOptions) or die(printErrorMessage("afficherListe: mauvaise requete sur : $nomIdOption"));

        echo "<select id='" . $nomTable . "' name='" . $nomTable . "'" . $disabled . ">";

        while ($recordOption = mysql_fetch_array($recordsetOptions)) {
            $option = $recordOption["$nomDescriptionOption"];
            if ($option == "") {
                $option = VAR_LANG_NON_SPECIFIE;
            }
            if ($recordOption[$nomIdOption] == $idSelect) {
                echo "<option selected value='" . $recordOption[$nomIdOption] . "'>" . $option . "</option>";
            } else {
                echo "<option value='" . $recordOption[$nomIdOption] . "'>" . $option . "</option>";
            }
        }
        echo "</select>";
    }
}

function printManualRefereePointsTypesSelect($selectedValue, $selectName)
{
    $query = "SELECT id, typesPoints" . $_SESSION["__langue__"] . " AS nom FROM Arbitres_Types_Points WHERE ajoutManuel=1 ORDER BY nom";
    $resultOptions = mysql_query($query) or die (printErrorMessage('Problème lors de la récupération des types de points'));

    echo "<select id='" . $selectName . "' name='" . $selectName . "'>";

    while ($option = mysql_fetch_array($resultOptions)) {
        if ($option['id'] == $selectedValue) {
            echo "<option selected value='" . $option['id'] . "'>" . $option['nom'] . "</option>";
        } else {
            echo "<option value='" . $option['id'] . "'>" . $option['nom'] . "</option>";
        }
    }
    echo "</select>";
}

function printSeasonsOptionsForSelect($from, $to, $selectedSeason)
{
    if ($from > $to) {
        $temp = $from;
        $from = $temp;
        $to = $from;
    }
    for ($season = $to; $season >= $from; $season--) {
        $seasonEnd = $season + 1;
        if ($season == $selectedSeason) {
            $selected = ' selected="selected"';
        } else {
            $selected = '';
        }
        echo '<option value="' . $season . '"' . $selected . '>' . $season . ' - ' . $seasonEnd . '</option>';
    }
}

/**
 * Prints a dropdown list with all the clubs
 * @param {int} $selectedClubId The club id selected in the list
 * @param {string} $typeId Must be 'id' or 'nbIdClub'
 */
function afficherListeClubs($selectedClubId, $typeId)
{
    $requeteListeClubs =
        "SELECT c.id, c.nbIdClub, c.club, c.statusId, cs.name" . $_SESSION['__langue__'] . " AS statusName
         FROM ClubsFstb c, clubs_status cs
         WHERE c.statusId = cs.id
         ORDER BY statusId, club";
    $reponse = mysql_query($requeteListeClubs) or die("<h4>Erreur : Mauvaise requête pour l'affichage de la liste des clubs</h4>");
    echo "<select name='ClubsFstb'>";
    $previousClubStatusId = 0;
    while ($club = mysql_fetch_assoc($reponse)) {
        $nomClub = $club['club'];
        $idClub = $club[$typeId];
        if ($previousClubStatusId != $club['statusId']) {
            if ($previousClubStatusId != 0) {
                echo "</optgroup>";
            }
            echo "<optgroup label='" . $club['statusName'] . "'>";
        }

        if ($nomClub == "") {
            $nomClub = VAR_LANG_NON_SPECIFIE;
        }

        if ($idClub == $selectedClubId) {
            $selected = " selected='selected'";
        } else {
            $selected = "";
        }
        echo "<option value='" . $idClub . "'" . $selected . ">" . $nomClub . "</option>";
        $previousClubStatusId = $club['statusId'];
    }
    echo "</select>";
}

function showHead($objectID, $table, $objectNameAttr)
{
    $query = "SELECT t." . $objectNameAttr . " AS objectName, p.idDbdPersonne, p.nom, p.prenom, p.adresse, p.cp, p.npa, p.ville, p.emailFSTB, p.email, p.telPrive, p.portable
              FROM " . $table . " t
              LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne = t.idResponsable
              WHERE t.id = " . $objectID . "
              LIMIT 1";
    if ($result = mysql_query($query)) {
        $person = mysql_fetch_assoc($result);
        echo '<h2>' . VAR_LANG_RESPONSABLE . ' ' . $person['objectName'] . '</h2>';
        showPerson($person);
    } else {
        printErrorMessage("Erreur lors de la récupération du responsable");
    }
}

function showCommissionHead($commissionID)
{
    showHead($commissionID, 'Commission_Nom', 'nomCom' . $_SESSION['__langue__']);
}

function showDomainHead($domainID)
{
    showHead($domainID, 'Comite_Responsabilites', 'nom' . $_SESSION['__langue__']);
}

function showCommitteeMember($committeeMember)
{
    echo "<div class='two-col-card'>";

    if ($committeeMember['idSexe'] == 3) {
        $functionTitle = $committeeMember['titreF'];
    } else {
        $functionTitle = $committeeMember['titreH'];
    }
    echo "<h2>" . $functionTitle . "</h2>";
    echo '<div class="two-col-card__content">';

    showPerson($committeeMember);

    echo "<div class='two-col-card__content__second-column'>";
    $responsabilitiesQuery = "SELECT cr.nom" . $_SESSION['__langue__'] . " AS nom, cr.lien
                              FROM Comite_Responsabilites cr
                              WHERE cr.idResponsable=" . $committeeMember['idDbdPersonne'];
    $commissionsQuery = "SELECT comm.nomCom" . $_SESSION['__langue__'] . " AS nom, comm.lien
                         FROM Commission_Nom comm
                         WHERE comm.idMembreComite=" . $committeeMember['idDbdPersonne'];
    $respAndCommQuery = "(" . $responsabilitiesQuery . ") UNION (" . $commissionsQuery . ")";
    $respAndCommData = mysql_query($respAndCommQuery);
    echo "<h5>";
    if (mysql_num_rows($respAndCommData) > 1) {
        echo VAR_LANG_DOMAINES_RESPONSABILITE;
    } else {
        echo VAR_LANG_DOMAINE_RESPONSABILITE;
    }
    echo "</h5>";
    echo "<ul>";
    while ($respAndComm = mysql_fetch_assoc($respAndCommData)) {
        $lien = $respAndComm['lien'];
        echo '<li>';
        echo $lien != '' ? '<a href="' . $lien . '">' : '';
        echo $respAndComm['nom'];
        echo $lien != '' ? '</a>' : '';
        echo '</li>';
    }
    echo "</ul>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

function showTeamCoaches($teamId)
{
    $query = "SELECT p.idDbdPersonne, p.nom, p.prenom, p.adresse, p.cp, p.npa, p.ville,
              p.emailFSTB, p.email, p.telPrive, p.portable, p.idSexe,
              cnf.titreH" . $_SESSION["__langue__"] . " AS maleTitle, 
              cnf.titreF" . $_SESSION["__langue__"] . " AS femaleTitle
              FROM CadreNational_Membres cnm
              LEFT OUTER JOIN CadreNational_Fonctions cnf ON cnf.id = cnm.idFonction
              LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne = cnm.idPersonne
              WHERE cnm.idEquipe = " . $teamId . "
              AND (cnm.idFonction = 1 || cnm.idFonction = 2)
              AND (ISNULL(cnm.dateFin) OR cnm.dateFin >= CURDATE())";
    if ($result = mysql_query($query)) {
        while($coach = mysql_fetch_assoc($result)) {
            $title = $coach['femaleTitle'];
            if ($coach['idSexe'] == 2) {
                $title = $coach['maleTitle'];
            }
            echo '<h2>' . $title . '</h2>';
            showPerson($coach, false, true);
        }
    } else {
        printErrorMessage("Erreur lors de la récupération des responsables");
    }
}

function showCommission($commission)
{
    echo "<div class='two-col-card' id='c" . $commission["id"] . "'>";
    echo $commission["lien"] != '' ? '<a href="' . $commission["lien"] . '">' : '';
    echo "<h2>" . $commission["nomCommission"] . "</h2>";
    echo $commission["lien"] != '' ? '</a>' : '';

    echo '<div class="two-col-card__content">';
    if ($commission['idDbdPersonne'] != null) {
        showPerson($commission);
    }

    echo "<div class='two-col-card__content__second-column'>";
    $membersQuery = "SELECT *
                    FROM Commission_Membre cm, DBDPersonne p
                    WHERE cm.idPersonne = p.idDbdPersonne
                    AND cm.idNom = '" . $commission["id"] . "'
                    ORDER BY p.nom, p.prenom";
    $membersResult = mysql_query($membersQuery);

    if (mysql_num_rows($membersResult) > 0) {
        echo "<h5>Membres</h5>";
        echo "<ul>";
        while ($member = mysql_fetch_array($membersResult)) {
            echo "<li>";
            echo stripslashes($member["prenom"]) . " " . stripslashes($member["nom"]);
            if ($member['fonction'] != null) {
                echo ' <span class="li__complement">' . $member['fonction'] . '</span>';
            }
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<br />"; //Pour que les langues s'affichent dans la première colonne
    }
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

function showFunctionPerson($functionID)
{
    $query = "SELECT f.titreH" . $_SESSION['__langue__'] . " AS titreH, f.titreF" . $_SESSION['__langue__'] . " AS titreF, p.idDbdPersonne, p.nom, p.prenom, p.adresse, p.cp, p.npa, p.ville, p.emailFSTB, p.email, p.telPrive, p.portable, p.idSexe
              FROM FonctionsAutres_Repartition fr
              LEFT OUTER JOIN FonctionsAutres f ON f.id = fr.idFonctionAutre
              LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne = fr.idPersonne
              WHERE f.id = " . $functionID . "
              AND (ISNULL(fr.dateFin) OR fr.dateFin >= CURDATE()) AND (ISNULL(fr.dateDebut) OR fr.dateDebut < CURDATE())";
    if ($result = mysql_query($query)) {
        $person = mysql_fetch_assoc($result);
        if ($person['idSexe'] == 3) {
            $functionTitle = $person['titreF'];
        } else {
            $functionTitle = $person['titreH'];
        }
        echo '<h2>' . $functionTitle . '</h2>';
        showPerson($person);
    } else {
        printErrorMessage("Erreur lors de la récupération de la personne");
    }
}

function showPagination($currentPage, $totalNumberOfPages, $href, $pointerName = "page")
{
    if ($currentPage < 3) {
        $firstPage = 1;
    } else {
        $firstPage = $currentPage - 2;
    }

    if ($currentPage > $totalNumberOfPages - 2) {
        $lastPage = $totalNumberOfPages;
    } else {
        $lastPage = $currentPage + 2;
    }

    echo '<ul class="pagination">';
    if ($firstPage != 1) {
        $previousPage = $currentPage - 1;
        echo '<li><a href="' . $href . '&' . $pointerName . '=1">&lt;&lt;</a></li>';
        echo '<li><a href="' . $href . '&' . $pointerName . '=' . $previousPage . '">&lt;</a></li>';
    }

    for ($p = $firstPage; $p <= $lastPage; $p++) {
        echo '<li><a href="' . $href . '&' . $pointerName . '=' . $p . '">' . $p . '</a></li>';
    }

    if ($lastPage != $totalNumberOfPages) {
        $nextPage = $currentPage + 1;
        echo '<li><a href="' . $href . '&' . $pointerName . '=' . $nextPage . '">&gt;</a></li>';
        echo '<li><a href="' . $href . '&' . $pointerName . '=' . $totalNumberOfPages . '">&gt;&gt;</a></li>';
    }
    echo '</ul>';
}

// que pour equipe suisse
function nomPhotoValide($nom, $prenom, $extensionPhotos, $extensionFileName)
{
    if ($extensionPhotos == null) {
        $srcImg = VAR_REP_IMAGE_EQUIPE_SUISSE . $prenom . "_" . $nom . "." . $extensionFileName;
    } else {
        $srcImg = VAR_REP_IMAGE_EQUIPE_SUISSE . $prenom . "_" . $nom . $extensionPhotos . "." . $extensionFileName;
    }
    $srcImg = str_replace("é", "e", $srcImg);
    $srcImg = str_replace("è", "e", $srcImg);
    $srcImg = str_replace("ü", "u", $srcImg);
    $srcImg = str_replace("ä", "a", $srcImg);
    $srcImg = str_replace("ô", "o", $srcImg);
    $srcImg = str_replace("ë", "e", $srcImg);
    $srcImg = str_replace("ï", "i", $srcImg);
    $srcImg = str_replace("ñ", "n", $srcImg);
    $srcImg = str_replace("ç", "c", $srcImg);
    $srcImg = str_replace(" ", "", $srcImg);
    $srcImg = str_replace("'", "", $srcImg);
    return $srcImg;
}

function nomFichierPhotoValide($srcImg)
{
    $srcImg = str_replace("é", "e", $srcImg);
    $srcImg = str_replace("è", "e", $srcImg);
    $srcImg = str_replace("ü", "u", $srcImg);
    $srcImg = str_replace("ä", "a", $srcImg);
    $srcImg = str_replace("ô", "o", $srcImg);
    $srcImg = str_replace("ë", "e", $srcImg);
    $srcImg = str_replace("ñ", "n", $srcImg);
    $srcImg = str_replace("ç", "c", $srcImg);
    $srcImg = str_replace(" ", "", $srcImg);
    $srcImg = str_replace("'", "", $srcImg);
    return strtolower($srcImg);
}

function rewrite($label)
{
    /* Expression régulière permettant le changement des caractères accentués en
    * caractères non accentués.
    */
    $search = array(
        '@[éèêëÊË]@i',
        '@[àâäÂÄ]@i',
        '@[îïÎÏ]@i',
        '@[ûùüÛÜ]@i',
        '@[ôöÔÖ]@i',
        '@[ç]@i',
        '@[^a-zA-Z0-9]@'
    );
    $replace = array('e', 'a', 'i', 'u', 'o', 'c', ' ');
    $label = preg_replace($search, $replace, $label);
    $label = strtolower($label);
    $label = str_replace(" ", '-', $label);
    $label = preg_replace('#-+#', '-', $label);
    $label = preg_replace('#([-]+)#', '-', $label);
    trim($label, '-');

    return $label;
}

// FROM http://alanwhipple.com/2011/05/25/php-truncate-string-preserving-html-tags-words/
/**
 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
 *
 * @param string $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 *
 * @return string Trimmed string.
 */
function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
{
    $open_tags = array();
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = strlen($ending);
        $truncate = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is',
                    $line_matchings[1])) {
                    // do nothing
                    // if tag is a closing tag
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                        unset($open_tags[$pos]);
                    }
                    // if tag is an opening tag
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate'd text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ',
                $line_matchings[2]));
            if ($total_length + $content_length > $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities,
                    PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left + $entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if ($total_length >= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if ($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }
    return $truncate;
}


function sizeNewsManager($text, $nbchar, $newsId)
{
    if (strlen($text) > $nbchar) {
        return substr($text, 0,
                $nbchar) . "... " . "<p class='lireSuiteArticle'><a href='" . VAR_HREF_PAGE_PRINCIPALE . "?menuselection=1&smenuselection=1&newsIdSelection=" . $newsId . "'>" . VAR_LANG_LIRE_SUITE_ARTICLE . "</a></p>";
    }
    return $text;
}

function getLastYouTubeVideoID($channel)
{
    if ($channel == "TP") {
        $RSSurl = "http://gdata.youtube.com/feeds/base/users/TchoukballPromotion/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile";
    } else {
        $RSSurl = "http://gdata.youtube.com/feeds/base/users/tchoukballsuisse/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile";
    }

    $xml = simplexml_load_file($RSSurl);

    $xmlStr = $xml->asXML();

    $positionVideo = strpos($xmlStr, "video:");
    $debut = $positionVideo + 6;
    $youtubeID = substr($xmlStr, $debut, 11);

    return $youtubeID;
}

function getLastYouTubeVideoTitle($channel)
{
    if ($channel == "TP") {
        $RSSurl = "http://gdata.youtube.com/feeds/base/users/TchoukballPromotion/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile";
    } else {
        $RSSurl = "http://gdata.youtube.com/feeds/base/users/tchoukballsuisse/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile";
    }

    $xml = simplexml_load_file($RSSurl);

    $xmlStr = $xml->asXML();
    $xmlStr = strstr($xmlStr, "<title>");
    $xmlStr = substr($xmlStr, 7);
    $xmlStr = strstr($xmlStr, "<title>");
    $xmlStr = substr($xmlStr, 7);
    $xmlStr = strstr($xmlStr, "<title>");
    $xmlStr = substr($xmlStr, 7);
    $positionFin = strpos($xmlStr, "</title>");
    $xmlStr = substr($xmlStr, 0, $positionFin);
    $xmlStr = utf8_decode($xmlStr);

    return $xmlStr;
}

function chif_rome($num)
{
    //I V X  L  C   D   M
    //1 5 10 50 100 500 1k
    $rome = array("", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX");
    $rome2 = array("", "X", "XX", "XXX", "XL", "L", "LX", "LXX", "LXXX", "XC");
    $rome3 = array("", "C", "CC", "CCC", "CD", "D", "DC", "DCC", "DCCC", "CM");
    $rome4 = array("", "M", "MM", "MMM", "IVM", "VM", "VIM", "VIIM", "VIIIM", "IXM");
    $str = $rome[$num % 10];
    $num -= ($num % 10);
    $num /= 10;
    $str = $rome2[$num % 10] . $str;
    $num -= ($num % 10);
    $num /= 10;
    $str = $rome3[$num % 10] . $str;
    $num -= ($num % 10);
    $num /= 10;
    $str = $rome4[$num % 10] . $str;
    return $str;
}

/* from http://php.net/manual/en/function.ucwords.php */
function ucwordspecific($str, $delimiter)
{
    $delimiter_space = '- ';
    return str_replace($delimiter_space, $delimiter, ucwords(str_replace($delimiter, $delimiter_space, $str)));
}

function isValidYear($year)
{
    return preg_match("#^\d{4}$#", $year);
}

function isValidID($id)
{
    return preg_match("#^\d+$#", $id) && $id != 0;
}

function isValidMemberID($id)
{
    return isValidID($id);
}

function isValidClubID($id)
{
    return preg_match("#^\d+$#", $id); // Can be 0 for the "no-club" (which is bad)
}

function isValidNPA($npa)
{
    return preg_match("#^\d{4}$#", $npa);
}

function isValidMatchID($id)
{
    return isValidID($id);
}

function isValidVenueID($id)
{
    return isValidID($id);
}

function isValidSeasonID($id)
{
    return isValidID($id) && isValidYear($id);
}

// from http://www.bizinfosys.com/php/php-print-echo-array.html
function print_array($aArray)
{
    // Print a nicely formatted array representation:
    echo '<pre>';
    print_r($aArray);
    echo '</pre>';
}

function slugify($str)
{
    $slug = strtolower($str);

    $slug = preg_replace("/[^a-z0-9\s-]/", "", $slug);
    $slug = trim(preg_replace("/[\s-]+/", " ", $slug));
    $slug = preg_replace("/\s/", "-", $slug);

    return $slug;
}

function ajouterLogAnnuaireFSTB($idPersonne, $field, $old_value, $new_value)
{
    if ($old_value != $new_value) {
        $query = "INSERT INTO DBDLog (idDbdPersonne, field, userID, old_value, new_value) VALUES (" . $idPersonne . ", '" . $field . "', " . $_SESSION['__idUser__'] . ", '" . addslashes($old_value) . "', '" . addslashes($new_value) . "')";
        return mysql_query($query);
    } else {
        return true;
    }
}

function ajouterSSiPluriel($word, $number)
{
    if ($number > 1) {
        return $word . "s";
    } else {
        return $word;
    }
}


// FROM http://stackoverflow.com/questions/2762061/how-to-add-http-if-its-not-exists-in-the-url
function addhttp($url)
{
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}


/**
 * afficherRang function.
 *
 * Affiche le rang au championnat selon les paramètres
 *
 * @access public
 * @param mixed $idTour
 * @param mixed $typeClassement
 * @param mixed $nbMatchGagne
 * @param mixed $nbMatchPerdu
 * @param mixed $nbMatchGagnantPromoReleg
 * @param mixed $nbMatchGagnantTourFinal
 * @param mixed $rang
 * @return string
 */
function afficherRang(
    $idTour,
    $typeClassement,
    $nbMatchGagne,
    $nbMatchPerdu,
    $nbMatchGagnantPromoReleg,
    $nbMatchGagnantTourFinal,
    $rang
) {
    $rangAffiche = '-';
    if ($idTour == 2000 && ($typeClassement == 1 || $typeClassement == 2)) {
        if ($nbMatchGagne == $nbMatchGagnantPromoReleg) {
            $rangAffiche = "Promu";
        } else if ($nbMatchPerdu == $nbMatchGagnantPromoReleg) {
            $rangAffiche = "Relégué";
        }
    } else if ($idTour == 4000) {
        if ($nbMatchGagne == $nbMatchGagnantTourFinal) {
            $rangAffiche = "Passe en finale";
        } else if ($nbMatchPerdu == $nbMatchGagnantTourFinal) {
            $rangAffiche = "Passe en petite finale";
        }
    } else {
        $rangAffiche = $rang . '.';
    }
    return $rangAffiche;
}
