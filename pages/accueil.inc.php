<div class="homepage">
    <div class="homepage__news">
        <div class="homepage__news__last">
            <h1>News</h1>
            <?php
            $TAILLE_NEWS = 500;

            $requete = mysql_query("SELECT * FROM News ORDER BY premiereNews DESC, Date DESC LIMIT 0,1");
            while ($donnees = mysql_fetch_array($requete)) {

                $titre = $donnees['titre' . $_SESSION["__langue__"]];
                $corps = $donnees['corps' . $_SESSION["__langue__"]];
                if ($titre == "") {
                    $titre = $donnees['titre' . $VAR_TABLEAU_DES_LANGUES[0][0]];
                }
                if ($corps == "") {
                    $corps = $donnees['corps' . $VAR_TABLEAU_DES_LANGUES[0][0]];
                }

                echo "<h2>" . $titre . "</h2>";
                echo "<div class='news_body'>";
                if ($donnees['image'] != 0) { // On affiche l'image si il y en a une.
                    $retourbis = mysql_query("SELECT * FROM Uploads WHERE id='" . $donnees['image'] . "'");
                    $donneesbis = mysql_fetch_array($retourbis);
                    echo "<img src='/uploads/" . $donneesbis['fichier'] . "' alt='" . $donneesbis['titre'] . "' />";
                }
                echo truncateHtml(markdown($corps), $TAILLE_NEWS,
                    "... " . "<p class='lireSuiteArticle'><a href='/news/" . $donnees['id'] . "'>" . VAR_LANG_LIRE_SUITE_ARTICLE . "</a></p>");
                echo "<p class='news-date'>Posté " . date_sql2date_joli($donnees["date"], "le", "Fr", false) . "</p>";
                echo "</div>";
            }
            ?>
        </div>
        <div class="homepage__news__rest">
            <?php echo "<h2><a href='" . VAR_HREF_PAGE_PRINCIPALE . "?" . VAR_HREF_LIEN_MENU . "=51'>" . VAR_LANG_DERNIERES_NEWS . "</a></h2>"; ?>
            <ul class="homepage__dates-list">
                <?php
                $requete = mysql_query("SELECT * FROM News ORDER BY date DESC LIMIT 1, 5");
                while ($donnees = mysql_fetch_array($requete)) {
                    $titre = $donnees['titre' . $_SESSION["__langue__"]];
                    if ($titre == "") {
                        $titre = $donnees['titre' . $VAR_TABLEAU_DES_LANGUES[0][0]];
                    }
                    echo "<li>";
                    echo "<a href='/news/" . $donnees['id'] . "'>" . $titre . "</a> ";
                    echo "<span class='news-date'>" . date_sql2date_joli($donnees["date"], null, $_SESSION['__langue__'], false) . "</span>";
                    echo "</li>";
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="homepage__next-events">
        <div class="homepage__next-events__others">
            <?php echo "<h1><a href='" . VAR_HREF_PAGE_PRINCIPALE . "?" . VAR_HREF_LIEN_MENU . "=4'>" . VAR_LANG_PROCHAINS_EVENEMENTS . "</a></h1>"; ?>
            <ul class="homepage__dates-list">
                <?php
                $aujourdhui = date_actuelle();
                $requete = mysql_query("SELECT * FROM Calendrier_Evenements WHERE dateDebut > '" . $aujourdhui . "' AND dateDebut != 0 ORDER BY dateDebut LIMIT 0, 6");
                while ($donnees = mysql_fetch_array($requete)) {
                    echo "<li>";
                    echo "<a href='/evenement/" . $donnees['id'] . "'>" . $donnees["titre"] . "</a><br/>";
                    echo ucfirst(date_sql2date_joli($donnees["dateDebut"], null, $_SESSION['__langue__']));
                    if ($donnees['jourEntier'] == 0) {
                        echo " " . $agenda_a . " " . time_sql2heure($donnees['heureDebut']);
                    }
                    echo "</li>";

                }
                ?>
            </ul>
        </div>
        <div class="homepage__next-events__matches">
            <?php echo "<h1><a href='" . VAR_HREF_PAGE_PRINCIPALE . "?" . VAR_HREF_LIEN_MENU . "=22'>" . VAR_LANG_PROCHAINS_MATCHS . "</a></h1>"; ?>
            <ul class="homepage__dates-list">
                <?php
                $aujourdhui = date_actuelle();
                $requete = mysql_query("SELECT * FROM Championnat_Matchs WHERE dateDebut >= '" . $aujourdhui . "' AND dateDebut != 0 ORDER BY dateDebut LIMIT 0, 6");
                while ($donnees = mysql_fetch_array($requete)) {
                    echo "<li>";
                    echo "<a href='/championnat/match/" . $donnees['idMatch'] . "'>";
                    $requeteA = "SELECT * FROM Championnat_Equipes WHERE idEquipe=" . $donnees['equipeA'];
                    $retourA = mysql_query($requeteA);
                    $donneesA = mysql_fetch_array($retourA);
                    echo $donneesA['equipe'];
                    echo " - ";
                    $requeteA = "SELECT * FROM Championnat_Equipes WHERE idEquipe=" . $donnees['equipeB'];
                    $retourA = mysql_query($requeteA);
                    $donneesA = mysql_fetch_array($retourA);
                    echo $donneesA['equipe'];
                    if ($donnees['dateReportDebut'] != '0000-00-00' AND $donnees['dateReportFin'] != '0000-00-00' AND $donnees['heureReportDebut'] != '00:00:00' AND $donnees['heureReportFin'] != '00:00:00') {
                        echo " : Match reporté au " . date_sql2date($donnees['dateReportDebut']);
                    }
                    echo "</a><br/>";
                    echo ucfirst(date_sql2date_joli($donnees["dateDebut"], null, $_SESSION['__langue__'], true));
                    if ($donnees['jourEntier'] == 0) {
                        echo " " . $agenda_a . " " . time_sql2heure($donnees['heureDebut']);
                    }
                    echo "</li>";

                }
                ?>
            </ul>
        </div>
    </div>
    <div class="homepage__championship-ranking">

        <?php

        //CHAMPIONNAT

        $annee = annee(date_actuelle());
        if (mois(date_actuelle()) < 8) {
            $annee--;
        }
        $couleurLigneA = "#ffffff";
        $couleurLigneB = "#ffe6e7";

        // Affichage classement

        include('championnat.fonctions.tri.classement.A.inc.php');
        include('championnat.fonctions.tri.classement.B.inc.php');
        include('championnat.fonctions.tri.classement.C.inc.php');
        include('championnat.fonctions.tri.classement.D.inc.php');
        include('championnat.fonctions.tri.classement.E.inc.php');
        include('championnat.fonctions.tri.classement.F.inc.php');

        $debug = false;

        /* Sélection de la politique des points de l'année choisie. */

        $retourAnnee = mysql_query("SELECT * FROM Championnat_Saisons WHERE saison=" . $annee);
        $donnees = mysql_fetch_array($retourAnnee);
        $pointsMatchGagne = $donnees['pointsMatchGagne'];
        $pointsMatchNul = $donnees['pointsMatchNul'];
        $pointsMatchPerdu = $donnees['pointsMatchPerdu'];
        $pointsMatchForfait = $donnees['pointsMatchForfait'];
        $nbMatchGagnantTourFinal = $donnees['nbMatchGagnantTourFinal'];
        $nbMatchGagnantPlayoff = $donnees['nbMatchGagnantPlayoff'];
        $nbMatchGagnantPlayout = $donnees['nbMatchGagnantPlayout'];
        $nbMatchGagnantPromoReleg = $donnees['nbMatchGagnantPromoReleg'];

        /* Requête pour les noms de Catégorie et Tour afin de pas devoir les chercher à chaque fois avec une requête */

        $requeteNomCategorie = "SELECT idCategorie AS id, categorie" . $_SESSION['__langue__'] . " AS nom FROM Championnat_Categories";
        $retourNomCategorie = mysql_query($requeteNomCategorie);
        $nomCategorie = array();
        while ($donnesNomCategorie = mysql_fetch_array($retourNomCategorie)) {
            $nomCategorie[$donnesNomCategorie['id']] = $donnesNomCategorie['nom'];
        }

        $requeteNomTour = "SELECT idTour AS id, tour" . $_SESSION['__langue__'] . " AS nom FROM Championnat_Types_Tours";
        $retourNomTour = mysql_query($requeteNomTour);
        $nomTour = array();
        while ($donnesNomTour = mysql_fetch_array($retourNomTour)) {
            $nomTour[$donnesNomTour['id']] = $donnesNomTour['nom'];
        }


        /* Requête définissant les de classements à faire et leur type puis execution d'une boucle pour chaque classement du dernier tour de chaque catégorie ATTENTION : différent de la page Classement ! */

        $requeteNbClassementA =
            "SELECT DISTINCT idCategorie
             FROM Championnat_Equipes_Tours
             WHERE saison=" . $annee . "
             AND (idCategorie = 0 OR idCategorie = 1 OR idCategorie = 2)
             ORDER BY idCategorie";
        if ($debug) {
            echo "<br /><br />Categorie : " . $requeteNbClassementA;
        }
        $retourNbClassementA = mysql_query($requeteNbClassementA);
        $nbRows = mysql_num_rows($retourNbClassementA);
        if ($nbRows == 0) {
            $championnatEnCours = 0;
        } else {
            $championnatEnCours = 1;
        }

        if ($championnatEnCours) {
            echo "<h1><a href='" . VAR_HREF_PAGE_PRINCIPALE . "?" . VAR_HREF_LIEN_MENU . "=24'>" . VAR_LANG_CHAMPIONNAT . "</a></h1>";
        }
        while ($donneesNbClassementA = mysql_fetch_array($retourNbClassementA)) {
            $requeteNbClassementB = "SELECT DISTINCT idTour FROM Championnat_Equipes_Tours WHERE saison=" . $annee . " AND idCategorie=" . $donneesNbClassementA['idCategorie'] . " ORDER BY idTour DESC LIMIT 1";
            if ($debug) {
                echo "<br /><br />Tour : " . $requeteNbClassementB;
            }
            $retourNbClassementB = mysql_query($requeteNbClassementB);
            $donneesNbClassementB = mysql_fetch_array($retourNbClassementB);

            $requeteNbClassementC = "SELECT DISTINCT noGroupe FROM Championnat_Equipes_Tours WHERE saison=" . $annee . " AND idCategorie=" . $donneesNbClassementA['idCategorie'] . " AND idTour=" . $donneesNbClassementB['idTour'] . " ORDER BY noGroupe";
            if ($debug) {
                echo "<br /><br />Groupe : " . $requeteNbClassementC;
            }
            $retourNbClassementC = mysql_query($requeteNbClassementC);
            while ($donneesNbClassementC = mysql_fetch_array($retourNbClassementC)) {
                $idCategorie = $donneesNbClassementA['idCategorie'];
                $idTour = $donneesNbClassementB['idTour'];
                $noGroupe = $donneesNbClassementC['noGroupe'];
                if ($debug) {
                    echo "<h4>ID TOUR : " . $idTour . "</h4>";
                }
                // Informations pour les fonctions plus bas.
                $informations = array();
                $informations['annee'] = $annee;
                $informations['idCategorie'] = $idCategorie;
                $informations['idTour'] = $idTour;
                $informations['noGroupe'] = $noGroupe;


                /* Triage par points*/

                $groupeTriPoints = array();

                $requete = "SELECT * FROM Championnat_Equipes_Tours WHERE saison=" . $annee . " AND idCategorie=" . $idCategorie . " AND idTour=" . $idTour . " AND noGroupe=" . $noGroupe . " ORDER BY points DESC";
                if ($debug) {
                    echo "<br /><br />Tri par points : " . $requete;
                }
                $retourTriPoints = mysql_query($requete);
                $a = 0;
                while ($donneesTriPoints = mysql_fetch_array($retourTriPoints)) { // Vérification du nombre de points pour chaque équipe
                    if ($a == 0 OR $donneesTriPoints['points'] != $points) { // SI première équipe OU équipe précédante n'a pas le même nombre de points
                        $a++; // Nouveau groupe de tri par points
                        $b = 1; // Réinitialisation du numéro d'équipe du groupe
                        $points = $donneesTriPoints['points']; // Nombre de point qu'a le groupe de tri par points
                    }
                    $groupeTriPoints[$a][$b] = $donneesTriPoints['idEquipe']; //ID d'équipe placé dans un groupe de tri par points
                    $b++; //Nouveau numéro d'équipe
                } // Fin boucle Tri Points
                $groupeTriPoints[0] = $a; //la position 0 du tableau étant libre, on peut lui donner le nombre de groupes a égalités pour la fonction.
                if ($debug) {
                    echo "<br />Tableau de groupement des égalités de points : ";
                    print_r($groupeTriPoints);
                    echo "<br />";
                    $etatMemoire = memory_get_usage();
                    echo "memory_get_usage : " . $etatMemoire;
                    echo "<br />";
                }


                $tableau = triParPointsInterne($informations, $groupeTriPoints, $debug);
                if ($debug) {
                    echo "<br />Tableau de groupement des égalités de points interne : ";
                    print_r($tableau);
                    echo "<br />";
                    $etatMemoire = memory_get_usage();
                    echo "memory_get_usage : " . $etatMemoire;
                    echo "<br />";
                }

                $tableau = triDifferenceScorePointsInterne($informations, $tableau, $debug);
                if ($debug) {
                    echo "<br />Tableau de groupement des égalités de différence de points(score) interne : ";
                    print_r($tableau);
                    echo "<br />";
                    $etatMemoire = memory_get_usage();
                    echo "memory_get_usage : " . $etatMemoire;
                    echo "<br />";
                }
                $tableau = triScorePointsMarquesInterne($informations, $tableau, $debug);
                if ($debug) {
                    echo "<br />Tableau de groupement des égalités de points marqués interne : ";
                    print_r($tableau);
                    echo "<br />";
                    $etatMemoire = memory_get_usage();
                    echo "memory_get_usage : " . $etatMemoire;
                    echo "<br />";
                }
                $tableau = triDifferenceScorePoints($informations, $tableau, $debug);
                if ($debug) {
                    echo "<br />Tableau de groupement des égalités de goalaverage : ";
                    print_r($tableau);
                    echo "<br />";
                    $etatMemoire = memory_get_usage();
                    echo "memory_get_usage : " . $etatMemoire;
                    echo "<br />";
                }
                $tableau = triScorePointsMarques($informations, $tableau, $debug);
                if ($debug) {
                    echo "<br />Tableau de groupement des points marques : ";
                    print_r($tableau);
                    echo "<br />";
                    $etatMemoire = memory_get_usage();
                    echo "memory_get_usage : " . $etatMemoire;
                    echo "<br />";
                }
                $tableau = triEgaliteParfaite($informations, $tableau, $debug);
                if ($debug) {
                    echo "<br />Tableau de groupement des égalités parfaite : ";
                    print_r($tableau);
                    echo "<br />";
                    $etatMemoire = memory_get_usage();
                    echo "memory_get_usage : " . $etatMemoire;
                    echo "<br />";
                }

                /* AFFICHAGE CLASSEMENT */

                if (($idTour == 4000 AND $nbMatchGagnantPlayoff > 1) OR ($idTour == 3000 AND $nbMatchGagnantPlayout > 1) OR ($idTour == 2000 AND $nbMatchGagnantPromoReleg > 1) OR ($idTour == 10000 AND $nbMatchGagnantTourFinal > 1)) { // Play-off, Play-out, Promotion/Relegation ou tour final de + de 1 match
                    $typeClassement = 1;
                } elseif (($idTour == 4000 AND $nbMatchGagnantPlayoff == 1) OR ($idTour == 3000 AND $nbMatchGagnantPlayout == 1) OR ($idTour == 2000 AND $nbMatchGagnantPromoReleg == 1) OR ($idTour == 10000 AND $nbMatchGagnantTourFinal == 1)) { // Play-off, Play-out, Promotion/Relegation, tour final de 1 match
                    $typeClassement = 2;
                } else { //Tour normal
                    $typeClassement = 3;
                }

                if (!isset($idCategoriePrecedenteBoucle) OR $idCategoriePrecedenteBoucle != $idCategorie) {
                    $idTourPrecedenteBoucle = 0;
                    $noGroupePrecedenteBoucle = 0;
                    echo '<h3 class="alt">' . $nomCategorie[$idCategorie] . '</h3>';
                }
                if ((!isset($idTourPrecedenteBoucle) OR $idTourPrecedenteBoucle != $idTour) AND $idTour != 2000) {
                    $noGroupePrecedenteBoucle = 0;
                    echo '<h4>' . $nomTour[$idTour] . '</h4>';
                }
                if ((!isset($noGroupePrecedenteBoucle) OR $noGroupePrecedenteBoucle != $noGroupe) AND $noGroupe != 0) {
                    echo '<h5>' . VAR_LANG_GROUPE . ' ' . $noGroupe . '</h5>';
                }
                echo "<table class='homepage__championship-ranking__table'>";

                if ($idTour != 10000) { // Pas un tour final
                    $i = 1;
                    for ($k = 1; $k < count($tableau); $k++) {
                        $idEquipe = $tableau[$k][1];
                        $requete = "SELECT equipe, nbMatchJoue, nbMatchGagne, nbMatchNul, nbMatchPerdu, nbMatchForfait, nbPointMarque, nbPointRecu, goolaverage, points FROM Championnat_Equipes, Championnat_Equipes_Tours WHERE Championnat_Equipes.idEquipe=" . $idEquipe . " AND Championnat_Equipes_Tours.idEquipe=Championnat_Equipes.idEquipe AND saison=" . $annee . " AND idCategorie=" . $idCategorie . " AND idTour=" . $idTour . " AND noGroupe=" . $noGroupe;
                        if ($debug) {
                            echo "<br /><br />Affichage des équipes qui ne sont pas à égalité : " . $requete;
                        }
                        $retourEquipeClassement = mysql_query($requete);
                        $donneesEquipeClassement = mysql_fetch_array($retourEquipeClassement);
                        if ($i % 2 == 0) {
                            $style = "background-color:" . $couleurLigneA . ";";
                        } else {
                            $style = "background-color:" . $couleurLigneB . ";";
                        }
                        echo "<tr style='" . $style . "'>";
                        if (isset($idTourPrecedenteBoucle) AND $idTourPrecedenteBoucle == $idTour AND $noGroupePrecedenteBoucle != $noGroupe) { //On ne doit pas remettre le classement à zéro !
                            $classement = $k + $dernierClassement - 1;
                        } else {
                            $classement = $k;
                        }
                        echo "<td class='homepage__championship-ranking__table__rank'>" . afficherRang($idTour, $typeClassement, $donneesEquipeClassement['nbMatchGagne'],
                                $donneesEquipeClassement['nbMatchPerdu'], $nbMatchGagnantPromoReleg,
                                $nbMatchGagnantTourFinal, $classement) . "</td>";
                        echo "<td>" . $donneesEquipeClassement['equipe'] . "</td>";
                        echo "</tr>";
                        $i++;
                    }
                } else { //Tour final. Il faut aussi classer par le type de match.
                    /*$requeteC = "SELECT DISTINCT Championnat_Matchs.saison, Championnat_Matchs.idCategorie, Championnat_Matchs.idTour, Championnat_Matchs.noGroupe, Championnat_Equipes_Tours.idEquipe, idTypeMatch, points, goolaverage, nbMatchJoue, nbMatchGagne, nbMatchPerdu, nbMatchForfait, nbPointMarque, nbPointRecu, equipe
                    FROM Championnat_Equipes_Tours, Championnat_Matchs, Championnat_Equipes
                    WHERE Championnat_Equipes_Tours.saison =".$annee."
                    AND Championnat_Matchs.saison =".$annee."
                    AND Championnat_Equipes_Tours.idCategorie =".$idCategorie."
                    AND Championnat_Matchs.idCategorie =".$idCategorie."
                    AND Championnat_Equipes_Tours.idTour =".$idTour."
                    AND Championnat_Matchs.idTour =".$idTour."
                    AND Championnat_Equipes_Tours.noGroupe =".$noGroupe."
                    AND Championnat_Matchs.noGroupe =".$noGroupe."
                    AND (
                    equipeA = Championnat_Equipes_Tours.idEquipe
                    OR equipeB = Championnat_Equipes_Tours.idEquipe
                    )
                    AND Championnat_Equipes.idEquipe = Championnat_Equipes_Tours.idEquipe
                    ORDER BY Championnat_Matchs.idTypeMatch, nbMatchGagne DESC, Championnat_Equipes_Tours.points DESC , Championnat_Equipes_Tours.goolaverage DESC
                    LIMIT 0 , 30";*/
                    $requeteC = "SELECT DISTINCT cm.saison, cm.idCategorie, cm.idTour, cm.noGroupe, cet.idEquipe, idTypeMatch, points, goolaverage, nbMatchJoue, nbMatchGagne, nbMatchPerdu, nbMatchForfait, nbPointMarque, nbPointRecu, position, equipe
                             FROM Championnat_Equipes_Tours cet, Championnat_Matchs cm, Championnat_Equipes ce
                             WHERE cet.saison =" . $annee . "
                             AND cm.saison =" . $annee . "
                             AND cet.idCategorie =" . $idCategorie . "
                             AND cm.idCategorie =" . $idCategorie . "
                             AND cet.idTour =" . $idTour . "
                             AND cm.idTour =" . $idTour . "
                             AND cet.noGroupe =" . $noGroupe . "
                             AND cm.noGroupe =" . $noGroupe . "
                             AND (equipeA = cet.idEquipe
                                  OR equipeB = cet.idEquipe)
                             AND ce.idEquipe = cet.idEquipe
                             ORDER BY cm.idTypeMatch, nbMatchGagne DESC, cet.points DESC , cet.goolaverage DESC
                             LIMIT 0 , 30";
                    $retourC = mysql_query($requeteC);
                    $i = 1;
                    while ($donneesC = mysql_fetch_array($retourC)) {
                        $idTypeMatch = $donneesC['idTypeMatch'];
                        $nbMatchGagne = $donneesC['nbMatchGagne'];
                        $nbMatchPerdu = $donneesC['nbMatchPerdu'];
                        $nbMatchForfait = $donneesC['nbMatchForfait'];
                        $nbMatchJoue = $nbMatchPerdu + $nbMatchGagne + $nbMatchForfait;
                        $nbPointMarque = $donneesC['nbPointMarque'];
                        $nbPointRecu = $donneesC['nbPointRecu'];
                        $goolaverage = $donneesC['goolaverage'];
                        $position = $donneesC['position'];
                        $equipe = $donneesC['equipe'];
                        if ($i % 2 == 0) {
                            $style = "background-color:" . $couleurLigneA . ";";
                        } else {
                            $style = "background-color:" . $couleurLigneB . ";";
                        }
                        echo "<tr style='" . $style . "'>";
                        $nbMatchGagnant = $nbMatchGagnantTourFinal;
                        echo "<td class='homepage__championship-ranking__table__rank'>" . afficherRang($idTour, $typeClassement, $nbMatchGagne, $nbMatchPerdu,
                                $nbMatchGagnantPromoReleg, $nbMatchGagnantTourFinal, $position) . "</td>";
                        echo "</tr>";
                        echo "<td>" . $equipe . "</td>";

                        $i++;
                    }
                }
                echo "</table>";

                $idCategoriePrecedenteBoucle = $idCategorie;
                $idTourPrecedenteBoucle = $idTour;
                $noGroupePrecedenteBoucle = $noGroupe;
                $dernierClassement = count($tableau);
            }
        }

        ?>

    </div>
</div>