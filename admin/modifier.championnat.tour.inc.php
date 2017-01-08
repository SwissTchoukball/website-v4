<div class="modifierPhase">
    <?php
    if (isset($_POST["action"]) && $_POST["action"] == "modifier") {
        include "modifier.un.championnat.tour.inc.php";
    } else {

        if (isset($_POST["action"]) && $_POST["action"] == "modifierTour") {

            $saison = $_POST["saison"];
            $categorie = $_POST["categorie"];
            $tour = $_POST["tour"];
            $tourPrecedent = $_POST["tourPrecedent"];
            $groupe = $_POST["idGroupe"];

            $requete = "DELETE FROM  Championnat_Equipes_Tours WHERE saison=" . $saison . " AND idCategorie=" . $categorie . " AND idTour=" . $tour . " AND noGroupe=" . $groupe . "";
            // echo $requete;
            mysql_query($requete);

            //idGroupe ChampionnatGroupe
            while (list(, $val) = each($participant)) {

                // inserer l'equipe avec ses points initiaux du tour
                $requete = "INSERT INTO Championnat_Equipes_Tours VALUES ('', " . $saison . ", " . $categorie . ", " . $tour . ", " . $tourPrecedent . ", " . $groupe . "," . $val . ",0,0,0,0,0,0,0,0,0,0)";
                // echo $requete;
                mysql_query($requete);
            }
            include('championnat.miseajour.equipes.tour.inc.php');
            echo "<h3>Modification effectuée avec succès</h3>";
        }

        include "modifier.selection.championnat.tour.inc.php";
    }
    ?>
</div>
