<?php

function triEgaliteParfaite($informations, $tableau, $debug)
{
    $nouveauTableau = array();
    $groupeEgalite = 0;
    $annee = $informations['annee'];
    $idCategorie = $informations['idCategorie'];
    $idTour = $informations['idTour'];
    $noGroupe = $informations['noGroupe'];

    for ($k = 1; $k <= $tableau[0]; $k++) {
        if (count($tableau[$k]) > 1) {
            if ($debug) {
                echo "<br /><strong>Il y a une �galit� de points marqu�s.</strong><br />";
            }

            $ordningEquipesEgalitesPoints = array();
            $ordningEquipesEgalitesId = array();
            $l = 0;

            $requeteNomsEquipes = "SELECT DISTINCT Championnat_Equipes.idEquipe, equipe, egaliteParfaite FROM Championnat_Equipes, Championnat_Equipes_Tours WHERE Championnat_Equipes.idEquipe=Championnat_Equipes_Tours.idEquipe AND saison=" . $annee . " AND idCategorie=" . $idCategorie . " AND idTour=" . $idTour . " AND noGroupe=" . $noGroupe . " AND (";

            for ($i = 1; $i <= count($tableau[$k]); $i++) { // Une boucle par �quipe � �galit� ==>> $i = EQUIPE EGALITE
                $requeteNomsEquipes .= "Championnat_Equipes.idEquipe=" . $tableau[$k][$i] . " ";
                if ($i != count($tableau[$k])) {
                    $requeteNomsEquipes .= "OR ";
                }
            } // Fin boucle par �quipe �galit�

            $requeteNomsEquipes .= ") ORDER BY egaliteParfaite, equipe";

            if ($debug) {
                echo "Tri par ordre alphab�tique (ou en fonction de la r�solution d'�galit� parfaite): " . $requeteNomsEquipes;
            }

            $retourNomsEquipes = mysql_query($requeteNomsEquipes);
            $nbEquipesEgaliteParfaite = 0;
            while ($donneesNomsEquipes = mysql_fetch_array($retourNomsEquipes)) { // Classement des �quipes � �galit� parfaite par ordre alphab�tique (ou en fonction de la r�solution d'�galit� parfaite).
                $groupeEgalite++;
                if ($donneesNomsEquipes['egaliteParfaite'] <= 1) {
                    $nbEquipesEgaliteParfaite++;
                }
                $nouveauTableau[$groupeEgalite][1] = $donneesNomsEquipes['idEquipe'];
                $nomEquipeEgaliteParfaite[$nbEquipesEgaliteParfaite] = $donneesNomsEquipes['equipe'];
                $idEquipeEgaliteParfaite[$nbEquipesEgaliteParfaite] = $donneesNomsEquipes['idEquipe'];
            }


            /* Requ�te pour v�rifier si le mail a d�j� �t� envoy� en allant voir dans la table Championnat_Equipes_Tours si le champ egaliteParfaite est � 1 */

            if ($nbEquipesEgaliteParfaite != 0) {
                $requeteVerificationDejaEnvoye = "SELECT DISTINCT egaliteParfaite FROM Championnat_Equipes_Tours WHERE saison=" . $annee . " AND idCategorie=" . $idCategorie . " AND idTour=" . $idTour . " AND noGroupe=" . $noGroupe . " AND (";
                for ($j = 1; $j <= $nbEquipesEgaliteParfaite; $j++) {
                    $requeteVerificationDejaEnvoye .= "idEquipe=" . $idEquipeEgaliteParfaite[$j];
                    if ($j != $nbEquipesEgaliteParfaite) {
                        $requeteVerificationDejaEnvoye .= " OR ";
                    }
                }
                $requeteVerificationDejaEnvoye .= ")";
                if ($debug) {
                    echo "<br />Requ�te pour v�rifier si le champ egaliteParfaite est d�j� � 1 : " . $requeteVerificationDejaEnvoye;
                }
                $retourVerificationDejaEnvoye = mysql_query($requeteVerificationDejaEnvoye);
                $donneesVerificationDejaEnvoye = mysql_fetch_array($retourVerificationDejaEnvoye);
                if ($debug) {
                    echo "<br /><br />egaliteParfaite : " . $donneesVerificationDejaEnvoye['egaliteParfaite'] . "<br /><br />";
                }
                $champEgaliteParfaite = $donneesVerificationDejaEnvoye['egaliteParfaite'];
            } else {
                $champEgaliteParfaite = 1;
            }
            if (mysql_num_rows($retourVerificationDejaEnvoye) > 1 OR $champEgaliteParfaite == '0') { //Si il y a plusieurs lignes ou que le champ egaliteParfaite est � 0, envoyer le mail.

                /* Envoi du mail */

                $boutMail = "";
                for ($j = 1; $j <= $nbEquipesEgaliteParfaite; $j++) {
                    $boutMail .= "<strong>" . $nomEquipeEgaliteParfaite[$j] . "</strong>";
                    if ($j != $nbEquipesEgaliteParfaite) {
                        $boutMail .= " et ";
                    }
                }
                $from = "From:no-reply@tchoukball.ch\n";
                $from .= "MIME-version: 1.0\n";
                $from .= "Content-type: text/html; charset= iso-8859-1\n";
                $destinataireMail = "resp.championnat@tchoukball.ch, webmaster@tchoukball.ch";
                mail($destinataireMail, "�galit� parfaite au championnat",
                    "Les �quipes " . $boutMail . " sont � �galit� parfaite au championnat.", $from);
                if ($debug) {
                    echo "<br /><br />Un mail a �t� envoy� � " . $destinataireMail . " vu qu'il y a une �galit� parfaite et qu'un tirage au sort doit �tre effectu�.</strong>";
                }
            }

            /* le champ egaliteParfaite dans la table Championnat_Equipes_Tours et �tabli � 1 pour diff�rencier les �quipes o� l'on doit r�gler une �galit� parfaite. */

            for ($j = 1; $j <= $nbEquipesEgaliteParfaite; $j++) {
                $requeteDifferenciation = "UPDATE Championnat_Equipes_Tours SET egaliteParfaite='1' WHERE saison=" . $annee . " AND idCategorie=" . $idCategorie . " AND idTour=" . $idTour . " AND noGroupe=" . $noGroupe . " AND idEquipe=" . $idEquipeEgaliteParfaite[$j];
                if ($debug) {
                    echo "<br />Mise en �vidence des �quipes � �galit� parfaite dans la base de donn�es : " . $requeteDifferenciation;
                }
                mysql_query($requeteDifferenciation);
            }
            if ($debug) {
                echo "<br />Mise du champ egaliteParfaite des �quipes PAS � �galit� � 0 : " . $requeteEquipesPasEgaliteParfaite;
            }

        } elseif (count($tableau[$k]) == 1) {
            $groupeEgalite++; // Nouveau groupe a �galit�
            $nouveauTableau[$groupeEgalite][1] = $tableau[$k][1];
        } else {
            echo "<br /><strong>ERREUR F1</strong><br />";
        }
    }
    $nouveauTableau[0] = $groupeEgalite;
    if ($pasChangerClassement) {
        $nouveauTableau = $tableau;
    }
    return $nouveauTableau;

}

?>
