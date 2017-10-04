<?php ?>
<h3>
    <?php echo VAR_LANG_ETAPE_2; ?>
</h3>
<script language="Javascript">
    function testAutoQualif() {
        var etape2 = document.getElementById("formulaireInsertionCoupeCH");
        <?php
        for($k = 1;$k <= $_POST['nbEquipes'];$k++){
        if ($k % 2 == 0) {
            $j = $k - 1;
            $autoqualif = false;
        } else {
            $j = $k + 1;
            $autoqualif = true;
        }
        ?>
        var autoqualifieA = document.getElementById("autoqualifie<?php echo $k; ?>");
        var autoqualifieB = document.getElementById("autoqualifie<?php echo $j; ?>");
        if (etape2.equipe<?php echo $k; ?>.value != 0) {
            if (etape2.equipe<?php echo $j; ?>.value == 0) {
                <?php
                if ($autoqualif) {
                    echo "autoqualifieA.style.visibility = \"visible\";";
                    echo "autoqualifieB.style.visibility = \"hidden\";";
                } else {
                    echo "alert(\"Seule l'équipe du haut peut être autoqualifiée\");";
                    echo "etape2.equipe" . $k . ".value=0;";
                    echo "autoqualifieA.style.visibility = \"hidden\";";
                    echo "autoqualifieB.style.visibility = \"hidden\";";
                }
                ?>
            }
            else {
                autoqualifieA.style.visibility = "hidden";
                autoqualifieB.style.visibility = "hidden";
            }
        }
        else {
            if (etape2.equipe<?php echo $j; ?>.value == 0) {
                autoqualifieA.style.visibility = "hidden";
                autoqualifieB.style.visibility = "hidden";
            }
            else {
                <?php
                if (!$autoqualif) {
                    echo "autoqualifieA.style.visibility = \"hidden\";";
                    echo "autoqualifieB.style.visibility = \"visible\";";
                } else {
                    echo "alert(\"Seule l'équipe du haut peut être autoqualifiée\");";
                    echo "etape2.equipe" . $j . ".value=0;";
                    echo "autoqualifieA.style.visibility = \"hidden\";";
                    echo "autoqualifieB.style.visibility = \"hidden\";";
                }
                ?>
            }
        }
        <?php
        } // fin boucle for
        ?>
    }

    function testDoublons(listeEquipe) {
        var etape2 = document.getElementById("formulaireInsertionCoupeCH");
        var erreur = 0;
        <?php
        for($k = 1;$k <= $_POST['nbEquipes'];$k++){
        ?>
        if (listeEquipe.value == etape2.equipe<?php echo $k; ?>.value && listeEquipe.value != 0) {
            erreur++;
        }
        <?php
        }
        ?>
        if (erreur > 1) {
            alert("Cette équipe est déjà dans le tableau de départ !");
            listeEquipe.value = 0;
            testAutoQualif();
        }
    }

    function checkSaisie() {
        var etape2 = document.getElementById("formulaireInsertionCoupeCH");
        var counter = 0;
        <?php
        for($k = 1;$k <= $_POST['nbEquipes'];$k++){
        ?>
        if (etape2.equipe<?php echo $k; ?>.value == 0) {
            counter++;
        }
        <?php
        }
        ?>
        if (counter > <?php echo $_POST['nbEquipes'] - ($_POST['nbEquipes'] / 2) - 1; ?>) {
            alert("Trop d'équipes indéfinies. (" + counter + ")");
            return false;
        }
        else {
            return true;
        }
    }
</script>
<form method="post" action="" id="formulaireInsertionCoupeCH" onSubmit="return checkSaisie();">
    <fieldset>
        <legend><?php echo VAR_LANG_ETAPE_1; ?></legend>
        <label for="annee">Année : </label> <?php echo $_POST['annee']; ?>
        <input type="hidden" name="annee" value="<?php echo $_POST['annee']; ?>"/><br/><br/>
        <?php
        $requeteCat = "SELECT nom" . $_SESSION['__langue__'] . " FROM CoupeCH_Categories ORDER BY idCategorie";
        $retourCat = mysql_query($requeteCat);
        $donneesCat = mysql_fetch_array($retourCat)
        ?>
        <label for="categorie">Catégorie : </label> <?php echo $donneesCat['nom' . $_SESSION['__langue__']]; ?>
        <input type="hidden" name="categorie" value="<?php echo $_POST['categorie']; ?>"/><br/><br/>
        <label for="nbEquipes">Nombre d'équipes : </label> <?php echo $_POST['nbEquipes']; ?>
        <input type="hidden" name="nbEquipes" value="<?php echo $_POST['nbEquipes']; ?>"/><br/><br/>
        <?php
        if ($_POST['sets'] == "oui") {
            ?>
            Les matchs se joueront en
            <select name="nbSetsGagnants" id="nbSetsGagnants">
                <option>1</option>
                <option>2</option>
                <option selected="selected">3</option> <?php /* max 3 car la BDD peut pas + au niveau des colonnes */ ?>
            </select>
            sets gagnants.
            <?php
            $setOuMatch = "chaque set";
        } else {
            ?>
            <input type="hidden" name="nbSetsGagnants" id="nbSetsGagnants" value="0"/>
            <?php
            $setOuMatch = "le match";
        }
        ?><br/>
        Si une équipe déclare forfait ou se fait disqualifier, son adversaire gagne <?php echo $setOuMatch; ?>
        <select name="scoreGagnantParForfait" id="scoreGagnantParForfait" value="15">
            <?php
            for ($i = 0; $i <= 120; $i++) {
                if ($i == 15) { // score par défaut pour les sets
                    $selected = "selected='selected'";
                } else {
                    $selected = "";
                }
                echo "<option value='" . $i . "' " . $selected . ">$i</option>";
            }
            ?>
        </select>
        à 0.
        <br/><br/>
    </fieldset>
    <fieldset>
        <legend><?php echo VAR_LANG_ETAPE_2; ?></legend>
        <?php
        for ($k = 1; $k <= $_POST['nbEquipes']; $k++) {
            ?>
            <label for="equipe<?php echo $k; ?>">Equipe <?php echo $k; ?> : </label>
            <select name="equipe<?php echo $k; ?>" id="equipe<?php echo $k; ?>"
                    onChange="testAutoQualif();testDoublons(this);">
                <option value="0">Aucune</option>
                <?php
                $requeteEquipes = "SELECT * FROM CoupeCH_Equipes ORDER BY nomEquipe";
                $retourEquipes = mysql_query($requeteEquipes);
                while ($donneesEquipes = mysql_fetch_array($retourEquipes)) {
                    ?>
                    <option
                        value="<?php echo $donneesEquipes['idEquipe']; ?>"><?php echo $donneesEquipes['nomEquipe'] ?></option>
                    <?php
                }
                ?>
            </select>
            <span id="autoqualifie<?php echo $k; ?>" style="visibility:hidden;"><strong>Autoqualifié !!!</strong></span>
            <br/>
            <?php
            if ($k % 2 == 0) {
                echo "<br />";
            }
        }
        ?>
    </fieldset>

    <p>
        <input type="hidden" name="prochaineEtape" value="etape3"/>
        <!-- FAIRE VERIFICATION PAS DOUBLONS EQUIPE + AVERTISSEMENT AUTO QUALIF -->
        <input type="submit" class="button button--primary" value="<?php echo VAR_LANG_ETAPE_3; ?>"/>
    </p>
</form>
