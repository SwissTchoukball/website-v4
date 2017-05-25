<?php ?>
<h3>
    <?php echo VAR_LANG_ETAPE_3; ?>
</h3>

<div class="modifierMatch">
    <?php
    $requete = "SELECT * FROM CoupeCH_Matchs WHERE idMatch=" . $_GET['modMatch'];
    $retour = mysql_query($requete) or die($requete . " " . mysql_error());
    $donnees = mysql_fetch_array($retour);

    $forfait = $donnees['forfait'];

    // Détermination du nom des équipes.
    if ($donnees['equipeA'] == 0) {
        if ($donnees['forfait'] == 3) {
            $equipeA = "-";
        } else { // Affiche les possibilités si encore inconnnu
            $idTypeMatchA = $donnees['idTypeMatch'] * 2;
            $ordreA1 = $donnees['ordre'] * 2;
            $ordreA2 = $ordreA1 - 1; // pas besoin ici

            $requeteIDEquipeA = "SELECT equipeA, equipeB FROM CoupeCH_Matchs WHERE idTypeMatch=" . $idTypeMatchA . " AND ordre=" . $ordreA1;
            $retourIDEquipeA = mysql_query($requeteIDEquipeA);
            $donneesIDEquipeA = mysql_fetch_array($retourIDEquipeA);

            $requeteEquipeA = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=" . $donneesIDEquipeA['equipeA'];
            $retourEquipeA = mysql_query($requeteEquipeA);
            $donneesEquipeA = mysql_fetch_array($retourEquipeA);

            $requeteEquipeB = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=" . $donneesIDEquipeA['equipeB'];
            $retourEquipeB = mysql_query($requeteEquipeB);
            $donneesEquipeB = mysql_fetch_array($retourEquipeB);

            $equipeA = $donneesEquipeA['nomEquipe'] . "<br />ou<br />" . $donneesEquipeB['nomEquipe'];

        }
    } else {
        $requeteEquipeA = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=" . $donnees['equipeA'];
        $retourEquipeA = mysql_query($requeteEquipeA);
        $donneesEquipeA = mysql_fetch_array($retourEquipeA);
        $equipeA = $donneesEquipeA['nomEquipe'];
    }
    if ($donnees['equipeB'] == 0) {
        if ($donnees['forfait'] == 3) {
            $equipeB = "-";
        } else { // Affiche les possibilités si encore inconnnu
            $idTypeMatchA = $donnees['idTypeMatch'] * 2;
            $ordreA1 = $donnees['ordre'] * 2; // besoin juste pour calculer ordreA2
            $ordreA2 = $ordreA1 - 1;

            $requeteIDEquipeB = "SELECT equipeA, equipeB FROM CoupeCH_Matchs WHERE idTypeMatch=" . $idTypeMatchA . " AND ordre=" . $ordreA2;
            $retourIDEquipeB = mysql_query($requeteIDEquipeB);
            $donneesIDEquipeB = mysql_fetch_array($retourIDEquipeB);

            $requeteEquipeA = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=" . $donneesIDEquipeB['equipeA'];
            $retourEquipeA = mysql_query($requeteEquipeA);
            $donneesEquipeA = mysql_fetch_array($retourEquipeA);

            $requeteEquipeB = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=" . $donneesIDEquipeB['equipeB'];
            $retourEquipeB = mysql_query($requeteEquipeB);
            $donneesEquipeB = mysql_fetch_array($retourEquipeB);

            $equipeB = $donneesEquipeA['nomEquipe'] . "<br />ou<br />" . $donneesEquipeB['nomEquipe'];

        }
    } else {
        $requeteEquipeB = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=" . $donnees['equipeB'];
        $retourEquipeB = mysql_query($requeteEquipeB);
        $donneesEquipeB = mysql_fetch_array($retourEquipeB);
        $equipeB = $donneesEquipeB['nomEquipe'];
    }


    // Récupération des informations sur la journée
    $idJournee = $donnees['idJournee'];
    $requeteJournee = "SELECT * FROM CoupeCH_Journees WHERE idJournee=" . $donnees['idJournee'];
    $retourJournee = mysql_query($requeteJournee);
    $donneesJournee = mysql_fetch_array($retourJournee);
    $noJournee = $donneesJournee['no'];
    $salle = $donneesJournee['salle'];
    $ville = $donneesJournee['ville'];
    $annee = $donneesJournee['annee'];
    $idCategorie = $donneesJournee['idCategorie'];
    $dateSQL = $donneesJournee['dateDebut'];

    //Récupération du score en cas de forfait ou disqualification ainsi que du nombre de set gagnants
    $requeteGenerale = "SELECT * FROM CoupeCH_Categories_Par_Annee WHERE annee=" . $annee;
    $retourGeneral = mysql_query($requeteGenerale);
    $donneesGenerales = mysql_fetch_array($retourGeneral);
    $nbSetsGagnants = $donneesGenerales['nbSetsGagnants'];
    if ($nbSetsGagnants == 0) { // Jeux normal sans sets
        $nbMaxSets = 1;
        $nbSetsGagnants = 1;
    } else {
        $nbMaxSets = ($nbSetsGagnants * 2) - 1;
    }
    $scoreGagnantParForfait = $donneesGenerales['scoreGagnantParForfait'];

    //Type de match
    $requeteTypeMatch = "SELECT nom" . $_SESSION['__langue__'] . " FROM CoupeCH_Type_Matchs WHERE idTypeMatch=" . $donnees['idTypeMatch'];
    $retourTypeMatch = mysql_query($requeteTypeMatch);
    $donneesTypeMatch = mysql_fetch_array($retourTypeMatch);
    $typeMatch = $donneesTypeMatch['nom' . $_SESSION["__langue__"]];


    //JAVASCRIPT pour l'affichage.
    ?>

    <script language="javascript">
        function changeEtatForfait(chkbox) {
            if ((chkbox != null && chkbox.name == "AGagneParForfait") || (chkbox == null && modifierUnMatch.AGagneParForfait.checked)) {

                <?php
                for($k = 1;$k <= $nbMaxSets;$k++){
                if($k <= $nbSetsGagnants){
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.value = <?php echo $scoreGagnantParForfait; ?>;
                modifierUnMatch.scoreB<?php echo $k; ?>.value = 0;
                <?php
                }
                else
                {
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.value = 0;
                modifierUnMatch.scoreB<?php echo $k; ?>.value = 0;
                <?php
                }
                }
                ?>
                if (modifierUnMatch.AGagneParForfait.checked && modifierUnMatch.BGagneParForfait.checked) {
                    modifierUnMatch.BGagneParForfait.checked = false;
                }
                <?php
                for($k = 1;$k <= $nbMaxSets;$k++){
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.disabled = true;
                modifierUnMatch.scoreB<?php echo $k; ?>.disabled = true;
                <?php
                }
                ?>
                modifierUnMatch.ADisqualifie.disabled = true;
                modifierUnMatch.BDisqualifie.disabled = true;
            }
            // pas de else, car appele par saison onchange
            if ((chkbox != null && chkbox.name == "BGagneParForfait") || (chkbox == null && modifierUnMatch.BGagneParForfait.checked)) {
                <?php
                for($k = 1;$k <= $nbMaxSets;$k++){
                if($k <= $nbSetsGagnants){
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.value = 0;
                modifierUnMatch.scoreB<?php echo $k; ?>.value = <?php echo $scoreGagnantParForfait; ?>;
                <?php
                }
                else
                {
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.value = 0;
                modifierUnMatch.scoreB<?php echo $k; ?>.value = 0;
                <?php
                }
                }
                ?>
                if (modifierUnMatch.BGagneParForfait && modifierUnMatch.AGagneParForfait.checked) {
                    modifierUnMatch.AGagneParForfait.checked = false;
                }
                <?php
                for($k = 1;$k <= $nbMaxSets;$k++){
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.disabled = true;
                modifierUnMatch.scoreB<?php echo $k; ?>.disabled = true;
                <?php
                }
                ?>
                modifierUnMatch.ADisqualifie.disabled = true;
                modifierUnMatch.BDisqualifie.disabled = true;
            }
            if (modifierUnMatch.AGagneParForfait.checked == false && modifierUnMatch.BGagneParForfait.checked == false) {

                <?php
                for($k = 1;$k <= $nbMaxSets;$k++){
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.disabled = false;
                modifierUnMatch.scoreB<?php echo $k; ?>.disabled = false;
                modifierUnMatch.scoreA<?php echo $k; ?>.value = <?php echo $donnees['scoreA' . $k]; ?>;
                modifierUnMatch.scoreB<?php echo $k; ?>.value = <?php echo $donnees['scoreB' . $k]; ?>;
                <?php
                }
                ?>
                modifierUnMatch.ADisqualifie.disabled = false;
                modifierUnMatch.BDisqualifie.disabled = false;
            }
        }

        function changeEtatDisqualification(chkbox) {
            if ((chkbox != null && chkbox.name == "ADisqualifie") || (chkbox == null && modifierUnMatch.ADisqualifie.checked)) {

                <?php
                for($k = 1;$k <= $nbMaxSets;$k++){
                if($k <= $nbSetsGagnants){
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.value = 0;
                modifierUnMatch.scoreB<?php echo $k; ?>.value = <?php echo $scoreGagnantParForfait; ?>;
                <?php
                }
                else
                {
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.value = 0;
                modifierUnMatch.scoreB<?php echo $k; ?>.value = 0;
                <?php
                }
                }
                ?>
                if (modifierUnMatch.ADisqualifie.checked && modifierUnMatch.BDisqualifie.checked) {
                    modifierUnMatch.BDisqualifie.checked = false;
                }
                <?php
                for($k = 1;$k <= $nbMaxSets;$k++){
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.disabled = true;
                modifierUnMatch.scoreB<?php echo $k; ?>.disabled = true;
                <?php
                }
                ?>
                modifierUnMatch.AGagneParForfait.disabled = true;
                modifierUnMatch.BGagneParForfait.disabled = true;
            }
            // pas de else, car appele par saison onchange
            if ((chkbox != null && chkbox.name == "BDisqualifie") || (chkbox == null && modifierUnMatch.BDisqualifie.checked)) {
                <?php
                for($k = 1;$k <= $nbMaxSets;$k++){
                if($k <= $nbSetsGagnants){
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.value = <?php echo $scoreGagnantParForfait; ?>;
                modifierUnMatch.scoreB<?php echo $k; ?>.value = 0;
                <?php
                }
                else
                {
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.value = 0;
                modifierUnMatch.scoreB<?php echo $k; ?>.value = 0;
                <?php
                }
                }
                ?>
                if (modifierUnMatch.BDisqualifie && modifierUnMatch.ADisqualifie.checked) {
                    modifierUnMatch.ADisqualifie.checked = false;
                }

                <?php
                for($k = 1;$k <= $nbMaxSets;$k++){
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.disabled = true;
                modifierUnMatch.scoreB<?php echo $k; ?>.disabled = true;
                <?php
                }
                ?>
                modifierUnMatch.AGagneParForfait.disabled = true;
                modifierUnMatch.BGagneParForfait.disabled = true;
            }
            if (modifierUnMatch.ADisqualifie.checked == false && modifierUnMatch.BDisqualifie.checked == false) {

                <?php
                for($k = 1;$k <= $nbMaxSets;$k++){
                ?>
                modifierUnMatch.scoreA<?php echo $k; ?>.disabled = false;
                modifierUnMatch.scoreB<?php echo $k; ?>.disabled = false;
                modifierUnMatch.scoreA<?php echo $k; ?>.value = <?php echo $donnees['scoreA' . $k]; ?>;
                modifierUnMatch.scoreB<?php echo $k; ?>.value = <?php echo $donnees['scoreB' . $k]; ?>;
                <?php
                }
                ?>
                modifierUnMatch.AGagneParForfait.disabled = false;
                modifierUnMatch.BGagneParForfait.disabled = false;
            }
        }
        function selectionAutomatiqueHeure() {
            modifierUnMatch.finHeure.value = modifierUnMatch.debutHeure.value;
        }
        function selectionAutomatiqueMinute() {
            modifierUnMatch.finMinute.value = modifierUnMatch.debutMinute.value;
        }
    </script>

    <?php
    // Fonction d'affichage du score pour l'affichage
    function optionsScore($scoreEquipeSelected)
    {
        for ($i = 0; $i < 120; $i++) {
            if ($i == $scoreEquipeSelected) {
                $selected = "selected='selected'";
            } else {
                $selected = "";
            }
            echo "<option value='" . $i . "' " . $selected . ">$i</option>";
        }
    }

    //Transformation de la forme de la date et de l'heure
    $date = date_sql2date($dateSQL); //Date SQL en Date normale
    $date = preg_replace('#(.+)-(.+)-(.+)#', '$1.$2.$3', $date); //Remplacement des - par des .
    $heureDebut = substr($donnees['heureDebut'], 0, 2);
    $minuteDebut = substr($donnees['heureDebut'], 3, 2);
    $heureFin = substr($donnees['heureFin'], 0, 2);
    $minuteFin = substr($donnees['heureFin'], 3, 2);


    //AFFICHAGE
    echo VAR_LANG_EDITION . " " . $annee;
    echo "<br />";
    echo VAR_LANG_JOURNEE . " " . $noJournee;
    echo "<br />";
    echo $date;
    if ($salle != "") {
        echo " / " . $salle;
    }
    echo " / " . $ville;
    echo "<br />";
    echo $typeMatch;
    if ($donnees['idTypeMatch'] != 1 OR $donnees['idTypeMatch'] != -1) {
        echo " " . $donnees['ordre'];
    }
    if ($forfait != 3) { // si ce n'est pas une autoqualification, le match à lieu, donc on affiche l'heure
        echo "<br />";
        echo "<br />";
        echo $heure;
    }
    ?>
    <form name="modifierUnMatch"
          action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>"
          method="post">
        <table border="0" align="center">
            <?php
            if ($forfait != 3) {
                ?>
                <tr>
                    <td>De <select name="debutHeure" id="debutHeure" onChange="selectionAutomatiqueHeure()">
                            <?php echo modif_liste_heure($heureDebut); ?>
                        </select>h<select name="debutMinute" id="debutMinute">
                            <?php echo modif_liste_minute($minuteDebut); ?>
                        </select>
                    </td>
                    <td>à</td>
                    <td><select name="finHeure" id="finHeure">
                            <?php echo modif_liste_heure($heureFin); ?>
                        </select>h<select name="finMinute" id="finMinute">
                            <?php echo modif_liste_minute($minuteFin); ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <input type="hidden" name="EquipeA" value="<?php echo $equipeA; ?>"/>
                        <strong><?php echo $equipeA; ?></strong>
                    </td>
                    <td>-</td>
                    <td align="left">
                        <input type="hidden" name="EquipeB" value="<?php echo $equipeB; ?>"/>
                        <strong><?php echo $equipeB; ?></strong>
                    </td>
                </tr>
                <?php
                for ($k = 1; $k <= $nbMaxSets; $k++) {
                    ?>
                    <tr>
                        <td align="right">
                            <select name="scoreA<?php echo $k; ?>">
                                <?php optionsScore($donnees['scoreA' . $k]); ?>
                            </select>
                        </td>
                        <td>-</td>
                        <td align="left">
                            <select name="scoreB<?php echo $k; ?>">
                                <?php optionsScore($donnees['scoreB' . $k]); ?>
                            </select>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <?php
                    if ($forfait == 1) { //forfait
                        $c1 = 0; // Compteur de sets 15-0
                        $c2 = 0; // Compteur de sets 0-0
                        for ($k = 1; $k <= $nbMaxSets; $k++) {
                            if ($donnees['scoreA' . $k] == $scoreGagnantParForfait AND $donnees['scoreB' . $k] == 0) {
                                $c1++;
                            } elseif ($donnees['scoreA' . $k] == 0 AND $donnees['scoreB' . $k] == 0) {
                                $c2++;
                            }
                        }
                        if ($c1 == $nbSetsGagnants AND $c1 + $c2 == $nbMaxSets) {
                            $AGagneParForfait = "checked='checked'";
                            $BGagneParForfait = "";
                        }
                        $c1 = 0; // Compteur de sets 15-0
                        $c2 = 0; // Compteur de sets 0-0
                        for ($k = 1; $k <= $nbMaxSets; $k++) {
                            if ($donnees['scoreB' . $k] == $scoreGagnantParForfait AND $donnees['scoreA' . $k] == 0) {
                                $c1++;
                            } elseif ($donnees['scoreA' . $k] == 0 AND $donnees['scoreB' . $k] == 0) {
                                $c2++;
                            }
                        }
                        if ($c1 == $nbSetsGagnants AND $c1 + $c2 == $nbMaxSets) {
                            $AGagneParForfait = "";
                            $BGagneParForfait = "checked='checked'";
                        }
                    } elseif ($forfait == 2) { //disqualification
                        $c1 = 0; // Compteur de sets 15-0
                        $c2 = 0; // Compteur de sets 0-0
                        for ($k = 1; $k <= $nbMaxSets; $k++) {
                            if ($donnees['scoreA' . $k] == $scoreGagnantParForfait AND $donnees['scoreB' . $k] == 0) {
                                $c1++;
                            } elseif ($donnees['scoreA' . $k] == 0 AND $donnees['scoreB' . $k] == 0) {
                                $c2++;
                            }
                        }
                        if ($c1 == $nbSetsGagnants AND $c1 + $c2 == $nbMaxSets) {
                            $ADisqualifie = "";
                            $BDisqualifie = "checked='checked'";
                        }
                        $c1 = 0; // Compteur de sets 15-0
                        $c2 = 0; // Compteur de sets 0-0
                        for ($k = 1; $k <= $nbMaxSets; $k++) {
                            if ($donnees['scoreB' . $k] == $scoreGagnantParForfait AND $donnees['scoreA' . $k] == 0) {
                                $c1++;
                            } elseif ($donnees['scoreA' . $k] == 0 AND $donnees['scoreB' . $k] == 0) {
                                $c2++;
                            }
                        }
                        if ($c1 == $nbSetsGagnants AND $c1 + $c2 == $nbMaxSets) {
                            $ADisqualifie = "checked='checked'";
                            $BDisqualifie = "";
                        }
                    } else {
                        $BGagneParForfait = "";
                        $AGagneParForfait = "";
                        $ADisqualifie = "";
                        $BDisqualifie = "";
                    }
                    ?>
                    <td align="right">
                        <p>Gagne par forfait<input name="AGagneParForfait" type="checkbox" class='couleurCheckBox'
                                                   onclick="changeEtatForfait(this);" <?php echo $AGagneParForfait; ?> />
                        </p>
                    </td>
                    <td></td>
                    <td align="left">
                        <p><input name="BGagneParForfait" type="checkbox" class='couleurCheckBox'
                                  onclick="changeEtatForfait(this);" <?php echo $BGagneParForfait; ?> />Gagne par
                            forfait</p>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <p>Disqualifié<input name="ADisqualifie" type="checkbox" class='couleurCheckBox'
                                             onclick="changeEtatDisqualification(this);" <?php echo $ADisqualifie; ?> />
                        </p>
                    </td>
                    <td></td>
                    <td align="left">
                        <p><input name="BDisqualifie" type="checkbox" class='couleurCheckBox'
                                  onclick="changeEtatDisqualification(this);" <?php echo $BDisqualifie; ?> />Disqualifié
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input type="hidden" name="idMatch" value="<?php echo $_GET['modMatch']; ?>">
                        <input type="hidden" name="nbSetsGagnants" value="<?php echo $nbSetsGagnants; ?>">
                        <input type="hidden" name="nbMaxSets" value="<?php echo $nbMaxSets; ?>">
                        <input type="hidden" name="scoreGagnantParForfait"
                               value="<?php echo $scoreGagnantParForfait; ?>">
                        <input type="hidden" name="annee" value="<?php echo $annee; ?>">
                        <input type="hidden" name="idCategorie" value="<?php echo $idCategorie; ?>">
                        <input type="hidden" name="action" value="modificationMatch">
                        <input type='submit' value='<?php echo VAR_LANG_MODIFIER; ?>' class="button button--primary">
                    </td>
                </tr>
                <?php
            } // Fin si forfait!=3 == normal
            else { // Auto-qualification
                ?>
                <tr>
                    <td colspan="3"><strong><?php echo $equipeA . " est autoqualifié"; ?></strong></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <script language="javascript">
            changeEtatForfait();
            changeEtatDisqualification();
        </script>
    </form>
    <?php

    ?>
</div>
