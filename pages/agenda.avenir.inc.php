<?php
statInsererPageSurf(__FILE__);

if (isset($_GET['idEvenement'])) {
    include('pages/agenda.evenement.inc.php');
} else {

    // L�gende des cat�gories
    ?>
    <h4>Cat�gories</h4>

    <script language="javascript">
        function decocheLeReste() {
            var selectionCategoriesCalendrierCategorie4 = document.getElementById("categorie4");
            <?php
            $requeteCategories = "SELECT * FROM Calendrier_Categories ORDER BY nom";
            $retourCategories = mysql_query($requeteCategories);
            while($donneesCategories = mysql_fetch_array($retourCategories)){
            ?>
            var selectionCategoriesCalendrierCategorie<?php echo $donneesCategories['id']; ?> = document.getElementById("categorie<?php echo $donneesCategories['id']; ?>");

            if (selectionCategoriesCalendrierCategorie4.checked == true) {
                <?php
                if($donneesCategories['id'] != 4){
                ?>
                selectionCategoriesCalendrierCategorie<?php echo $donneesCategories['id']; ?>.checked = false;
                <?php
                }
                ?>
            }
            else {
                <?php
                if($donneesCategories['id'] != 4){
                ?>
                selectionCategoriesCalendrierCategorie<?php echo $donneesCategories['id']; ?>.checked = true;
                <?php
                }
                ?>
            }
            <?php
            }
            ?>
        }

        function verifierCheckbox(chkbox) {
            var selectionCategoriesCalendrierCategorie4 = document.getElementById("categorie4");
            if (chkbox.checked) {
                selectionCategoriesCalendrierCategorie4.checked = false;
            }
        }
    </script>
    <?php

    $requeteCategories = "SELECT * FROM Calendrier_Categories ORDER BY nom";
    $retourCategories = mysql_query($requeteCategories);
    $listchkbx = array();
    $c = 1;
    echo "<form class='selectionCategoriesCalendrier' name='selectionCategoriesCalendrier' id='selectionCategoriesCalendrier' action='' method='post'><br />";
    while ($donneesCategories = mysql_fetch_array($retourCategories)) {
        //Apr�s envoi
        if (isset($_POST['envoiSelectionCategories'])) {
            if (isset($_POST['categorie' . $donneesCategories['id']])) {
                $categorieCochee[$donneesCategories['id']] = true;
            }
        } else {
            if ($donneesCategories['id'] != 4) {
                $categorieCochee[$donneesCategories['id']] = true;
            }
        }
        if ($donneesCategories['id'] > $categorieCochee['max']) {
            $categorieCochee['max'] = $donneesCategories['id'];
        }

        //Avant envoi
        if (!$c != 1) {
            echo "&nbsp;&nbsp;&nbsp;";
        }
        if ($categorieCochee[$donneesCategories['id']]) {
            $checked = " checked='checked'";
        } else {
            $checked = "";
        }
        if ($donneesCategories['id'] == 4) {
            $specialite = " onClick='decocheLeReste();'";
        } else {
            $specialite = " onClick='verifierCheckbox(this);'";
        }
        echo "<input type='checkbox' name='categorie" . $donneesCategories['id'] . "' id='categorie" . $donneesCategories['id'] . "'" . $checked . "" . $specialite . " /> ";
        echo "<label for='categorie" . $donneesCategories['id'] . "' style='color: #" . $donneesCategories['couleur'] . "; font-weight: bold;'>" . $donneesCategories['nom'] . "</label>";
        $listchkbx[$c] = "categorie" . $donneesCategories['id'];
        $c++;
        $premier = false;
    }
    ?>
    <script language="javascript">
        function checkAll(mstrchkbx) {
            if (mstrchkbx.checked) {
                <?php
                for($k = 1;$k < $c;$k++){ // Je met $k<$c et pas $k<=$c car il y a un $c de plus que de cat�gories.
                ?>
                document.getElementById('categorie<?php echo $k; ?>').checked = true;

                <?php
                }
                ?>
                document.getElementById('categorie4').checked = false;
            }
            else {
                <?php
                for($k = 1;$k < $c;$k++){ // Je met $k<$c et pas $k<=$c car il y a un $c de plus que de cat�gories.
                ?>
                document.getElementById('categorie<?php echo $k; ?>').checked = false;

                <?php
                }
                ?>
            }
        }
    </script>
    <?php
    echo "<br /><br /><input type='submit' name='envoiSelectionCategories' value='Trier' class=\"button button--primary\" />&nbsp;&nbsp;&nbsp;
	<input type='checkbox' id='masterCheckbox' onclick='checkAll(this);' checked='checked' /><label for='masterCheckbox'>Tout (d�)cocher</label>";
    echo "</form>";


    $premier = true;
    $triageCategorie = " AND (";
    for ($k = 1; $k <= $categorieCochee['max']; $k++) {
        if ($categorieCochee[$k]) {
            if (!$premier) {
                $triageCategorie .= " OR ";
            }
            $triageCategorie .= "idCategorie=" . $k;
            $premier = false;
        }

    }
    $triageCategorie .= ")";

    echo "<h5>Cliquez sur les �v�nements pour obtenir plus d'informations</h5><br />";

    if (isset($categorieCochee[4])) { // Championnat
        $requete = "SELECT m.dateDebut, m.dateFin, m.heureDebut, m.heureFin, l.nom AS nomLieu, l.ville, m.idMatch, m.equipeA, m.equipeB, c.couleur
				  FROM Calendrier_Categories c, Championnat_Matchs m
				  LEFT OUTER JOIN Lieux l ON l.id=m.idLieu
				  WHERE c.id = 4
				  AND m.dateFin>'" . date('Y-m-d') . "'
				  ORDER BY m.dateDebut, m.heureDebut";
        $requeteEquipes = "SELECT * FROM Championnat_Equipes WHERE idEquipe!=11 ORDER BY idEquipe";
        $retourEquipes = mysql_query($requeteEquipes);
        $tableauEquipes = array();
        while ($donneesEquipes = mysql_fetch_array($retourEquipes)) {
            $tableauEquipes[$donneesEquipes['idEquipe']] = $donneesEquipes['equipe'];
        }
    } else {
        $requete = "SELECT Calendrier_Evenements.id AS idEvent, titre, description, lieu, jourEntier, couleur, nom, heureDebut, heureFin, dateDebut, dateFin FROM Calendrier_Evenements, Calendrier_Categories WHERE Calendrier_Evenements.idCategorie=Calendrier_Categories.id AND dateFin>'" . date('Y') . "-" . date('m') . "-" . date('d') . "'" . $triageCategorie . " AND visible=1 ORDER BY dateDebut, heureDebut";
        //echo $requete;
    }
    $retour = mysql_query($requete);
    if (mysql_num_rows($retour) < 1) {
        echo "<h4>" . VAR_LANG_AUCUN_EVENEMENT_A_VENIR . "</h4>";
    } else {
        ?>

        <table id="AgendaAVenir">
            <tr>
                <th>&nbsp;</th>
                <th><?php echo $agenda_date; ?></th>
                <th><?php echo $agenda_evenement; ?></th>
                <th><?php echo $agenda_lieu; ?></th>
                <th><?php echo $agenda_heure; ?></th>
            </tr>
            <?php
            while ($donnees = mysql_fetch_array($retour)) {
                if (isset($categorieCochee[4])) {
                    $lieu = $donnees['nomLieu'] . ", " . $donnees['ville'];
                    $eventURL = "/championnat/match/" . $donnees['idMatch'];
                    $titre = $tableauEquipes[$donnees['equipeA']] . " - " . $tableauEquipes[$donnees['equipeB']];
                } else {
                    $lieu = $donnees['lieu'];
                    $eventURL = "/evenement/" . $donnees['idEvent'];
                    $titre = $donnees['titre'];
                }
                ?>
                <tr>
                    <td class="categorie" style='background-color: #<?php echo $donnees['couleur'] ?>'></td>
                    <td class="date">
                        <?php
                        if ($donnees['dateDebut'] == $donnees['dateFin']) {
                            echo date_sql2date($donnees['dateDebut']);
                            $plusieursJours = false;
                        } else {
                            echo date_sql2date($donnees['dateDebut']) . " - " . date_sql2date($donnees['dateFin']);
                            $plusieursJours = true;
                        }
                        ?>
                    </td>
                    <td class="titre"><a href="<?php echo $eventURL; ?>"><?php echo $titre; ?></a></td>
                    <td class="lieu"><?php echo $lieu; ?></td>
                    <td class="heure">
                        <?php
                        if ($donnees['jourEntier'] == 1) {
                            if ($plusieursJours) {
                                echo "Journ�es enti�res";
                            } else {
                                echo "Journ�e enti�re";
                            }
                        } else {
                            echo time_sql2heure($donnees['heureDebut']) . " " . $agenda_a . " " . time_sql2heure($donnees['heureFin']);
                        }
                        ?>
                    </td>
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
