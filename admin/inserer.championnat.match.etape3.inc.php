<?php
?>
<h3>
    <?php echo VAR_LANG_ETAPE_3; ?>
</h3>
<?php
if (!isset($_GET['nbMatchs']) OR !isset($_GET['saison']) OR !isset($_GET['idCat']) OR !isset($_GET['idTour']) OR !isset($_GET['idGroupe'])) {
    echo "Erreur: il manque des informations.";
    $nbMatchs = 0;
} else {
    $nbMatchs = $_GET['nbMatchs'];
    $saison = $_GET['saison'];
    $idCategorie = $_GET['idCat'];
    $idTour = $_GET['idTour'];
    $idGroupe = $_GET['idGroupe'];

    function optionsParticipant($saison, $idCategorie, $idTour, $idGroupe)
    {
        if ($saison == '' OR $idCategorie == '' OR $idTour == '' OR $idGroupe == '') {
            $requete = "SELECT * FROM Championnat_Equipes ORDER BY equipe";
            $retour = mysql_query($requete);
            $nbLigne = mysql_affected_rows();
            while ($donnees = mysql_fetch_array($retour)) {
                echo "<option value='" . $donnees["idEquipe"] . "'>" . $donnees["equipe"] . "</option>";
            }
        } elseif ($saison != '' AND $idCategorie != '' AND $idTour != '' AND $idGroupe != '') {
            $requete = "SELECT equipe, idEquipe FROM Championnat_Equipes ORDER BY equipe";
            $retour = mysql_query($requete);
            $nbLigne = mysql_affected_rows();
            while ($donnees = mysql_fetch_array($retour)) {
                $requeteA = "SELECT idEquipe FROM Championnat_Equipes_Tours WHERE saison=" . $saison . " AND idCategorie=" . $idCategorie . " AND idTour=" . $idTour . " AND noGroupe=" . $idGroupe;
                // echo $requeteA;
                $retourA = mysql_query($requeteA);
                while ($donneesA = mysql_fetch_array($retourA)) {
                    if ($donnees['idEquipe'] == $donneesA['idEquipe']) {
                        echo "<option value='" . $donnees["idEquipe"] . "'>" . $donnees["equipe"] . "</option>";
                    }
                }
            }
        } else {
            echo "<option>ERREUR</option>";
        }

    }

    ?>
    <script language="javascript">
        function validateForm() {
            var insererMatchForm = document.getElementById("insererMatchForm");
            <?php
            for($k = 1;$k <= $nbMatchs;$k++){
            if($k == 1){
            ?>
            if (insererMatchForm.equipeA<?php echo $k; ?>.options[insererMatchForm.equipeA<?php echo $k; ?>.selectedIndex].text == insererMatchForm.equipeB<?php echo $k; ?>.options[insererMatchForm.equipeB<?php echo $k; ?>.selectedIndex].text) {
                alert("Erreur, un match doit avoir des participants différents");
                return false;
            }
            <?php
                }
                else{
                ?>
            else if (insererMatchForm.equipeA<?php echo $k; ?>.options[insererMatchForm.equipeA<?php echo $k; ?>.selectedIndex].text == insererMatchForm.equipeB<?php echo $k; ?>.options[insererMatchForm.equipeB<?php echo $k; ?>.selectedIndex].text) {
                alert("Erreur, un match doit avoir des participants différents");
                return false;
            }
            <?php
            }
            }
            ?>
        }
        $(function () {
            $("[name=equipeA_all]").change(function () {
                $("[name^=equipeA]").val($("[name=equipeA_all]").val());
            });

            $("[name=journee_all]").change(function () {
                $("[name^=journee]").val($("[name=journee_all]").val());
            });

            $("[name=typeMatch_all]").change(function () {
                $("[name^=typeMatch]").val($("[name=typeMatch_all]").val());
            });

            $("[name=idLieu_all]").change(function () {
                $("[name^=idLieu]").val($("[name=idLieu_all]").val());
            });

            $("[name=debutJour_all]").change(function () {
                $("[name^=debutJour]").val($("[name=debutJour_all]").val());
                $("[name^=finJour]").val($("[name=debutJour_all]").val());
            });
            $("[name=debutMois_all]").change(function () {
                $("[name^=debutMois]").val($("[name=debutMois_all]").val());
                $("[name^=finMois]").val($("[name=debutMois_all]").val());
            });
            $("[name=debutAnnee_all]").change(function () {
                $("[name^=debutAnnee]").val($("[name=debutAnnee_all]").val());
                $("[name^=finAnnee]").val($("[name=debutAnnee_all]").val());
            });
            $("[name=debutHeure_all]").change(function () {
                $("[name^=debutHeure]").val($("[name=debutHeure_all]").val());
                $("[name^=finHeure]").val(parseInt($("[name=debutHeure_all]").val()) + 1);
            });
            $("[name=debutMinute_all]").change(function () {
                $("[name^=debutMinute]").val($("[name=debutMinute_all]").val());
                $("[name^=finMinute]").val(parseInt($("[name=debutMinute_all]").val()) + 10);
            });

            $("[name=finJour_all]").change(function () {
                $("[name^=finJour]").val($("[name=finJour_all]").val());
            });
            $("[name=finMois_all]").change(function () {
                $("[name^=finMois]").val($("[name=finMois_all]").val());
            });
            $("[name=finAnnee_all]").change(function () {
                $("[name^=finAnnee]").val($("[name=finAnnee_all]").val());
            });
            $("[name=finHeure_all]").change(function () {
                $("[name^=finHeure]").val($("[name=finHeure_all]").val());
            });
            $("[name=finMinute_all]").change(function () {
                $("[name^=finMinute]").val($("[name=finMinute_all]").val());
            });
        });

        <?php
        for ($k = 1;$k <= $nbMatchs;$k++) {
        ?>
        function selectionTypeMatch<?php echo $k; ?>() {
            var idTour = <?php echo $idTour; ?>;
            var insererMatchForm = document.getElementById("insererMatchForm");
            if (idTour == 1 || idTour == 2 || idTour == 3 || idTour == 4) {
                insererMatchForm.typeMatch<?php echo $k; ?>.value = 0;
                insererMatchForm.typeMatch<?php echo $k; ?>.disabled = true;
            }
            else if (idTour == 2000) {
                insererMatchForm.typeMatch<?php echo $k; ?>.value = 1000;
                insererMatchForm.typeMatch<?php echo $k; ?>.disabled = true;
            }
            else {
                insererMatchForm.typeMatch<?php echo $k; ?>.disabled = false;
            }
        }

        function selectionAutomatiqueAnnee<?php echo $k; ?>() {
            insererMatchForm.finAnnee<?php echo $k; ?>.value = insererMatchForm.debutAnnee<?php echo $k; ?>.value;
        }
        function selectionAutomatiqueMois<?php echo $k; ?>() {
            insererMatchForm.finMois<?php echo $k; ?>.value = insererMatchForm.debutMois<?php echo $k; ?>.value;
        }
        function selectionAutomatiqueJour<?php echo $k; ?>() {
            insererMatchForm.finJour<?php echo $k; ?>.value = insererMatchForm.debutJour<?php echo $k; ?>.value;
        }
        function selectionAutomatiqueHeure<?php echo $k; ?>() {
            insererMatchForm.finHeure<?php echo $k; ?>.value = parseInt(insererMatchForm.debutHeure<?php echo $k; ?>.value) + 1;
        }
        function selectionAutomatiqueMinute<?php echo $k; ?>() {
            insererMatchForm.finMinute<?php echo $k; ?>.value = parseInt(insererMatchForm.debutMinute<?php echo $k; ?>.value) + 10;
        }
        <?php
        }
        ?>
    </script>
    <form id="insererMatchForm" method="post"
          action="<?php echo "?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection; ?>"
          onSubmit="return validateForm();">
        <?php
        echo "<table class='st-table'>";
        echo "<tr>";
        echo "<th>";
        echo VAR_LANG_EQUIPES;
        echo "</th>";
        echo "<th>";
        echo VAR_LANG_JOURNEE;
        echo "</th>";
        echo "<th>";
        echo VAR_LANG_TYPE_MATCH;
        echo "</th>";
        echo "<th>";
        echo VAR_LANG_DATE;
        echo "</th>";
        echo "</tr>";
        /* INDICATION POUR TOUS LES MATCHS À AJOUTER */
        echo "<tr>";
        echo "<td>";
        echo "<strong>Modifier pour<br />tous les matchs</strong>";
        echo "<br /><br />";
        echo "Équipe à domicile";
        echo "<br /><br />";
        echo "<select name='equipeA_all'>";
        optionsParticipant($saison, $idCategorie, $idTour, $idGroupe);
        echo "</select>";
        echo "</td>";
        echo "<td>";
        echo "<select name='journee_all'>";
        for ($i = 1; $i < 50; $i++) {
            echo "<option value='$i'>$i</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "<td>";
        if ($idCategorie == 0) {
            $bloquage = "disabled='disabled'";
        }
        echo "<select name='typeMatch_all' " . $bloquage . ">";
        $requete = "SELECT * FROM Championnat_Types_Matchs ORDER BY idTypeMatch";
        $retour = mysql_query($requete);
        while ($donnees = mysql_fetch_array($retour)) {
            if ($idCategorie == 0 AND $donnees['idTypeMatch'] == 1000) {
                $selectionAuto = "selected='selected'";
            } else {
                $selectionAuto = "";
            }
            echo "<option value='" . $donnees['idTypeMatch'] . "' " . $selectionAuto . ">" . $donnees["type" . $_SESSION["__langue__"] . ""] . "</option>";
        }
        echo "</select>";
        echo "<br />";
        echo "<input type='checkbox' name='necessiteDefraiementArbitre' for='necessiteDefraiementArbitre' checked='checked'/><label id='necessiteDefraiementArbitre'>Nécessite le défraiement<br />des arbitres</label>";
        echo "</td>";
        echo "<td>";
        ?>
        <table>
            <tr>
                <td><label for="idLieuAll"><?php echo $agenda_lieu; ?></label></td>
                <td colspan="3">
                    <select name="idLieu_all" id="idLieuAll">
                        <option value="NULL">Non défini</option>
                        <?php
                        $requete = "SELECT * FROM Lieux ORDER BY nomCourt";
                        $retour = mysql_query($requete);
                        while ($donnees = mysql_fetch_array($retour)) {
                            echo "<option value='" . $donnees['id'] . "'>" . $donnees["nomCourt"] . ", " . $donnees['ville'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label><?php echo $agenda_debut; ?></label></td>
                <td colspan="3">
                    <p>
                        <?php echo $agenda_date; ?> :
                        <select name="debutJour_all" id="debutJour" title="Date début (jour)">
                            <?php echo creation_liste_jour(); ?>
                        </select>
                        <select name="debutMois_all" id="debutMois" title="Date début (mois)">
                            <?php echo creation_liste_mois(); ?>
                        </select>
                        <select name="debutAnnee_all" id="debutAnnee" title="Date début (année)">
                            <?php
                            $anneeActuelle = date('Y');
                            for ($i = $saison; $i <= $saison + 1; $i++) {
                                if ($i == $anneeActuelle) {
                                    echo "<option value=" . $i . " SELECTED>" . $i . "</option>";
                                } else {
                                    echo "<option value=" . $i . ">" . $i . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <?php echo $agenda_heure; ?> :
                        <select name="debutHeure_all" id="debutHeure" title="Date début (heure)">
                            <?php echo modif_liste_heure("20"); ?>
                        </select>
                        <select name="debutMinute_all" id="debutMinute" title="Date début (minute)">
                            <?php echo modif_liste_minute("45"); ?>
                        </select>
                    </p>
                </td>
            </tr>
            <tr>
                <td><label><?php echo $agenda_fin; ?></label></td>
                <td colspan="3">
                    <p>
                        <?php echo $agenda_date; ?> :
                        <select name="finJour_all" id="finJour" title="Date fin (jour)">
                            <?php echo creation_liste_jour(); ?>
                        </select>
                        <select name="finMois_all" id="finMois" title="Date fin (mois)">
                            <?php echo creation_liste_mois(); ?>
                        </select>
                        <select name="finAnnee_all" id="finAnnee" title="Date fin (année)">
                            <?php
                            $anneeActuelle = date('Y');
                            for ($i = $saison; $i <= $saison + 1; $i++) {
                                if ($i == $anneeActuelle) {
                                    echo "<option value=" . $i . " SELECTED>" . $i . "</option>";
                                } else {
                                    echo "<option value=" . $i . ">" . $i . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <?php echo $agenda_heure; ?> :
                        <select name="finHeure_all" id="finHeure" title="Date fin (heure)">
                            <?php echo modif_liste_heure("21"); ?>
                        </select>
                        <select name="finMinute_all" id="finMinute" title="Date fin (minute)">
                            <?php echo modif_liste_minute("55"); ?>
                        </select>
                    </p>
                </td>
            </tr>
        </table>
        <?php
        echo "</td>";
        /* INDICATION SPÉCIFIQUE À CHAQUE MATCH */
        for ($k = 1; $k <= $nbMatchs; $k++) {
            echo "</tr>";
            echo "<tr>";
            echo "<td class='center'>";
            echo "<select name='equipeA" . $k . "'>";
            optionsParticipant($saison, $idCategorie, $idTour, $idGroupe);
            echo "</select>";
            echo "<br /><br />";
            echo VAR_LANG_JOUERA_AVEC;
            echo "<br /><br />";
            echo "<select name='equipeB" . $k . "'>";
            optionsParticipant($saison, $idCategorie, $idTour, $idGroupe);
            echo "</select>";
            echo "</td>";
            echo "<td>";
            echo "<select name='journee" . $k . "'>";
            $anneeActuelle = date("Y");
            for ($i = 1; $i < 50; $i++) {
                echo "<option value='$i'>$i</option>";
            }
            echo "</select>";
            echo "</td>";
            echo "<td>";
            if ($idCategorie == 0) {
                $bloquage = "disabled='disabled'";
            }
            echo "<select name='typeMatch" . $k . "' " . $bloquage . ">";
            $requete = "SELECT * FROM Championnat_Types_Matchs ORDER BY idTypeMatch";
            $retour = mysql_query($requete);
            while ($donnees = mysql_fetch_array($retour)) {
                if ($idCategorie == 0 AND $donnees['idTypeMatch'] == 1000) {
                    $selectionAuto = "selected='selected'";
                } else {
                    $selectionAuto = "";
                }
                echo "<option value='" . $donnees['idTypeMatch'] . "' " . $selectionAuto . ">" . $donnees["type" . $_SESSION["__langue__"] . ""] . "</option>";
            }
            echo "</select>";
            echo "</td>";
            echo "<td>";
            ?>
            <table>
                <!--<tr>
							<td><p><?php echo VAR_LANG_SALLE; ?></p></td>
							<td><input name="salle<?php echo $k; ?>" type="text" size="20" ></td>
							<td><p><?php echo VAR_LANG_VILLE; ?></p></td>
							<td><input name="ville<?php echo $k; ?>" type="text" size="20" ></td>
						</tr>-->
                <tr>
                    <td><label for="locationSelector"><?php echo $agenda_lieu; ?></label></td>
                    <td colspan="3">
                        <select name="idLieu<?php echo $k; ?>" id="locationSelector">
                            <option value="NULL">Non défini</option>
                            <?php
                            $requete = "SELECT * FROM Lieux ORDER BY nomCourt";
                            $retour = mysql_query($requete);
                            while ($donnees = mysql_fetch_array($retour)) {
                                echo "<option value='" . $donnees['id'] . "'>" . $donnees["nomCourt"] . ", " . $donnees['ville'] . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label><?php echo $agenda_debut; ?></label></td>
                    <td colspan="3">
                        <p>
                            <?php echo $agenda_date; ?> :
                            <select name="debutJour<?php echo $k; ?>" id="debutJour" title="Date début (jour)"
                                    onChange="selectionAutomatiqueJour<?php echo $k; ?>()">
                                <?php echo creation_liste_jour(); ?>
                            </select>
                            <select name="debutMois<?php echo $k; ?>" id="debutMois" title="Date début (mois)"
                                    onChange="selectionAutomatiqueMois<?php echo $k; ?>()">
                                <?php echo creation_liste_mois(); ?>
                            </select>
                            <select name="debutAnnee<?php echo $k; ?>" id="debutAnnee" title="Date début (année)"
                                    onChange="selectionAutomatiqueAnnee<?php echo $k; ?>()">
                                <?php
                                $anneeActuelle = date('Y');
                                for ($i = $saison; $i <= $saison + 1; $i++) {
                                    if ($i == $anneeActuelle) {
                                        echo "<option value=" . $i . " SELECTED>" . $i . "</option>";
                                    } else {
                                        echo "<option value=" . $i . ">" . $i . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <?php echo $agenda_heure; ?> :
                            <select name="debutHeure<?php echo $k; ?>" id="debutHeure" title="Date début (heure)"
                                    onChange="selectionAutomatiqueHeure<?php echo $k; ?>()">
                                <?php echo modif_liste_heure("20"); ?>
                            </select>
                            <select name="debutMinute<?php echo $k; ?>" id="debutMinute" title="Date début (minute)"
                                    onChange="selectionAutomatiqueMinute<?php echo $k; ?>()">
                                <?php echo modif_liste_minute("45"); ?>
                            </select>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td><label><?php echo $agenda_fin; ?></label></td>
                    <td colspan="3">
                        <p>
                            <?php echo $agenda_date; ?> :
                            <select name="finJour<?php echo $k; ?>" id="finJour" title="Date fin (jour)">
                                <?php echo creation_liste_jour(); ?>
                            </select>
                            <select name="finMois<?php echo $k; ?>" id="finMois" title="Date fin (mois)">
                                <?php echo creation_liste_mois(); ?>
                            </select>
                            <select name="finAnnee<?php echo $k; ?>" id="finAnnee" title="Date fin (année)">
                                <?php
                                $anneeActuelle = date('Y');
                                for ($i = $saison; $i <= $saison + 1; $i++) {
                                    if ($i == $anneeActuelle) {
                                        echo "<option value=" . $i . " SELECTED>" . $i . "</option>";
                                    } else {
                                        echo "<option value=" . $i . ">" . $i . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <?php echo $agenda_heure; ?> :
                            <select name="finHeure<?php echo $k; ?>" id="finHeure" title="Date fin (heure)">
                                <?php echo modif_liste_heure("21"); ?>
                            </select>
                            <select name="finMinute<?php echo $k; ?>" id="finMinute" title="Date fin (minute)">
                                <?php echo modif_liste_minute("55"); ?>
                            </select>
                        </p>
                    </td>
                </tr>
            </table>
        <?php
        echo "</td>";
        echo "</tr>";
        ?>
            <script language="javascript">
                selectionTypeMatch<?php echo $k; ?>();
            </script>
            <?php
        }
        echo "</table><br /><br />";
        ?>
        <input type="hidden" name="nbMatchs" value="<?php echo $nbMatchs; ?>">
        <input type="hidden" name="saison" value="<?php echo $saison; ?>">
        <input type="hidden" name="idCategorie" value="<?php echo $idCategorie; ?>">
        <input type="hidden" name="idTour" value="<?php echo $idTour; ?>">
        <input type="hidden" name="idGroupe" value="<?php echo $idGroupe; ?>">
        <input type="hidden" name="action" value="insererMatchs2">
        <input type="submit" name="submit" value="<?php echo VAR_LANG_INSERER; ?>" class="button button--primary">
    </form>
    <?php
}
?>
