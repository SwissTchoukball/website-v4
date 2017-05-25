<script lang="javascript">
    function showHideSubscriptions() {
        var subscriptionsList = document.getElementById("abonnementsCalendrier");
        var switchLink = document.getElementById("showHideSubscriptions");
        if (subscriptionsList.style.display === "none") {
            subscriptionsList.style.display = "block";
            switchLink.innerHTML = "Masquer les abonnements";
        } else {
            subscriptionsList.style.display = "none";
            switchLink.innerHTML = "Afficher les abonnements";
        }
    }
</script>
<?php
statInsererPageSurf(__FILE__);
if (isset($_GET['idEvenement'])) {
    include('agenda.evenement.inc.php');
} else {

    // On fait un listing du nom des �quipes avec leur ID.
    $requeteEquipes = "SELECT * FROM Championnat_Equipes WHERE idEquipe!=11 ORDER BY idEquipe";
    $retourEquipes = mysql_query($requeteEquipes);
    $tableauEquipes = array();
    while ($donneesEquipes = mysql_fetch_array($retourEquipes)) {
        $tableauEquipes[$donneesEquipes['idEquipe']] = $donneesEquipes['equipe'];
    }

    // On d�fini quel mois afficher
    if (isset($_GET['mois']) AND $_GET['mois'] > 0 AND $_GET['mois'] <= 12) {
        $mois = $_GET['mois'];
    } else {
        $mois = date('n');
    }
    // On d�fini quelle ann�e
    if (isset($_GET['annee']) AND preg_match('#[0-9]{4}#', $_GET['annee'])) {
        $annee = $_GET['annee'];
    } else {
        $annee = date('Y');
    }
    // On d�fini si l'affichage par semaine est activ�
    if (isset($_GET['semaine']) AND isset($_GET['jour'])) {
        $affichageSemaine = true;
    } else {
        $affichageSemaine = false;
    }
    if (isset($_GET['jour'])) {
        $jour = $_GET['jour'];
        $affichageJour = true;
    } else {
        $jour = date('j');
        $affichageJour = false;
    }


    $timestampPremierJourMois = mktime(0, 0, 0, $mois, 1, $annee); // timestamp du 1er $mois $annee � minuit.
    $timestampPremierJourMoisSuivant = mktime(0, 0, 0, $mois + 1, 1,
        $annee); // timestamp du 1er $mois+1 $annee � minuit.
    $timestampPremierJourMoisPrecedant = mktime(0, 0, 0, $mois - 1, 1,
        $annee); // timestamp du 1er $mois-1 $annee � minuit.

    //On d�fini le nombre de jour dans le mois (actuel, suivant et pr�c�dant)
    $nombreJoursMois = date('t', $timestampPremierJourMois);
    $nombreJoursMoisSuivant = date('t', $timestampPremierJourMoisSuivant);
    $nombreJoursMoisPrecedant = date('t', $timestampPremierJourMoisPrecedant);


    //On d�fini le jour de la semaine du premier jour du mois (0 (pour dimanche) � 6 (pour samedi))
    $jourSemainePremierJourMois = date('w', $timestampPremierJourMois);
    //On transforme en 1 (pour Lundi) � 7 (pour Dimanche)
    if ($jourSemainePremierJourMois == 0) {
        $jourSemainePremierJourMois = 7;
    }

    //On d�fini le nombre de cases vides la premi�re semaine du mois
    $nombreCasesVidesPremiereSemaine = 7 - (8 - $jourSemainePremierJourMois);


    $timestampDernierJourMois = mktime(0, 0, 0, $mois, $nombreJoursMois,
        $annee); // timestamp du dernier jour $mois $annee � minuit.

    //On d�fini le jour de la semaine du dernier jour du mois (0 (pour dimanche) � 6 (pour samedi))
    $jourSemaineDernierJourMois = date('w', $timestampDernierJourMois);
    //On transforme en 1 (pour Lundi) � 7 (pour Dimanche)
    if ($jourSemaineDernierJourMois == 0) {
        $jourSemaineDernierJourMois = 7;
    }

    //On d�fini le nombre de cases vides la derni�re semaine du mois
    $nombreCasesVidesDerniereSemaine = 7 - $jourSemaineDernierJourMois;

    //On d�fini le premier jour de la semaine
    $timestampJour = mktime(0, 0, 0, $mois, $jour, $annee); // timestamp du $jour $mois $annee � minuit.
    $jourSemaineJour = date('w', $timestampJour);
    $numeroSemaine = date('W', $timestampJour);
    //On transforme en 1 (pour Lundi) � 7 (pour Dimanche)
    if ($jourSemaineJour == 0) {
        $jourSemaineJour = 7;
    }
    $jourAReculer = $jourSemaineJour - 1;
    $jourPremierJourSemaine = $jour - $jourAReculer;


    //Variables
    $affichageParJour = false;
    $affichageParSemaine = true;

    ?>
    <h4>Abonnements</h4>
    <p><strong>Abonnez-vous avec votre ordinateur ou votre smartphone aux calendriers
            de <?php echo VAR_LANG_ASSOCIATION_NAME_ARTICLE; ?> !</strong></p>
    <p><a id="showHideSubscriptions" href="#" onclick="showHideSubscriptions();">Afficher les abonnements</a></p>
    <ul id="abonnementsCalendrier" style="display: none;">
        <li><a href="webcal://<?php echo $_SERVER['HTTP_HOST']; ?>/fstb-calendar.php">Tous les autres �v�nements sauf le
                championnat</a></li>
        <li><a href="webcal://<?php echo $_SERVER['HTTP_HOST']; ?>/fstb-calendar.php?championnat">Matchs de
                championnat</a></li>
        <li><a href="webcal://<?php echo $_SERVER['HTTP_HOST']; ?>/fstb-calendar.php?entrainements">Entra�nements</a>
        </li>
        <?php
        $getTeams = mysql_query("SELECT idEquipe, equipe FROM Championnat_Equipes WHERE actif=1 ORDER BY equipe");
        while ($team = mysql_fetch_assoc($getTeams)) {
            ?>
            <li>
                <a href="webcal://<?php echo $_SERVER['HTTP_HOST']; ?>/fstb-calendar.php?championnat&equipe=<?php echo $team['idEquipe']; ?>">Matchs
                    de <?php echo $team['equipe']; ?></a></li>
            <?php
        }
        ?>
    </ul>

    <?php

    $requeteCategories = "SELECT * FROM Calendrier_Categories ORDER BY id";
    $retourCategories = mysql_query($requeteCategories);
    $categorieCochee = array();
    while ($donneesCategories = mysql_fetch_array($retourCategories)) {
        if (isset($_POST['envoiSelectionCategories'])) {
            if (isset($_POST['categorie' . $donneesCategories['id']])) {
                $categorieCochee[$donneesCategories['id']] = true;
            }
        } else {
            $categorieCochee[$donneesCategories['id']] = true;
        }
        $categorieCochee['max'] = $donneesCategories['id'];
    }

    // L�gende des cat�gories
    echo '<h4>' . VAR_LANG_CATEGORIES . '</h4>';

    $requeteCategories = "SELECT * FROM Calendrier_Categories ORDER BY nom";
    $retourCategories = mysql_query($requeteCategories);
    $c = 1;
    $listchkbx = array();
    echo "<form class='selectionCategoriesCalendrier' id='selectionCategoriesCalendrier' action='' method='post'>";
    while ($donneesCategories = mysql_fetch_array($retourCategories)) {
        if ($c != 1) {
            echo "&nbsp;&nbsp;&nbsp;";
        }
        if ($categorieCochee[$donneesCategories['id']]) {
            $checked = " checked='checked'";
        } else {
            $checked = "";
        }
        echo "<input type='checkbox' name='categorie" . $donneesCategories['id'] . "' id='categorie" . $donneesCategories['id'] . "'" . $checked . " /> ";
        echo "<label for='categorie" . $donneesCategories['id'] . "' style='color: " . $donneesCategories['couleur'] . "; font-weight: bold;'>" . $donneesCategories['nom'] . "</label>";
        $listchkbx[$c] = "categorie" . $donneesCategories['id'];
        $c++;
    }
    ?>
    <script language="javascript">
        function checkAll(mstrchkbx) {
            <?php
            for($k = 1;$k < $c;$k++){ // Je met $k<$c et pas $k<=$c car il y a un $c de plus que de cat�gories.
            ?>
            document.getElementById('categorie<?php echo $k; ?>').checked = mstrchkbx.checked;
            <?php
            }
            ?>
        }
    </script>
    <?php
    echo "<input type='submit' name='envoiSelectionCategories' value='Trier' class=\"button button--primary\" />&nbsp;&nbsp;&nbsp;
	<input type='checkbox' id='masterCheckbox' onclick='checkAll(this);' checked='checked' /><label for='masterCheckbox'>Tout (d�)cocher</label>";
    echo "</form>";

    if ($affichageSemaine) { // Affichage par semaine
        include('agenda.semaine.inc.php');
    } elseif ($affichageJour) { // Affichage par jour
        include('agenda.jour.inc.php');
    } else { // Affichage par mois
        include('agenda.mois.inc.php');
    }
}
?>
