<?php
// test la chronologie entre deux date : dateDebut < dateFin
function dateDebutFinValide(
    $debutAnnee,
    $debutMois,
    $debutJour,
    $debutHeure,
    $debutMinute,
    $finAnnee,
    $finMois,
    $finJour,
    $finHeure,
    $finMinute
) {

    $dateDebut = "$debutAnnee-$debutMois-$debutJour";
    $dateFin = "$finAnnee-$finMois-$finJour";
    $heureDebut = "$debutHeure:$debutMinute:00";
    $heureFin = "$finHeure:$finMinute:00";

    $nbErreur = 0;

    // test de la validit� de la date de d�but
    if (!checkdate($debutMois, $debutJour, $debutAnnee)) {
        echo "<p class='titresectiontext' align='center'>date incorrecte : debut = $debutJour.$debutMois.$debutAnnee</p>";
        $nbErreur++;
    }

    // test de la validit� de la date de fin
    if (!checkdate($finMois, $finJour, $finAnnee)) {
        echo "<p class='titresectiontext' align='center'>date incorrecte : fin = $finJour.$finMois.$finAnnee</p>";
        $nbErreur++;
    }

    // teste de la chronologie des dates
    if (date1_sup_date2($dateDebut, $dateFin)) {
        echo "<p class='titresectiontext' align='center'>Chronologie des dates non respect�e : debut = $debutJour.$debutMois.$debutAnnee, fin = $finJour.$finMois.$finAnnee</p>";
        $nbErreur++;
    }

    // test si les heures sont dans un ordre chronologique
    if (($debutHeure > $finHeure) ||
        ($debutHeure == $finHeure && $debutMinute > $finMinute)
    ) {
        echo "<p class='titresectiontext' align='center'>Chronologie des heures non respect�e : debut = $heureDebut, fin = $heureFin</p>";
        $nbErreur++;
    }

    return $nbErreur == 0;
}

//�inclDate = "isset";
///////////////////////////////////////////////////////////////////////////////
//Nom: date_sql2MonthYear                                                    //
//But: transforme la date format sql en date normale (janvier-2004           //
//Date: 06.05.2005                                                           //
//Cr�e par: Romain                                                           //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function date_sql2MonthYear($date, $monthArray)
{
    $annee = substr($date, 0, 4);
    $mois = $monthArray[substr($date, 5, 2) - 1];
    return $mois . " " . $annee;
}

//�inclDate = "isset";
///////////////////////////////////////////////////////////////////////////////
//Nom: date_sql2date                                                         //
//But: transforme la date format sql en date normale (jj-mm-aaaa)            //
//Date: 17.10.2001                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function date_sql2date($date)
{
    $annee = substr($date, 0, 4);
    $mois = substr($date, 5, 2);
    $jour = substr($date, 8, 2);
    $date = $jour . "." . $mois . "." . $annee;
    return $date;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: date_date2sql                                                         //
//But: transforme la date format normale (jj-mm-aaaa) en format SQL          //
//Date: 17.10.2001                                                           //
//Cr�e par: Schmocker Romain                                                 //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function date_date2sql($date)
{
    $annee = substr($date, 6, 4);
    $mois = substr($date, 3, 2);
    $jour = substr($date, 0, 2);
    $date = "$annee-$mois-$jour";
    //echo "date = $date";
    return $date;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: jour, mois, annee                                                     //
//But: fonctions qui retournent le jour, le mois et l'ann�e  de la date au   //
//     format sql (aaaa-mm-jj) pass�e en param                               //
//Date: 15.10.2003                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function jour($date)
{
    return substr($date, 8, 2);
}

function mois($date)
{
    return substr($date, 5, 2);
}

function annee($date)
{
    return substr($date, 0, 4);
}

///////////////////////////////////////////////////////////////////////////////
//Nom: heure, minute, seconde                                                //
//But: fonctions qui retournent l'heure, la minute et les secondes de la date//
//     au format sql (hh:mm:ss) pass�e en param                              //
//Date: 15.10.2003                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function heure($heure)
{
    return substr($heure, 0, 2);
}

function minute($minute)
{
    return substr($minute, 3, 2);
}

function seconde($seconde)
{
    return substr($seconde, 6, 2);
}


///////////////////////////////////////////////////////////////////////////////
//Nom: date_actuelle                                                         //
//But: retourne la date d'aujourd'hui au format sql (aaaa-mm-jj)             //
//Date: 17.10.2001                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function date_actuelle()
{
    $jour = date('d');
    if ($jour < 10) {
        $jour = "$jour";
    }
    $mois = date('m');
    $annee = date('Y');
    $date_du_jour = "$annee-$mois-$jour";
    return $date_du_jour;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: date_egale                                                            //
//But: retourne 1 si les dates sont identiques 0 sinon                       //
//Param�tres: les 2 dates (format yyyy-mm-jj) � comparer                     //
//Date: 26.03.2003                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function date_egales($date1, $date2)
{
    $annee1 = substr($date1, 0, 4);
    $mois1 = substr($date1, 5, 2);
    $jour1 = substr($date1, 8, 2);

    $annee2 = substr($date2, 0, 4);
    $mois2 = substr($date2, 5, 2);
    $jour2 = substr($date2, 8, 2);

    if ($jour1 == $jour2 && $mois1 == $mois2 && $annee1 == $annee2) {
        return 1;
    } else {
        return 0;
    }
}

///////////////////////////////////////////////////////////////////////////////
//Nom: date1_inf_date2                                                       //
//But: retourne 1 si la date 1 et strictement ant�rieure � la date 2, 0 sinon//
//Param�tres: les 2 dates (format yyyy-mm-jj) � comparer                     //
//Date: 26.03.2003                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function date1_inf_date2($date1, $date2)
{
    $annee1 = substr($date1, 0, 4);
    $mois1 = substr($date1, 5, 2);
    $jour1 = substr($date1, 8, 2);

    $annee2 = substr($date2, 0, 4);
    $mois2 = substr($date2, 5, 2);
    $jour2 = substr($date2, 8, 2);

    if ($annee1 < $annee2) {
        return 1;
    }
    if ($annee1 == $annee2 && $mois1 < $mois2) {
        return 1;
    }
    if ($annee1 == $annee2 && $mois1 == $mois2 && $jour1 < $jour2) {
        return 1;
    } else {
        return 0;
    }
}

///////////////////////////////////////////////////////////////////////////////
//Nom: date1_sup_date2                                                       //
//But: retourne 1 si la date 1 et strictement ant�rieure � la date 2, 0 sinon//
//Param�tres: les 2 dates (format yyyy-mm-jj) � comparer                     //
//Date: 26.03.2003                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function date1_sup_date2($date1, $date2)
{
    $annee1 = substr($date1, 0, 4);
    $mois1 = substr($date1, 5, 2);
    $jour1 = substr($date1, 8, 2);

    $annee2 = substr($date2, 0, 4);
    $mois2 = substr($date2, 5, 2);
    $jour2 = substr($date2, 8, 2);

    if ($annee1 > $annee2) {
        return 1;
    }
    if ($annee1 == $annee2 && $mois1 > $mois2) {
        return 1;
    }
    if ($annee1 == $annee2 && $mois1 == $mois2 && $jour1 > $jour2) {
        return 1;
    } else {
        return 0;
    }
}

///////////////////////////////////////////////////////////////////////////////
//Nom: dateAvantAujourdhui                                                   //
//But: retourne true si la date et strictement ant�rieure � la date          //
//     d'aujourd'hui, flase sinon                                            //
//Param�tres: la date (format yyyy-mm-jj) � comparer                         //
//Date: 12.02.2014                                                           //
//Cr�e par: Sandoz David                                                     //
//Remarques: utilise la fonction date1_inf_date2                             //
///////////////////////////////////////////////////////////////////////////////
function dateAvantAujourdhui($date)
{
    return date1_inf_date2($date, date('Y-m-d')) == 1;
}

function dateApresAujourdhui($date)
{
    return date1_sup_date2($date, date('Y-m-d')) == 1;
}

function periodeContientAujourdhui($dateDebut, $dateFin)
{
    return dateAvantAujourdhui($dateDebut) && dateApresAujourdhui($dateFin);
}

///////////////////////////////////////////////////////////////////////////////
//Nom: creation_liste_jour                                                   //
//But: cr�ee la liste d'option pour les 31 jours                             //
//Date: 17.10.2001                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function creation_liste_jour()
{
    $option = "";
    for ($i = 1; $i <= 31; $i++) {
        if ($i < 10) {
            $option .= "<option value='0$i'>0$i</option>\n";
        }
        if ($i >= 10) {
            $option .= "<option value='$i'>$i</option>\n";
        }
    }
    return $option;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: modif_liste_jours                                                     //
//But: cr�ee la liste d'option pour les jours avec le jours pass� en         //
//     param�tre selectionn�                                                 //
//Date: 17.10.2001                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function modif_liste_jour($jour)
{
    $option = "";
    for ($i = 1; $i <= 31; $i++) {
        if ($i < 10) {
            if ($i == $jour) {
                $option .= "<option value='0$i' selected>0$i</option>\n";
            } else {
                $option .= "<option value='0$i'>0$i</option>\n";
            }
        }
        if ($i >= 10) {
            if ($i == $jour) {
                $option .= "<option value='$i' selected>$i</option>\n";
            } else {
                $option .= "<option value='$i'>$i</option>\n";
            }
        }
    }
    return $option;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: creation_liste_mois                                                   //
//But: cr�ee la liste d'option pour les 12 mois                              //
//Date: 17.10.2001                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function creation_liste_mois()
{
    $option = "";
    for ($i = 1; $i <= 12; $i++) {
        if ($i < 10) {
            $option .= "<option value='0$i'>0$i</option>\n";
        }
        if ($i >= 10) {
            $option .= "<option value='$i'>$i</option>\n";
        }
    }
    return $option;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: modif_liste_mois                                                      //
//But: cr�ee la liste d'option pour les mois avec le mois pass� en          //
//     param�tre selectionn�                                                 //
//Date: 17.10.2001                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function modif_liste_mois($mois)
{
    $option = "";
    for ($i = 1; $i <= 12; $i++) {
        if ($i < 10) {
            if ($i == $mois) {
                $option .= "<option value='0$i' selected>0$i</option>\n";
            } else {
                $option .= "<option value='0$i'>0$i</option>\n";
            }
        } else {
            if ($i == $mois) {
                $option .= "<option value='$i' selected>$i</option>\n";
            } else {
                $option .= "<option value='$i'>$i</option>\n";
            }
        }
    }
    return $option;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: creation_liste_annee                                                  //
//But: cr�ee la liste d'option pour 5 annees depuis l'annee courante         //
//Param�tres: -debut: premi�re ann�e de la liste en fonction de l'ann�e      //
//                 courante. Exemple: Si ann�e courante=2003 et que l'on veux    //
//                  commencer en 2005 il faut mettre 2. -2 pour 2001              //
//                -fin: mem chose que pour le debut mais pour la fin             //
//Date: 02.10.2003                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function creation_liste_annee($debut, $fin)
{
    $option = "";
    for ($i = date('Y') + $debut; $i <= date('Y') + $fin; $i++) {
        $option .= "<option value='$i'>$i</option>\n";
    }
    return $option;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: modif_liste_annees                                                    //
//But: cr�ee la liste d'option pour les annees avec l'annee pass�e en        //
//     param�tre selectionn�e                                                //
//Date: 17.10.2001                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function modif_liste_annee($debut, $fin, $annee)
{
    $option = "";
    for ($i = date('Y') + $debut; $i <= date('Y') + $fin; $i++) {
        if ($i == $annee) {
            $option .= "<option value='$i' selected='selected'>$i</option>\n";
        } else {
            $option .= "<option value='$i'>$i</option>\n";
        }
    }
    return $option;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: creation_liste_heure                                                  //
//But: cr�ee la liste d'option pour les 24 heures                            //
//Date: 03.10.2003                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function creation_liste_heure()
{
    $option = "";
    for ($i = 0; $i < 24; $i++) {
        if ($i < 10) {
            $option .= "<option value='0$i'>0$i</option>\n";
        }
        if ($i >= 10) {
            $option .= "<option value='$i'>$i</option>\n";
        }
    }
    return $option;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: modif_liste_heure                                                     //
//But: cr�ee la liste d'option pour les 24 heures avec l'heure pass�e en     //
//     param�tre s�l�ctionn�e                                                //
//Date: 15.10.2003                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function modif_liste_heure($heure)
{
    $option = "";
    for ($i = 0; $i < 24; $i++) {
        if ($i == $heure) {
            if ($i < 10) {
                $option .= "<option value='0$i' selected>0$i</option>\n";
            }
            if ($i >= 10) {
                $option .= "<option value='$i' selected>$i</option>\n";
            }
        } else {
            if ($i < 10) {
                $option .= "<option value='0$i'>0$i</option>\n";
            }
            if ($i >= 10) {
                $option .= "<option value='$i'>$i</option>\n";
            }
        }
    }
    return $option;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: creation_liste_minute                                                 //
//But: cr�ee la liste d'option pour les 60 minutes                           //
//Date: 03.10.2003                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function creation_liste_minute()
{
    $option = "";
    for ($i = 0; $i <= 59; $i++) {
        if ($i < 10) {
            $option .= "<option value='0$i'>0$i</option>\n";
        }
        if ($i >= 10) {
            $option .= "<option value='$i'>$i</option>\n";
        }
    }
    return $option;
}

///////////////////////////////////////////////////////////////////////////////
//Nom: modif_liste_minute                                                    //
//But: cr�ee la liste d'option pour les 60 minutes avec la minute pass�e en  //
//     param�tre s�l�ctionn�e                                                //
//Date: 15.10.2003                                                           //
//Cr�e par: Poffet Ga�l                                                      //
//Remarques:                                                                 //
///////////////////////////////////////////////////////////////////////////////
function modif_liste_minute($minute)
{
    $option = "";
    for ($i = 0; $i <= 59; $i++) {
        if ($i == $minute) {
            if ($i < 10) {
                $option .= "<option value='0$i' selected>0$i</option>\n";
            }
            if ($i >= 10) {
                $option .= "<option value='$i' selected>$i</option>\n";
            }
        } else {
            if ($i < 10) {
                $option .= "<option value='0$i'>0$i</option>\n";
            }
            if ($i >= 10) {
                $option .= "<option value='$i'>$i</option>\n";
            }
        }
    }
    return $option;
}


function date_sql2date_joli($date, $prefixe, $langue, $dayOfWeek = true)
{
    $annee = substr($date, 0, 4);
    $mois = substr($date, 5, 2);
    $jour = substr($date, 8, 2);
    $timestamp = mktime(0, 0, 0, $mois, $jour, $annee);
    $jourSemaineNumero = date('w', $timestamp);
    $dateJoli = '';

    $dateEcrite = false;
    if ($langue == "Fr") {
        if ($annee == date('Y')) {
            if ($mois == date('m')) {
                if ($jour == date('d') - 1) {
                    $dateJoli = "hier";
                    $dateEcrite = true;
                } elseif ($jour == date('d')) {
                    $dateJoli = "aujourd'hui";
                    $dateEcrite = true;
                } elseif ($jour == date('d') + 1) {
                    $dateJoli = "demain";
                    $dateEcrite = true;
                }
            }
        }

        if ($dateEcrite) {
            if ($prefixe == "du") {
                $dateJoli = "de " . $date;
            } elseif ($prefixe == "au") {
                $dateJoli = "� " . $date;
            } elseif ($prefixe == "le") {
                //nothing
            }
        } else {
            if ($mois == 1) {
                $mois = "janvier";
            } elseif ($mois == 2) {
                $mois = "f�vrier";
            } elseif ($mois == 3) {
                $mois = "mars";
            } elseif ($mois == 4) {
                $mois = "avril";
            } elseif ($mois == 5) {
                $mois = "mai";
            } elseif ($mois == 6) {
                $mois = "juin";
            } elseif ($mois == 7) {
                $mois = "juillet";
            } elseif ($mois == 8) {
                $mois = "ao�t";
            } elseif ($mois == 9) {
                $mois = "septembre";
            } elseif ($mois == 10) {
                $mois = "octobre";
            } elseif ($mois == 11) {
                $mois = "novembre";
            } elseif ($mois == 12) {
                $mois = "d�cembre";
            }

            $jourSemaine = '';
            if ($jourSemaineNumero == 0) {
                $jourSemaine = "dimanche";
            } elseif ($jourSemaineNumero == 1) {
                $jourSemaine = "lundi";
            } elseif ($jourSemaineNumero == 2) {
                $jourSemaine = "mardi";
            } elseif ($jourSemaineNumero == 3) {
                $jourSemaine = "mercredi";
            } elseif ($jourSemaineNumero == 4) {
                $jourSemaine = "jeudi";
            } elseif ($jourSemaineNumero == 5) {
                $jourSemaine = "vendredi";
            } elseif ($jourSemaineNumero == 6) {
                $jourSemaine = "samedi";
            }

            $jour = ltrim($jour, '0'); // Removes leading zeros

            $dateJoli = '';

            if ($prefixe != "") {
                $dateJoli .= $prefixe . ' ';
            }

            if ($dayOfWeek) {
                $dateJoli .= $jourSemaine . ' ';
            }

            $dateJoli .= $jour;

            if ($jour == 1) {
                $dateJoli .= '<sup>er</sup>';
            }

            $dateJoli .= '&nbsp;' . $mois . '&nbsp;' . $annee;
        }
    } else {
        if ($prefixe != "") {
            $dateJoli .= $prefixe . ' ';
        }

        $dateJoli .= date_sql2date($date);
    }
    return $dateJoli;
}

function time_sql2heure($time)
{
    $heure = substr($time, 0, 2);
    $minute = substr($time, 3, 2);
    $heureTotal = $heure . ":" . $minute;
    return $heureTotal;
}

function commenceAvecVoyelle($mot)
{
    if (substr($mot, 0, 1) == 'a' || substr($mot, 0, 1) == 'A'
        || substr($mot, 0, 1) == 'e' || substr($mot, 0, 1) == 'E'
        || substr($mot, 0, 1) == 'i' || substr($mot, 0, 1) == 'I'
        || substr($mot, 0, 1) == 'o' || substr($mot, 0, 1) == 'O'
        || substr($mot, 0, 1) == 'u' || substr($mot, 0, 1) == 'U'
        || substr($mot, 0, 1) == 'y' || substr($mot, 0, 1) == 'Y'
    ) {
        return true;
    } else {
        return false;
    }
}
