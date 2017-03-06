<div>
    <a href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&ajouter"><img
            src="admin/images/ajouter.png" alt="Ajouter un événement"/> Ajouter un événement</a></div>
<div>
    <a href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>"><img
            src="admin/images/liste.png" alt="Liste des événements"/> Liste des événements</a></div>

<div>
    <a href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&masques"><img
            src="admin/images/masquer.png" alt="Liste des événements masqués"/> Liste des événements masqués</a></div>
<form name="formRechercheEvenement"
      action="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>"
      method="post">
    <div><img src="admin/images/rechercher.png" alt="Rechercher un événement"/> <input type="search"
                                                                                       name="rechercheEvenement"/></div>
    <br/>
</form>

<?php

if (isset($_GET['modifier']) OR isset($_GET['ajouter']) OR isset($_POST['verification'])) {
    ?>

    <script language="javascript">
        function changeJourEntier(chkbox) {
            var plagea = document.getElementById("heureDebut");
            var plageb = document.getElementById("heureFin");
            if (chkbox.checked) {
                plagea.style.visibility = "hidden";
                plageb.style.visibility = "hidden";
            }
            else {
                plagea.style.visibility = "visible";
                plageb.style.visibility = "visible";
            }
        }
        function enregistrerModification() {
            var editerEvenement = document.getElementById("editerEvenement");
            editerEvenement.action = "?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>";
        }
    </script>

    <?php
    if (isset($_GET['modifier'])) {
        $requeteAModifier = "SELECT Calendrier_Evenements.id AS idEvenement, titre, idCategorie, description, lieu, jourEntier, dateDebut, heureDebut, dateFin, heureFin, visible FROM Calendrier_Evenements, Calendrier_Categories WHERE Calendrier_Categories.id=idCategorie AND Calendrier_Evenements.id=" . $_GET['modifier'];
        $retourAModifier = mysql_query($requeteAModifier);
        $donneesAModifier = mysql_fetch_array($retourAModifier);
        $idEvenement = $donneesAModifier['idEvenement'];
        $titre = stripslashes($donneesAModifier['titre']);
        $idCategorie = $donneesAModifier['idCategorie'];
        $description = stripslashes($donneesAModifier['description']);
        $lieu = stripslashes($donneesAModifier['lieu']);
        $jourEntier = $donneesAModifier['jourEntier'];
        $dateDebut = $donneesAModifier['dateDebut'];
        $heureDebut = $donneesAModifier['heureDebut'];
        $dateFin = $donneesAModifier['dateFin'];
        $heureFin = $donneesAModifier['heureFin'];
        $jourDebut = substr($dateDebut, 8, 2);
        $moisDebut = substr($dateDebut, 5, 2);
        $anneeDebut = substr($dateDebut, 0, 4);
        $minuteDebut = substr($heureDebut, 3, 2);
        $heureDebut = substr($heureDebut, 0, 2);
        $jourFin = substr($dateFin, 8, 2);
        $moisFin = substr($dateFin, 5, 2);
        $anneeFin = substr($dateFin, 0, 4);
        $minuteFin = substr($heureFin, 3, 2);
        $heureFin = substr($heureFin, 0, 2);
        $visible = $donneesAModifier['visible'];

        $actionModifier = "&modifier=" . $_GET['modifier'];
    } else {
        $idEvenement = "";
        $titre = "";
        $idCategorie = 0;
        $description = "";
        $lieu = "";
        $jourEntier = 0;
        $jourDebut = date('d');
        $moisDebut = date('m');
        $anneeDebut = date('Y');
        $heureDebut = date('H');
        $minuteDebut = date('i');
        $jourFin = date('d');
        $moisFin = date('m');
        $anneeFin = date('Y');
        $heureFin = date('H');
        $minuteFin = date('i');
        $visible = 0;

        $actionModifier = "";
    }
    if (isset($_POST['verification'])) {
        $idEvenement = $_POST['idEvenement'];
        $titre = $_POST['titre'];
        $idCategorie = $_POST['idCategorie'];
        $description = $_POST['description'];
        $lieu = $_POST['lieu'];
        if (isset($_POST['jourEntier'])) {
            $jourEntier = 1;
        } else {
            $jourEntier = 0;
        }
        $jourDebut = $_POST['jourDebut'];
        $moisDebut = $_POST['moisDebut'];
        $anneeDebut = $_POST['anneeDebut'];
        $heureDebut = $_POST['heureDebut'];
        $minuteDebut = $_POST['minuteDebut'];
        $jourFin = $_POST['jourFin'];
        $moisFin = $_POST['moisFin'];
        $anneeFin = $_POST['anneeFin'];
        $heureFin = $_POST['heureFin'];
        $minuteFin = $_POST['minuteFin'];
        if (isset($_POST['visible'])) {
            $visible = 1;
        } else {
            $visible = 0;
        }
    }

    ?>

    <script language="javascript">
        function selectionAutomatiqueAnnee() {
            editerEvenement.anneeFin.value = editerEvenement.anneeDebut.value;
        }
        function selectionAutomatiqueMois() {
            editerEvenement.moisFin.value = editerEvenement.moisDebut.value;
        }
        function selectionAutomatiqueJour() {
            editerEvenement.jourFin.value = editerEvenement.jourDebut.value;
        }
        function selectionAutomatiqueHeure() {
            editerEvenement.heureFin.value = editerEvenement.heureDebut.value;
        }
        function selectionAutomatiqueMinute() {
            editerEvenement.minuteFin.value = editerEvenement.minuteDebut.value;
        }
    </script>


    <form class="st-form" id="editerEvenement" name="editerEvenement" method="post"
          action="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&modifier=<?php echo $_GET['modifier']; ?>">
        <fieldset>
            <legend>Ajouter un événement</legend>
            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" size="30" value="<?php echo $titre; ?>"/>
            <?php
            if ($jourEntier == 1) {
                $checked = " checked='checked'";
                $visibility = " style='visibility:hidden;'";
            } else {
                $checked = "";
                $visibility = " style='visibility:visible;'";
            }
            $debutSelectionAnnee = min($anneeDebut - 5, date('Y') - 5);
            $finSelectionAnnee = max($anneeFin + 10, date('Y') + 10);
            ?>
            <label for="jourEntier"> L'événement dure toute la journée</label>
            <input type="checkbox" id="jourEntier" name="jourEntier"<?php echo $checked; ?>
                   onClick="changeJourEntier(this);"/>
            <label>Date de début</label>
            <div class="st-form__date">
                <span id="dateDebut">
                    <select name="jourDebut" onChange="selectionAutomatiqueJour()">
                        <?php
                        for ($jour = 1; $jour <= 31; $jour++) {
                            if ($jourDebut == $jour) {
                                $selected = " selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $jour . "'" . $selected . ">" . $jour . "</option>";
                        }
                        ?>
                    </select>.<select name="moisDebut" onChange="selectionAutomatiqueMois()">
                        <?php
                        for ($mois = 1; $mois <= 12; $mois++) {
                            if ($moisDebut == $mois) {
                                $selected = " selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $mois . "'" . $selected . ">" . $mois . "</option>";
                        }
                        ?>
                    </select>.<select name="anneeDebut" onChange="selectionAutomatiqueAnnee()">
                        <?php
                        for ($annee = $debutSelectionAnnee; $annee <= $finSelectionAnnee; $annee++) {
                            if ($anneeDebut == $annee) {
                                $selected = " selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $annee . "'" . $selected . ">" . $annee . "</option>";
                        }
                        ?>
                    </select>
                </span>
                <span id="heureDebut"<?php echo $visibility; ?>>
                     à
                    <select name="heureDebut" onChange="selectionAutomatiqueHeure()">
                        <?php
                        for ($heure = 0; $heure <= 23; $heure++) {
                            if ($heureDebut == $heure) {
                                $selected = " selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $heure . "'" . $selected . ">" . $heure . "</option>";
                        }
                        ?>
                    </select>h<select name="minuteDebut" onChange="selectionAutomatiqueMinute()">
                        <?php
                        for ($minute = 0; $minute <= 59; $minute++) {
                            if ($minuteDebut == $minute) {
                                $selected = " selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $minute . "'" . $selected . ">" . $minute . "</option>";
                        }
                        ?>
                    </select>
                </span>
            </div>
            <label>Date de fin</label>
            <div class="st-form__date">
                <span id="dateFin">
                <select name="jourFin">
                    <?php
                    for ($jour = 1; $jour <= 31; $jour++) {
                        if ($jourFin == $jour) {
                            $selected = " selected='selected'";
                        } else {
                            $selected = "";
                        }
                        echo "<option value='" . $jour . "'" . $selected . ">" . $jour . "</option>";
                    }
                    ?>
                </select>.<select name="moisFin">
                    <?php
                    for ($mois = 1; $mois <= 12; $mois++) {
                        if ($moisFin == $mois) {
                            $selected = " selected='selected'";
                        } else {
                            $selected = "";
                        }
                        echo "<option value='" . $mois . "'" . $selected . ">" . $mois . "</option>";
                    }
                    ?>
                </select>.<select name="anneeFin">
                    <?php
                    for ($annee = $debutSelectionAnnee; $annee <= $finSelectionAnnee; $annee++) {
                        if ($anneeFin == $annee) {
                            $selected = " selected='selected'";
                        } else {
                            $selected = "";
                        }
                        echo "<option value='" . $annee . "'" . $selected . ">" . $annee . "</option>";
                    }
                    ?>
                </select>
                </span>
                <span id="heureFin"<?php echo $visibility; ?>>
                     à
                    <select name="heureFin">
                        <?php
                        for ($heure = 0; $heure <= 23; $heure++) {
                            if ($heureFin == $heure) {
                                $selected = " selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $heure . "'" . $selected . ">" . $heure . "</option>";
                        }
                        ?>
                    </select>h<select name="minuteFin">
                        <?php
                        for ($minute = 0; $minute <= 59; $minute++) {
                            if ($minuteFin == $minute) {
                                $selected = " selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $minute . "'" . $selected . ">" . $minute . "</option>";
                        }
                        ?>
                    </select>
                </span>
            </div>
            <label for="lieu">Lieu</label>
            <input type="text" id="lieu" name="lieu" size="30" value="<?php echo $lieu; ?>"/>
            <label for="description">Description</label>
            <textarea id="description" name="description" cols="60"
                      rows="10"><?php echo $description; ?></textarea>
            <label for="idCategorie">Catégorie</label>
            <select id="idCategorie" name="idCategorie">
                <?php
                $requeteCategorie = "SELECT * FROM Calendrier_Categories ORDER BY nom";
                $retourCategorie = mysql_query($requeteCategorie);
                while ($donneesCategorie = mysql_fetch_array($retourCategorie)) {
                    if ($idCategorie == $donneesCategorie['id']) {
                        $selected = " selected='selected'";
                    } else {
                        $selected = "";
                    }
                    echo "<option value='" . $donneesCategorie['id'] . "'" . $selected . ">" . $donneesCategorie['nom'] . "</option>";
                }
                ?>
            </select>
            <?php
            if ($visible == 1) {
                $checked = " checked='checked'";
            } else {
                $checked = "";
            }
            ?>
            <label for="visible"> Visible</label>
            <input type="checkbox" id="visible" name="visible"<?php echo $checked; ?> />

            <input type="hidden" name="utilisateur"
                   value="<?php echo $_SESSION['__prenom__'] . $_SESSION['__nom__'] ?>"/>
            <input type="hidden" name="idEvenement" value="<?php echo $idEvenement; ?>"/>

            <?php
            if (isset($_POST['verification'])) {
                $dateEntierDebut = $anneeDebut . "-" . $moisDebut . "-" . $jourDebut;
                $dateEntierFin = $anneeFin . "-" . $moisFin . "-" . $jourFin;
                //Si c'est un ajout, on ne doit pas éviter que l'événement soit en conflit avec lui même vu qu'il n'est pas encore dans la base
                // de données. C'est pour ça que l'on définit la variable suivante :
                $evenementDejaDansBDD = "";
                if ($idEvenement != "") {
                    $evenementDejaDansBDD = "AND id!=" . $idEvenement;
                }
                $requeteVerification = "
				(SELECT titre, dateDebut, dateFin, 'event' AS Canton
				FROM Calendrier_Evenements
				WHERE (('" . $dateEntierDebut . "'>=dateDebut AND '" . $dateEntierDebut . "'<=dateFin AND '" . $dateEntierFin . "'>=dateDebut AND '" . $dateEntierFin . "'<=dateFin)
				OR ('" . $dateEntierDebut . "'<=dateDebut AND '" . $dateEntierDebut . "'<=dateFin AND '" . $dateEntierFin . "'>=dateDebut AND '" . $dateEntierFin . "'>=dateFin)
				OR ('" . $dateEntierDebut . "'>=dateDebut AND '" . $dateEntierDebut . "'<=dateFin AND '" . $dateEntierFin . "'>=dateDebut AND '" . $dateEntierFin . "'>=dateFin)
				OR ('" . $dateEntierDebut . "'<=dateDebut AND '" . $dateEntierDebut . "'<=dateFin AND '" . $dateEntierFin . "'>=dateDebut AND '" . $dateEntierFin . "'<=dateFin))
				" . $evenementDejaDansBDD . ")
				UNION
				(SELECT Calendrier_Vacances.nom AS titre, dateDebut, dateFin, Calendrier_Cantons.nom AS Canton
				FROM Calendrier_Vacances, Calendrier_Cantons
				WHERE (('" . $dateEntierDebut . "'>=dateDebut AND '" . $dateEntierDebut . "'<=dateFin AND '" . $dateEntierFin . "'>=dateDebut AND '" . $dateEntierFin . "'<=dateFin)
				OR ('" . $dateEntierDebut . "'<=dateDebut AND '" . $dateEntierDebut . "'<=dateFin AND '" . $dateEntierFin . "'>=dateDebut AND '" . $dateEntierFin . "'>=dateFin)
				OR ('" . $dateEntierDebut . "'>=dateDebut AND '" . $dateEntierDebut . "'<=dateFin AND '" . $dateEntierFin . "'>=dateDebut AND '" . $dateEntierFin . "'>=dateFin)
				OR ('" . $dateEntierDebut . "'<=dateDebut AND '" . $dateEntierDebut . "'<=dateFin AND '" . $dateEntierFin . "'>=dateDebut AND '" . $dateEntierFin . "'<=dateFin))
				AND idCanton=Calendrier_Cantons.id
				AND Calendrier_Vacances.nom!='false')";
                /* 				echo $requeteVerification; */
                $retourVerification = mysql_query($requeteVerification);
                while ($donneesVerification = mysql_fetch_array($retourVerification)) {
                    if ($donneesVerification['Canton'] == "event") {
                        echo "<p><strong>" . $agenda_evenement . "</strong> : " . $donneesVerification['titre'];
                        if ($donneesVerification['dateDebut'] == $donneesVerification['dateFin']) {
                            echo " " . date_sql2date_joli($donneesVerification['dateDebut'], $agenda_le,
                                    $_SESSION['__langue__']);
                        } else {
                            echo " " . date_sql2date_joli($donneesVerification['dateDebut'], $agenda_du,
                                    $_SESSION['__langue__']) . " " . date_sql2date_joli($donneesVerification['dateFin'],
                                    $agenda_au, $_SESSION['__langue__']) . ".";
                        }
                        echo "</p>";
                    } else {
                        echo "<p><strong>" . $agenda_vacances . "</strong> : " . $donneesVerification['titre'];
                        if ($donneesVerification['dateDebut'] == $donneesVerification['dateFin']) {
                            echo " " . date_sql2date_joli($donneesVerification['dateDebut'], $agenda_le,
                                    $_SESSION['__langue__']);
                        } else {
                            echo " " . date_sql2date_joli($donneesVerification['dateDebut'], $agenda_du,
                                    $_SESSION['__langue__']) . " " . date_sql2date_joli($donneesVerification['dateFin'],
                                    $agenda_au, $_SESSION['__langue__']) . ".";
                        }
                        echo " Canton : " . $donneesVerification['Canton'] . "</p>";
                    }
                }
            }
            ?>
            <input type="submit" name="verification" value="Vérifier" onClick="return enregistrerModification();"/>
            <?php /* Ne devrait pas s'appeler enregistrerModification, mais executer cette fonction permet de ne pas ajouter &modifier= à la fin de l'URL ce qui posait problème.*/ ?>

            <input type="submit" name="enregistrement" value="Enregistrer" onClick="return enregistrerModification();"/>
        </fieldset>
    </form>

    <?php
} else {
    if (isset($_POST['idEvenement'])) {
        if (isset($_POST['visible'])) {
            $visible = 1;
        } else {
            $visible = 0;
        }
        if (isset($_POST['jourEntier'])) {
            $jourEntier = 1;
        } else {
            $jourEntier = 0;
        }
        if ($_POST['idEvenement'] == "") { // Ajout
            $requeteAAjouter = "INSERT INTO Calendrier_Evenements (`id`, `titre`, `idCategorie`, `description`, `lieu`, `jourEntier`, `dateDebut`, `heureDebut`, `dateFin`, `heureFin`, `visible`, `utilisateur`)
			VALUES (NULL, '" . addslashes($_POST['titre']) . "', '" . $_POST['idCategorie'] . "', '" . addslashes($_POST['description']) . "', '" . addslashes($_POST['lieu']) . "', '" . $jourEntier . "', '" . $_POST['anneeDebut'] . "-" . $_POST['moisDebut'] . "-" . $_POST['jourDebut'] . "', '" . $_POST['heureDebut'] . ":" . $_POST['minuteDebut'] . ":00', '" . $_POST['anneeFin'] . "-" . $_POST['moisFin'] . "-" . $_POST['jourFin'] . "', '" . $_POST['heureFin'] . ":" . $_POST['minuteFin'] . ":00', '" . $visible . "', '" . $_POST['utilisateur'] . "')";
            mysql_query($requeteAAjouter);
            echo "<p class='notification notification--success'>Événement correctement ajouté.</p>";
        } else { // Modification
            $requeteAModifier = "UPDATE Calendrier_Evenements SET titre='" . addslashes($_POST['titre']) . "', idCategorie='" . $_POST['idCategorie'] . "', description='" . addslashes($_POST['description']) . "', lieu='" . addslashes($_POST['lieu']) . "', jourEntier='" . $jourEntier . "', dateDebut='" . $_POST['anneeDebut'] . "-" . $_POST['moisDebut'] . "-" . $_POST['jourDebut'] . "', heureDebut='" . $_POST['heureDebut'] . ":" . $_POST['minuteDebut'] . ":00', dateFin='" . $_POST['anneeFin'] . "-" . $_POST['moisFin'] . "-" . $_POST['jourFin'] . "', heureFin='" . $_POST['heureFin'] . ":" . $_POST['minuteFin'] . ":00', visible='" . $visible . "', utilisateur='" . $_POST['utilisateur'] . "' WHERE id=" . $_POST['idEvenement'];
            mysql_query($requeteAModifier);
            echo "<p class='notification notification--success'>Événement correctement modifié.</p>";
        }
    }
    if (isset($_GET['supprimer'])) { // Suppression
        $requeteASupprimer = "DELETE FROM Calendrier_Evenements WHERE id=" . $_GET['supprimer'] . " LIMIT 1";
        mysql_query($requeteASupprimer);
        echo "<p class='notification notification--success'>Événement correctement supprimé.</p>";
    }
    if (isset($_POST['rechercheEvenement'])) {
        $termeRecherche = $_POST['rechercheEvenement'];
        $termeRecherche = strtr($termeRecherche, "äëïöüáéíóúàèìòùâêîôû", "aeiouaeiouaeiouaeiou");
        $termeRecherche = htmlentities($termeRecherche);
        echo "<p>Terme recherché : \"" . $termeRecherche . "\"";
        $triRecherche = " AND (`titre` LIKE CONVERT( _utf8 '%" . $termeRecherche . "%' USING latin1) OR `description` LIKE CONVERT( _utf8 '%" . $termeRecherche . "%' USING latin1) COLLATE latin1_swedish_ci)";
    } else {
        $triRecherche = "";
    }

    if (isset($_GET['masques'])) {
        $uniquementMasques = " AND visible=0";
    } else {
        $uniquementMasques = "";
    }


    $requete = "SELECT Calendrier_Evenements.id AS idEvent, titre, description, lieu, jourEntier, couleur, nom, heureDebut, heureFin, dateDebut, dateFin, visible FROM Calendrier_Evenements, Calendrier_Categories WHERE Calendrier_Evenements.idCategorie=Calendrier_Categories.id" . $triRecherche . $uniquementMasques . " ORDER BY dateDebut DESC, heureDebut DESC";
    //echo $requete;
    $retour = mysql_query($requete);
    $nbEvenementsAffiche = mysql_num_rows($retour);
    if ($nbEvenementsAffiche == 0) {
        echo "<h4>Il n'y a rien à afficher.</h4>";
    } else {
        ?>

        <table id="AgendaAVenir">
            <tr>
                <th>&nbsp;</th>
                <th><?php echo $agenda_date; ?></th>
                <th><?php echo $agenda_evenement; ?></th>
                <th><?php echo VAR_LANG_MODIFIER; ?></th>
                <th><?php echo VAR_LANG_SUPPRIMER; ?></th>
            </tr>
            <?php
            while ($donnees = mysql_fetch_array($retour)) {
                ?>
                <tr>
                    <td class="categorie" style='background-color: #<?php echo $donnees['couleur'] ?>'></td>
                    <td class="date">
                        <?php
                        if ($donnees['dateDebut'] == $donnees['dateFin']) {
                            echo date_sql2date($donnees['dateDebut']);
                            $plusieursJours = false;
                        } else {
                            echo date_sql2date($donnees['dateDebut']) . "-" . date_sql2date($donnees['dateFin']);
                            $plusieursJours = true;
                        }
                        ?>
                    </td>
                    <td class="titre">
                        <?php echo $donnees['titre']; ?>
                        <?php
                        if ($donnees['visible'] == 0) {
                            echo " <img src='admin/images/masquer.png' alt='masqué' />";
                        }
                        ?>
                    </td>
                    <td class="modifier"><a
                            href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&modifier=<?php echo $donnees['idEvent']; ?>"><img
                                src="/admin/images/modifier.png" alt="modifier"/></a></td>
                    <td class="supprimer"><a
                            href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&supprimer=<?php echo $donnees['idEvent']; ?>"
                            onClick='return confirm("Vous êtes sur le point de supprimer cet événement \n OK pour supprimer, Annuler pour abandonner.");'><img
                                src="/admin/images/supprimer.png" alt="supprimer"/></a></td>
                    <td class="categorie" style='background-color: #<?php echo $donnees['couleur'] ?>'></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
}
?>
