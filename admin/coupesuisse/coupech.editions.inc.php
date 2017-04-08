<div>
    <a href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&ajouter"><img
            src="admin/images/ajouter.png" alt="Ajouter une édition"/> Ajouter une édition</a></div>
<div>
    <a href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>"><img
            src="admin/images/liste.png" alt="Liste des éditions"/> Liste des éditions</a></div>
<br/>
<?php
$nbMaxJournees = 10;


if (isset($_GET['ajouter'])) {
    $idEdition = null;
    $annee = date('Y');
    $idCategorie = null;

    //Recupération d'informations de la dernière édition
    $requeteRecupInfos = "SELECT nbSetsGagnants, pointsPourRemporterSet, deuxPointsDEcart FROM CoupeCH_Evenements ORDER BY annee DESC, idCategorie LIMIT 1";
    $retourRecupInfos = mysql_query($requeteRecupInfos);
    $donneesRecupInfos = mysql_fetch_array($retourRecupInfos);
    $nbSetsGagnants = $donneesRecupInfos['nbSetsGagnants'];
    $pointsPourRemporterSet = $donneesRecupInfos['pointsPourRemporterSet'];
    $deuxPointsDEcart = $donneesRecupInfos['deuxPointsDEcart'];

    $nbJournees = 1;
    $typeEnvoi = "ajout";

} elseif (isset($_GET['modifier'])) {
    $requeteModifier = "SELECT * FROM CoupeCH_Evenements e, CoupeCH_Journees j WHERE e.idEvenement=" . $_GET['modifier'] . " AND j.idEvenement = e.idEvenement";
    $retourModifier = mysql_query($requeteModifier);
    $c = 1;
    $journee = array();
    while ($donneesModifier = mysql_fetch_array($retourModifier)) {
        $idEdition = $donneesModifier['idEvenement'];
        $annee = $donneesModifier['annee'];
        $idCategorie = $donneesModifier['idCategorie'];
        $nbSetsGagnants = $donneesModifier['nbSetsGagnants'];
        $pointsPourRemporterSet = $donneesModifier['pointsPourRemporterSet'];
        $deuxPointsDEcart = $donneesModifier['deuxPointsDEcart'];
        $journee['id' . $c] = $donneesModifier['idJournee'];
        $journee['no' . $c] = $donneesModifier['no'];
        $journee['date' . $c] = $donneesModifier['dateDebut'];
        $journee['salle' . $c] = $donneesModifier['salle'];
        $journee['ville' . $c] = $donneesModifier['ville'];

        $journee['jour' . $c] = substr($journee['date' . $c], 8, 2);
        $journee['mois' . $c] = substr($journee['date' . $c], 5, 2);

        $c++;
    }
    $nbJournees = $c - 1;
    //Recupération des informations sur les équipes

    $requeteRecupEquipes = "SELECT * FROM CoupeCH_Participations WHERE idEvenement=" . $idEdition;
    $retourRecupEquipes = mysql_query($requeteRecupEquipes);
    $equipes = array();
    while ($donneesRecupEquipes = mysql_fetch_array($retourRecupEquipes)) {
        $equipes[$donneesRecupEquipes['idEquipe']] = "joueDansCetteEdition";
    }

    $typeEnvoi = "modification";
}

if (isset($_GET['ajouter']) OR isset($_GET['modifier'])) {
    ?>
    <script type="text/javascript">
        function updateNbJournees(contenuSelect) {
            var nbJournees = contenuSelect.value
            nbJournees++;
            for (var i = 1; i < nbJournees; i++) {
                document.getElementById("journee" + i).style.display = "block";
            }
            if (nbJournees < <?php echo $nbMaxJournees + 1; ?>) {
                for (var i = nbJournees; i < <?php echo $nbMaxJournees + 1; ?>; i++) {
                    document.getElementById("journee" + i).style.display = "none";
                }
            }
        }
    </script>
    <form method="post"
          action="?menuselection=<?php echo $_GET['menuselection']; ?>&amp;smenuselection=<?php echo $_GET['smenuselection']; ?>"
          class="insererEditionCoupeCH">
        <fieldset>
            <legend>Informations de base</legend>
            <?php
            ?>

            <label for="annee">Année : </label>
            <select id="annee" name="annee">
                <?php
                $premiereEdition = 2008;
                $cetteAnnee = date('Y');
                $dans4ans = $cetteAnnee + 4;
                for ($k = $premiereEdition; $k <= $dans4ans; $k++) {
                    if ($k == $annee) {
                        $selected = " selected='selected'";
                    } else {
                        $selected = "";
                    }
                    echo "<option value='" . $k . "'" . $selected . ">" . $k . "</option>";
                }
                ?>
            </select>

            <label for="idCategorie">Catégorie : </label>
            <select id="idCategorie" name="idCategorie">
                <?php
                $requeteCategories = "SELECT * FROM CoupeCH_Categories ORDER BY idCategorie";
                $retourCategories = mysql_query($requeteCategories);
                while ($donneesCategories = mysql_fetch_array($retourCategories)) {
                    if ($donneesCategories['idCategorie'] == $idCategorie) {
                        $selected = " selected='selected'";
                    } else {
                        $selected = "";
                    }
                    echo "<option value='" . $donneesCategories['idCategorie'] . "'" . $selected . ">" . $donneesCategories['nom' . $_SESSION['__langue__']] . "</option>";
                }
                ?>
            </select>

            <label for="nbSetsGagnants">Nombre de sets gagnants : </label>
            <input type="text" size="2" id="nbSetsGagnants" name="nbSetsGagnants"
                   value="<?php echo $nbSetsGagnants; ?>"/>

            <label for="pointsPourRemporterSet">Nombre de points pour remporter un set : </label>
            <input type="text" size="2" id="pointsPourRemporterSet" name="pointsPourRemporterSet"
                   value="<?php echo $pointsPourRemporterSet; ?>"/>

            <label for="deuxPointsDEcart">Deux points d'écarts nécessaires pour remporter un set </label>
            <input type="checkbox" id="deuxPointsDEcart"
                   name="deuxPointsDEcart" <?php echo $deuxPointsDEcart == 1 ? 'checked="checked"' : ''; ?> />

        </fieldset>
        <fieldset>
            <legend>Equipes</legend>
            <?php
            //Recupération de la liste des équipes

            $requete = "SELECT * FROM CoupeCH_Equipes ORDER BY nomEquipe";
            $retour = mysql_query($requete);
            while ($donnees = mysql_fetch_array($retour)) {
                if ($equipes[$donnees['idEquipe']] == "joueDansCetteEdition") {
                    $checked = " checked='checked'";
                } else {
                    $checked = "";
                }
                ?>
                <label for="equipe<?php echo $donnees['idEquipe']; ?>"><?php echo $donnees['nomEquipe']; ?></label>
                <input type="checkbox" id="equipe<?php echo $donnees['idEquipe']; ?>"
                       name="equipe<?php echo $donnees['idEquipe']; ?>"<?php echo $checked; ?> />
                <?php
            }
            ?>
        </fieldset>
        <fieldset>
            <legend>Journées</legend>
            <label for="nbJournees">Nombre de journées : </label>
            <select id="nbJournees" name="nbJournees" onChange="updateNbJournees(this);">
                <?php
                for ($k = 1; $k <= $nbMaxJournees; $k++) {
                    if ($k == $nbJournees) {
                        $selected = " selected='selected'";
                    } else {
                        $selected = "";
                    }
                    echo "<option value='" . $k . "'" . $selected . ">" . $k . "</option>";
                }
                ?>
            </select>
            <?php
            for ($k = 1; $k <= $nbMaxJournees; $k++) {
                if ($k <= $nbJournees) {
                    $style = "display: block;";
                } else {
                    $style = "display: none;";
                }
                ?>
                <fieldset id="journee<?php echo $k; ?>" style="<?php echo $style; ?>">
                    <legend>Journée <?php echo $k; ?></legend>

                    <label for="dateJour<?php echo $k; ?>">Jour : </label>
                    <select id="dateJour<?php echo $k; ?>" name="dateJour<?php echo $k; ?>">
                        <?php
                        for ($jour = 1; $jour <= 31; $jour++) {
                            if ($jour == $journee['jour' . $k]) {
                                $selected = " selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $jour . "'" . $selected . ">" . $jour . "</option>";
                        }
                        ?>
                    </select>

                    <label for="dateMois<?php echo $k; ?>">Mois : </label>
                    <select id="dateMois<?php echo $k; ?>" name="dateMois<?php echo $k; ?>">
                        <?php
                        for ($mois = 1; $mois <= 12; $mois++) {
                            if ($mois == $journee['mois' . $k]) {
                                $selected = " selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $mois . "'" . $selected . ">" . $mois . "</option>";
                        }
                        ?>
                    </select>

                    <label for="salle<?php echo $k; ?>">Salle : </label>
                    <input type="text" id="salle<?php echo $k; ?>" name="salle<?php echo $k; ?>" size="30"
                           value="<?php echo $journee['salle' . $k]; ?>"/>

                    <label for="ville<?php echo $k; ?>">Ville : </label>
                    <input type="text" id="ville<?php echo $k; ?>" name="ville<?php echo $k; ?>" size="30"
                           value="<?php echo $journee['ville' . $k]; ?>"/>
                </fieldset>
                <?php
            }
            ?>
        </fieldset>
        <br/>
        <input type="hidden" name="idEdition" value="<?php echo $idEdition; ?>"/>
        <input type="hidden" name="typeEnvoi" value="<?php echo $typeEnvoi; ?>"/>
        <input type="submit" class="button button--primary" value="Enregistrer"/>
    </form>
    <?php
} else {
    if (isset($_POST['typeEnvoi']) AND $_POST['typeEnvoi'] == "ajout") {

        $requeteEquipes = "SELECT * FROM CoupeCH_Equipes ORDER BY nomEquipe";
        $retourEquipes = mysql_query($requeteEquipes);
        $nbEquipes = 0;
        while ($donneesEquipes = mysql_fetch_array($retourEquipes)) {
            if (isset($_POST['equipe' . $donneesEquipes['idEquipe']])) {
                $nbEquipes++;
            }
        }

        //Arrondissement du nombre d'équipes à la puissance de deux au dessus (2, 4, 8, 16, 32)
        $trouve = false;
        for ($k = 1; $k <= 5; $k++) {
            if (pow(2, $k) > $nbEquipes AND !$trouve) {
                $trouve = true;
                $nbEquipes = pow(2, $k);
            }
        }
        if (isset($_POST['deuxPointsDEcart'])) {
            $deuxPointsDEcart = 1;
        } else {
            $deuxPointsDEcart = 0;
        }

        $requeteAjout = "INSERT INTO CoupeCH_Evenements (`id`, `idCategorie`, `annee`, `nbSetsGagnants`, `pointsPourRemporterSet`, `deuxPointsDEcart`, `nbEquipes`) VALUES (NULL, '" . $_POST['idCategorie'] . "', '" . $_POST['annee'] . "', '" . $_POST['nbSetsGagnants'] . "', '" . $_POST['pointsPourRemporterSet'] . "', '" . $deuxPointsDEcart . "', '" . $nbEquipes . "')";
        mysql_query($requeteAjout);
        $idNouvelleEdition = mysql_insert_id();

        // Insertion des équipes participantes
        $requeteEquipes = "SELECT * FROM CoupeCH_Equipes ORDER BY nomEquipe";
        $retourEquipes = mysql_query($requeteEquipes);
        while ($donneesEquipes = mysql_fetch_array($retourEquipes)) {
            if (isset($_POST['equipe' . $donneesEquipes['idEquipe']])) {
                $requeteAjoutEquipe = "INSERT INTO CoupeCH_Participations (`idReg`, `idEquipe`, `idEvenement`) VALUES (NULL, '" . $donneesEquipes['idEquipe'] . "', '" . $idNouvelleEdition . "')";
                mysql_query($requeteAjoutEquipe);
            }
        }


        for ($k = 1; $k <= $_POST['nbJournees']; $k++) {
            $requeteAjoutJournee = "INSERT INTO CoupeCH_Journees (`idJournee`, `no`, `annee`, `dateDebut`, `dateFin`, `salle`, `ville`, `idCategorie`) VALUES (NULL, '" . $k . "', '" . $_POST['annee'] . "', '" . $_POST['annee'] . "-" . $_POST['dateMois' . $k] . "-" . $_POST['dateJour' . $k] . "', '" . $_POST['annee'] . "-" . $_POST['dateMois' . $k] . "-" . $_POST['dateJour' . $k] . "', '" . $_POST['salle' . $k] . "', '" . $_POST['ville' . $k] . "', '" . $_POST['idCategorie'] . "')";
            mysql_query($requeteAjoutJournee);
        }
    } elseif (isset($_POST['typeEnvoi'], $_POST['idEdition']) AND $_POST['typeEnvoi'] == "modification" AND is_numeric($_POST['idEdition'])) {
        // A FAIRE ICI

        //NE FONCTIONNE PAS, A TESTER
        // Modification des équipes participantes
        $requeteEquipes = "SELECT e.idEquipe, e.nomEquipe, p.idEvenement
						FROM CoupeCH_Equipes e
						LEFT OUTER JOIN CoupeCH_Participations p ON e.idEquipe = p.idEquipe
						AND idEvenement = " . $_POST['idEdition'] . "
						ORDER BY nomEquipe";
        $retourEquipes = mysql_query($requeteEquipes);
        while ($donneesEquipes = mysql_fetch_array($retourEquipes)) {
            if (isset($_POST['equipe' . $donneesEquipes['idEquipe']])) {
                if ($donneesEquipes['idEvenement'] == null) {
                    $requeteAjoutEquipe = "INSERT INTO CoupeCH_Participations (`id`, `idEquipe`, `idEvenement`) VALUES (NULL, '" . $donneesEquipes['idEquipe'] . "', '" . $idEdition . "')";
                    if (!mysql_query($requeteAjoutEquipe)) {
                        echo "<p class='notification notification--error'>Erreur lors de l'ajout d'équipes.</p>";
                    }
                } else {
                    //l'équipe est déjà participante à cette édition.
                }
            } else {
                if ($donneesEquipes['idEvenement'] == $_POST['idEdition']) { //l'équipe est indiquée comme participante dans la BDD, mais n'est plus cochée => suppression
                    $requeteSuppressionParticipation = "DELETE FROM CoupeCH_Participations WHERE idEvenement=" . $_POST['idEdition'] . " AND idEquipe=" . $donneesEquipes['idEquipe'];
                    if (!mysql_query($requeteSuppressionParticipation)) {
                        echo "<p class='notification notification--error'>Erreur lors de la suppression d'équipes.</p>";
                    }
                }
            }
        }

    } elseif (isset($_GET['supprimer'])) {
        // A FAIRE ICI
    }
    $requeteListe = "SELECT annee, nom" . $_SESSION['__langue__'] . ", idEvenement FROM CoupeCH_Evenements, CoupeCH_Categories WHERE CoupeCH_Evenements.idCategorie= CoupeCH_Categories.idCategorie ORDER BY annee DESC, CoupeCH_Categories.idCategorie";
    $retourListe = mysql_query($requeteListe);
    ?>

    <table class="st-table">
        <tr>
            <th>Année</th>
            <th>Catégorie</th>
            <th><?php echo VAR_LANG_MODIFIER; ?></th>
            <th><?php echo VAR_LANG_SUPPRIMER; ?></th>
        </tr>
        <?php
        while ($donneesListe = mysql_fetch_array($retourListe)) {
            ?>
            <tr>
                <td>
                    <?php echo $donneesListe['annee']; ?>
                </td>
                <td>
                    <?php echo $donneesListe['nom' . $_SESSION['__langue__']]; ?>
                </td>
                <td class="modifier"><a
                        href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&modifier=<?php echo $donneesListe['idEvenement']; ?>"><img
                            src="/admin/images/modifier.png" alt="modifier"/></a></td>
                <td class="supprimer"><a
                        href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&supprimer=<?php echo $donneesListe['idEvenement']; ?>"
                        onClick='return confirm("Vous êtes sur le point de supprimer cette édition \n OK pour supprimer, Annuler pour abandonner.");'><img
                            src="/admin/images/supprimer.png" alt="supprimer"/></a></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}
?>
