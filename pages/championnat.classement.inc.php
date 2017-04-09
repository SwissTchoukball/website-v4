<form name="classementChampionnat" action="" method="post">
    <table border="0" align="center">
        <tr>
            <td><p><?php echo $agenda_annee; ?> :</p></td>
            <td><select name="annee" id="select" onChange="classementChampionnat.submit();">
                    <?php
                    $annee = $_POST['annee'];
                    // recherche de la premiere date
                    $requeteAnnee = "SELECT MIN( Agenda_Evenement.dateDebut ) FROM `Agenda_Evenement`";
                    $recordset = mysql_query($requeteAnnee) or die ("<H3>Aucune date existe</H3>");
                    $dateMin = mysql_fetch_array($recordset) or die ("<H3>ERREUR extraction</H3>");
                    $anneeMin = annee($dateMin[0]);
                    $anneeMinAffichee = $anneeMin - annee(date_actuelle());

                    // championnat de aout � aout => deux date de diff�rence => il y a deux ann�es.
                    $nbChampionnatExistant = -$anneeMinAffichee;

                    // si on est en aout, on peut afficher une option en plus pour le nouveau championnat
                    if (mois(date_actuelle()) > 7) {
                        $nbChampionnatExistant++;
                    }

                    $anneeDebutChampionnat = $anneeMin;
                    if ($annee == "") {
                        $annee = annee(date_actuelle());
                        if (mois(date_actuelle()) < 8) {
                            $annee--;
                        }
                    }

                    for ($i = 0; $i < $nbChampionnatExistant; $i++) {
                        if ($annee == $anneeDebutChampionnat) {
                            echo "<option selected value='" . $anneeDebutChampionnat . "'>" . VAR_LANG_CHAMPIONNAT . " " . $anneeDebutChampionnat . "-" . ($anneeDebutChampionnat + 1) . "</option>";
                        } else {
                            echo "<option value='" . $anneeDebutChampionnat . "'>" . VAR_LANG_CHAMPIONNAT . " " . $anneeDebutChampionnat . "-" . ($anneeDebutChampionnat + 1) . "</option>";
                        }
                        $anneeDebutChampionnat++;
                    }

                    ?></select></td>
        </tr>
    </table>
</form>

<?php


// Affichage classement

include('championnat.fonctions.tri.classement.A.inc.php');
include('championnat.fonctions.tri.classement.B.inc.php');
include('championnat.fonctions.tri.classement.C.inc.php');
include('championnat.fonctions.tri.classement.D.inc.php');
include('championnat.fonctions.tri.classement.E.inc.php');
include('championnat.fonctions.tri.classement.F.inc.php');


$debug = false; // isAdmin()

/* S�lection de la politique des points de l'ann�e choisie. */

$retourAnnee = mysql_query("SELECT * FROM Championnat_Saisons WHERE saison=" . $annee . "");
$donnees = mysql_fetch_array($retourAnnee);
$pointsMatchGagne = $donnees['pointsMatchGagne'];
$pointsMatchNul = $donnees['pointsMatchNul'];
$pointsMatchPerdu = $donnees['pointsMatchPerdu'];
$pointsMatchForfait = $donnees['pointsMatchForfait'];
$nbMatchGagnantTourFinal = $donnees['nbMatchGagnantTourFinal'];
$nbMatchGagnantPlayoff = $donnees['nbMatchGagnantPlayoff'];
$nbMatchGagnantPlayout = $donnees['nbMatchGagnantPlayout'];
$nbMatchGagnantPromoReleg = $donnees['nbMatchGagnantPromoReleg'];

/* Requ�te pour les noms de Cat�gorie et Tour afin de pas devoir les chercher � chaque fois avec une requ�te */

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


/* Requ�te pour afficher la liste des ligues */
$requete = "SELECT DISTINCT idCategorie FROM Championnat_Equipes_Tours WHERE saison=" . $annee . " ORDER BY idCategorie";
if ($debug) {
    echo "<br /><br />Liste ligues : " . $requete;
}
$retourListeLigues = mysql_query($requete);
echo "<ul class='listeLigues'>";
//boucle pour afficher la liste des ligues en haut de page
while ($ligue = mysql_fetch_array($retourListeLigues)) {
    $idCategorie = $ligue['idCategorie'];
    echo "<li><a href='#" . slugify($nomCategorie[$idCategorie]) . "'>" . $nomCategorie[$idCategorie] . "</a></li>";
}
echo "</ul>";


/* Requ�te d�finissant les de classements � faire et leur type puis execution d'une boucle pour chaque classement */
$requete = "SELECT DISTINCT idCategorie, idTour, noGroupe FROM Championnat_Equipes_Tours WHERE saison=" . $annee . " ORDER BY idCategorie, idTour DESC";
if ($debug) {
    echo "<br /><br />Tours : " . $requete;
}
$retourNbClassement = mysql_query($requete);
while ($donneesNbClassement = mysql_fetch_array($retourNbClassement)) {
    $idCategorie = $donneesNbClassement['idCategorie'];
    $idTour = $donneesNbClassement['idTour'];
    $noGroupe = $donneesNbClassement['noGroupe'];
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
    while ($donneesTriPoints = mysql_fetch_array($retourTriPoints)) { // V�rification du nombre de points pour chaque �quipe
        if ($a == 0 OR $donneesTriPoints['points'] != $points) { // SI premi�re �quipe OU �quipe pr�c�dante n'a pas le m�me nombre de points
            $a++; // Nouveau groupe de tri par points
            $b = 1; // R�initialisation du num�ro d'�quipe du groupe
            $points = $donneesTriPoints['points']; // Nombre de point qu'a le groupe de tri par points
        }
        $groupeTriPoints[$a][$b] = $donneesTriPoints['idEquipe']; //ID d'�quipe plac� dans un groupe de tri par points
        $b++; //Nouveau num�ro d'�quipe
    } // Fin boucle Tri Points
    $groupeTriPoints[0] = $a; //la position 0 du tableau �tant libre, on peut lui donner le nombre de groupes a �galit�s pour la fonction.
    if ($debug) {
        echo "<br /><strong>Tableau de groupement des �galit�s de points :</strong><br />";
        print_array($groupeTriPoints);
        echo "<br />";
        $etatMemoire = memory_get_usage();
        echo "memory_get_usage : " . $etatMemoire;
        echo "<br />";
    }


    $tableau = triParPointsInterne($informations, $groupeTriPoints, $debug);
    if ($debug) {
        echo "<br /><strong>Tableau de groupement des �galit�s de points interne :</strong><br />";
        print_array($tableau);
        echo "<br />";
        $etatMemoire = memory_get_usage();
        echo "memory_get_usage : " . $etatMemoire;
        echo "<br />";
    }

    $tableau = triDifferenceScorePointsInterne($informations, $tableau, $debug);
    if ($debug) {
        echo "<br /><strong>Tableau de groupement des �galit�s de diff�rence de points(score) interne :</strong><br />";
        print_array($tableau);
        echo "<br />";
        $etatMemoire = memory_get_usage();
        echo "memory_get_usage : " . $etatMemoire;
        echo "<br />";
    }
    $tableau = triScorePointsMarquesInterne($informations, $tableau, $debug);
    if ($debug) {
        echo "<br /><strong>Tableau de groupement des �galit�s de points marqu�s interne :</strong><br />";
        print_array($tableau);
        echo "<br />";
        $etatMemoire = memory_get_usage();
        echo "memory_get_usage : " . $etatMemoire;
        echo "<br />";
    }
    $tableau = triDifferenceScorePoints($informations, $tableau, $debug);
    if ($debug) {
        echo "<br /><strong>Tableau de groupement des �galit�s de goalaverage :</strong><br />";
        print_array($tableau);
        echo "<br />";
        $etatMemoire = memory_get_usage();
        echo "memory_get_usage : " . $etatMemoire;
        echo "<br />";
    }
    $tableau = triScorePointsMarques($informations, $tableau, $debug);
    if ($debug) {
        echo "<br /><strong>Tableau de groupement des points marques :</strong><br />";
        print_array($tableau);
        echo "<br />";
        $etatMemoire = memory_get_usage();
        echo "memory_get_usage : " . $etatMemoire;
        echo "<br />";
    }
    $tableau = triEgaliteParfaite($informations, $tableau, $debug);
    if ($debug) {
        echo "<br /><strong>Tableau de groupement des �galit�s parfaite :</strong><br />";
        print_array($tableau);
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
        echo "<h2 id='" . slugify($nomCategorie[$idCategorie]) . "' class='alt'>" . $nomCategorie[$idCategorie] . "</h2>";
    }
    if ((!isset($idTourPrecedenteBoucle) OR $idTourPrecedenteBoucle != $idTour) AND $idTour != 2000) {
        $noGroupePrecedenteBoucle = 0;
        echo "<h3>" . $nomTour[$idTour] . "</h3>";
    }
    if ((!isset($noGroupePrecedenteBoucle) OR $noGroupePrecedenteBoucle != $noGroupe) AND $noGroupe != 0) {
        echo "<h4>" . VAR_LANG_GROUPE . " " . $noGroupe . "</h4>";
    }

    if ($typeClassement == 1 OR $typeClassement == 3) {
        echo "<table class='classementTour'>";
        ?>
        <tr>
            <th>
                <?php
                if ($idTour == 2000 AND $typeClassement == 1) {
                    echo "Promu";
                } else {
                    echo "Position";
                }
                ?>
            </th>
            <th>Equipes</th>
            <th>Jou�</th>
            <th>Gagn�</th>
            <?php if ($typeClassement == 3) { ?>
                <th>Nul</th> <?php } ?>
            <th>Perdu</th>
            <th>Forfait</th>
            <th>Marqu�</th>
            <th>Re�u</th>
            <th>Diff.</th>
            <?php if ($typeClassement == 3) { ?>
                <th>Points</th> <?php } ?>
        </tr>
        <?php
    } elseif ($typeClassement == 2) {
        echo "<table class='classementTourFinal'>";
        ?>
        <tr>
            <th>
                <?php
                if ($idTour == 2000) {
                    echo "Promu";
                } else {
                    echo "Position";
                }
                ?>
            </th>
            <th>Equipes</th>
        </tr>
        <?php
    }


    if ($idTour != 10000) { // Pas un tour final
        for ($k = 1; $k < count($tableau); $k++) {
            $idEquipe = $tableau[$k][1];
            $requete = "SELECT equipe, nbMatchJoue, nbMatchGagne, nbMatchNul, nbMatchPerdu, nbMatchForfait, nbPointMarque, nbPointRecu, goolaverage, points FROM Championnat_Equipes, Championnat_Equipes_Tours WHERE Championnat_Equipes.idEquipe=" . $idEquipe . " AND Championnat_Equipes_Tours.idEquipe=Championnat_Equipes.idEquipe AND saison=" . $annee . " AND idCategorie=" . $idCategorie . " AND idTour=" . $idTour . " AND noGroupe=" . $noGroupe . "";
            if ($debug) {
                echo "<br /><br />Affichage des �quipes qui ne sont pas � �galit� : " . $requete;
            }
            $retourEquipeClassement = mysql_query($requete);
            $donneesEquipeClassement = mysql_fetch_array($retourEquipeClassement);
            echo "<tr>";
            //echo "<tr>";
            if (isset($idTourPrecedenteBoucle) AND $idTourPrecedenteBoucle == $idTour AND $noGroupePrecedenteBoucle != $noGroupe) { //On ne doit pas remettre le classement � z�ro !
                $classement = $k + $dernierClassement - 1;
            } else {
                $classement = $k;
            }
            echo "<td>" . afficherRang($idTour, $typeClassement,
                    $donneesEquipeClassement['nbMatchGagne'], $donneesEquipeClassement['nbMatchPerdu'],
                    $nbMatchGagnantPromoReleg, $nbMatchGagnantTourFinal, $classement) . "</td>";
            echo "<td>" . $donneesEquipeClassement['equipe'] . "</td>";
            if ($typeClassement != 2) {
                $nbMatchJoue = $donneesEquipeClassement['nbMatchGagne'] + $donneesEquipeClassement['nbMatchNul'] + $donneesEquipeClassement['nbMatchPerdu'] + $donneesEquipeClassement['nbMatchForfait'];
                echo "<td>" . $nbMatchJoue . "</td>";
                echo "<td>" . $donneesEquipeClassement['nbMatchGagne'] . "</td>";
                if ($typeClassement == 3) {
                    echo "<td>" . $donneesEquipeClassement['nbMatchNul'] . "</td>";
                }
                echo "<td>" . $donneesEquipeClassement['nbMatchPerdu'] . "</td>";
                echo "<td>" . $donneesEquipeClassement['nbMatchForfait'] . "</td>";
                echo "<td>" . $donneesEquipeClassement['nbPointMarque'] . "</td>";
                echo "<td>" . $donneesEquipeClassement['nbPointRecu'] . "</td>";
                echo "<td>" . $donneesEquipeClassement['goolaverage'] . "</td>";
                if ($typeClassement == 3) {
                    echo "<td>" . $donneesEquipeClassement['points'] . "</td>";
                }
            }
            echo "</tr>";
        }
    } else { //Tour final. Il faut aussi classer par le type de match.
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
        if ($debug) {
            echo $requeteC;
        }
        $idTypeMatchPrecedant = null;
        $retourC = mysql_query($requeteC);
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
            echo "<tr>";
            $nbMatchGagnant = $nbMatchGagnantTourFinal;
            if ($typeClassement != 2) {
                if ($idTypeMatch != $idTypeMatchPrecedant) {
                    $rang = ($idTypeMatch * 2) - 1;
                } else {
                    $rang = $idTypeMatch * 2;
                }
            } else {
                $rang = $position;
            }
            echo "<td><p align='center'>" . afficherRang($idTour, $typeClassement, $nbMatchGagne, $nbMatchPerdu,
                    $nbMatchGagnantPromoReleg, $nbMatchGagnantTourFinal, $rang) . "</p></td>";
            echo "<td><p>" . $equipe . "</p></td>";
            if ($typeClassement != 2) {
                echo "<td><p>" . $nbMatchJoue . "</p></td>";
                echo "<td><p>" . $nbMatchGagne . "</p></td>";
                echo "<td><p>" . $nbMatchPerdu . "</p></td>";
                echo "<td><p>" . $nbMatchForfait . "</p></td>";
                echo "<td><p>" . $nbPointMarque . "</p></td>";
                echo "<td><p>" . $nbPointRecu . "</p></td>";
                echo "<td><p>" . $goolaverage . "</p></td>";
            }
            echo "</tr>";
            $idTypeMatchPrecedant = $idTypeMatch;
        }
    }
    echo "</table><br />";

    $idCategoriePrecedenteBoucle = $idCategorie;
    $idTourPrecedenteBoucle = $idTour;
    $noGroupePrecedenteBoucle = $noGroupe;
    $dernierClassement = count($tableau);
}
?>
