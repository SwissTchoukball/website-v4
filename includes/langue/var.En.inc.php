<?php
// fichier de variables en langue "fr"

//inclure le fichier generer par la bd.
// include "generationFichier/var.bd.En.inc.php";

if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}

define("VAR_LANG_ASSOCIATION_NAME", "Swiss Tchoukball");
define("VAR_LANG_ASSOCIATION_NAME_ARTICLE", VAR_LANG_ASSOCIATION_NAME);
define("VAR_LANG_EN_CONSTRUCTION", "In construction...");

define("VAR_LANG_NO_JAVA_SCRIPT_MAIL", "JavaScript must be enabled to display this email address.");

$VAR_G_JOURS_SEMAINE = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
$VAR_G_MOIS = array(
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December"
);

/* JOURS DE LA SEMAINE avec le 0 vide */

$jourDeLaSemaine = array();

$jourDeLaSemaine[1] = 'Monday';
$jourDeLaSemaine[2] = 'Tuesday';
$jourDeLaSemaine[3] = 'Wednesday';
$jourDeLaSemaine[4] = 'Thursday';
$jourDeLaSemaine[5] = 'Friday';
$jourDeLaSemaine[6] = 'Saturday';
$jourDeLaSemaine[7] = 'Sunday';

/* MOIS DE L'ANNEE avec le 0 vide  */

$moisDeLAnnee = array();

$moisDeLAnnee[1] = 'January';
$moisDeLAnnee[2] = 'February';
$moisDeLAnnee[3] = 'March';
$moisDeLAnnee[4] = 'April';
$moisDeLAnnee[5] = 'May';
$moisDeLAnnee[6] = 'June';
$moisDeLAnnee[7] = 'July';
$moisDeLAnnee[8] = 'August';
$moisDeLAnnee[9] = 'September';
$moisDeLAnnee[10] = 'October';
$moisDeLAnnee[11] = 'November';
$moisDeLAnnee[12] = 'December';

function sufixDate($dateJourMoisNum)
{
    if ($dateJourMoisNum == 1) {
        return "st";
    } else if ($dateJourMoisNum == 2) {
        return "nd";
    } else if ($dateJourMoisNum == 3) {
        return "rd";
    } else {
        return "th";
    }
}

// exemple de date : Monday, 17th September 2004
function afficherDateComplete()
{
    global $VAR_G_JOURS_SEMAINE;
    global $VAR_G_MOIS;

    $aujourdhui = getdate();
    echo $VAR_G_JOURS_SEMAINE[$aujourdhui["wday"]] . ", " . $aujourdhui["mday"] . sufixDate($aujourdhui["mday"]) . " " . $VAR_G_MOIS[$aujourdhui["mon"] - 1] . " " . $aujourdhui["year"];
}

define("VAR_LANG_INSERER", "Insert");
define("VAR_LANG_MODIFIER", "Edit");
define("VAR_LANG_SUPPRIMER", "Delete");
define("VAR_LANG_APPLIQUER", "Apply");
define("VAR_LANG_ETAPE_SUIVANTE", "Next Step");
define("VAR_LANG_ETAPE_1", "Step 1");
define("VAR_LANG_ETAPE_2", "Step 2");
define("VAR_LANG_ETAPE_3", "Step 3");
define("VAR_LANG_ETAPE_4", "Step 4");
define("VAR_LANG_ETAPE_5", "Step 5");
define("VAR_LANG_ANNULER", "Cancel");
define("VAR_LANG_ATTENTION", "Warning");
define("VAR_LANG_RETOUR", "Back");
define("VAR_LANG_ACCUEIL", "Home");
define("VAR_LANG_INTROUVABLE", "Missing");
define("VAR_LANG_PLUS_INFOS", "Plus d'infos");
define("VAR_LANG_LEGENDE", "Key");
define("VAR_LANG_RESPONSABLE", "Person in charge");
define("VAR_LANG_RESPONSABLES", "People in charge");
define("VAR_LANG_COMPETITIONS", "Competitions");
define("VAR_LANG_MONTANT", "Amount");

// AGENDA
$agenda_annee = "Year";
$agenda_categories = "Categories";
$agenda_actualiser = "Refresh";
$agenda_le = "the";
$agenda_du = "from";
$agenda_au = "to";
$agenda_de = "from";
$agenda_a = "to";
$agenda_type = "Type";
$agenda_description = "Description";
$agenda_lieu = "Place";
$agenda_debut = "Start";
$agenda_fin = "End";
$agenda_date = "Date";
$agenda_heure = "Hours";
$agenda_a_venir = "Comming";
$agenda_evenement = "Event";
$agenda_toutes = "All";
$agenda_vacances = "Holiday";
define("VAR_LANG_DESCRIPTION", "Description");
define("VAR_LANG_AUCUN_EVENEMENT_A_VENIR", "No coming event in this category.");
define("VAR_LANG_AUCUNE_VACANCE_PENDANT_EVENEMENT", "No holiday during this event.");

//comite
define("VAR_LANG_DOMAINES_RESPONSABILITE", "Responsabilies");
define("VAR_LANG_DOMAINE_RESPONSABILITE", "Responsability");

//clubs
define("VAR_LANG_NB_CLUBS", "There are %d clubs members to " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ".");

// Telechargement
define("VAR_LANG_DOC_NOM_LIEN_FLYER_PRESENTATION", "Download the flyer"); // nom lien flyer presentation
define("VAR_LANG_DOC_FLYER_PRESENTATION", "document/En_flyer_presentation.pdf"); // flyer presentation

// download
define("DOWNLOAD_ENTETE_DESCRIPTION", "Description du fichier");
define("VAR_LANG_TELECHARGER", "Download");
define("VAR_LANG_TOUT", "All");
//define("VAR_LANG_PRESSE","Press");

// news
define("VAR_LANG_NEWS_NON_TROUVEE", "News non-existent");
define("VAR_LANG_NEWS_SUIVANTES", "Next news");
define("VAR_LANG_NEWS_PRECEDENTES", "Previous news");
define("VAR_LANG_NEWS_BACK_TO_NEWS", "Back to news");
define("VAR_LANG_LIRE_SUITE_ARTICLE", "show the full new");

// Championnat
define("VAR_LANG_CHAMPIONNAT", "Championship");
define("VAR_LANG_CHAMPIONNAT_SUISSE", "Swiss Championship");
define("VAR_LANG_RENCONTRES_A_VENIR", "Comming games");
define("VAR_LANG_LIGUE", "League");
define("VAR_LANG_TOUR", "Tour");
define("VAR_LANG_TOUR_FINAL", "Final tour");
define("VAR_LANG_TOUR_PRECEDENT", "Previous tour");
define("VAR_LANG_GROUPE", "Group");
define("VAR_LANG_JOURNEE", "Day");
define("VAR_LANG_ACTE", "Act");
define("VAR_LANG_TYPE_MATCH", "Kind of match");
define("VAR_LANG_EQUIPE", "Team");
define("VAR_LANG_EQUIPES", "Teams");
define("VAR_LANG_DATE", "Date");
define("VAR_LANG_A", "at"); // e.g. at 20:45
define("VAR_LANG_JOUERA_AVEC", "is going to play with");
define("VAR_LANG_SALLE", "Sports hall");
define("VAR_LANG_VILLE", "City");
define("VAR_LANG_CLASSEMENT", "Ranking");
define("VAR_LANG_ANNEE", "Year");
define("VAR_LANG_SPECTATEURS", "spectators");
define("VAR_LANG_ARBITRE", "referee");
define("VAR_LANG_ARBITRES", "Referees");
define("VAR_LANG_NIVEAU", "level");
define("VAR_LANG_TYPE_POINTS", "points type");


define("VAR_LANG_CLUB", "Club");
define("VAR_LANG_POINTS", "points");
define("VAR_LANG_GAGNES", "Won");
define("VAR_LANG_NULS", "Tie");
define("VAR_LANG_PERDUS", "Lost");
define("VAR_LANG_MARQUES", "Scored");
define("VAR_LANG_ENCAISSES", "Took");
define("VAR_LANG_GOOLAVERAGE", "Goal-average");
define("VAR_LANG_MANAGER", "Manager"); // pour Tchoukball Games
define("VAR_LANG_PAS_EGALITES_PARFAITES", "There's currently no perfect tied");
define("VAR_LANG_EGALITE_ORDRE_ALPHABETIQUE", "The ties are currently classified by alphabetical order.");
define("VAR_LANG_PAS_TOUCHER_POUR_RESOUDRE_EGALITE",
    "Don't touch if the ties are going to be solved with the coming matches.");
define("VAR_LANG_EGALITES_PARFAITES_RESOLUES_HASARD", "Égalitées parfaites résolues grâce au tir au sort");

// Coupe Suisse
define("VAR_LANG_INCONNU", "Unknown");
define("VAR_LANG_EDITION", "Edition");
define("VAR_LANG_SCORE_FINAL", "Final score");
define("VAR_LANG_SURVOL_TABLEAU", "Fly over the chart for more informations");


// Login
define("VAR_LANG_USER", "User");
define("VAR_LANG_USERNAME", "Username");
define("VAR_LANG_EMAIL", "Email");
define("VAR_LANG_USERNAME_OR_EMAIL", "Username or email");
define("VAR_LANG_PASSWORD", "Password");
define("VAR_LANG_REPEAT_PASSWORD", "Repeat password");
define("VAR_LANG_SEND_RESET_EMAIL", "Send reset e-mail");
define("VAR_LANG_RESET_PASSWORD", "Reset my password");
define("VAR_LANG_AUTO_CONNECTION", "Remember me");
define("VAR_LANG_LOGIN", "Log in");
define("VAR_LANG_LOGOUT", "Log out");
define("VAR_LANG_I_FORGOT_PASSWORD", "I forgot my password");

// Admin home
define("VAR_LANG_HELLO", "Hello");
define("VAR_LANG_ADMIN_WELCOME", "Welcome in the admin zone of the Swiss Tchoukball website.");

define("VAR_LANG_NON_SPECIFIE", "Non specifié");
define("VAR_LANG_NON_DEFINI", "Undefined");

define("VAR_LANG_TITRE", "Title");
define("VAR_LANG_FORMAT", "Format");
define("VAR_LANG_TAILLE", "Size");

// International
define("VAR_LANG_CATEGORIE", "Category");
define("VAR_LANG_CATEGORIES", "Categories");
define("VAR_LANG_SCORE", "Score");
define("VAR_LANG_SCORES", "Scores");
define("VAR_LANG_RESULTATS", "Results");
define("VAR_LANG_TERRAIN", "Field");
define("VAR_LANG_HEURE", "Time");
define("VAR_LANG_MATCH", "Match");
define("VAR_LANG_RESULTAT", "Result");
define("VAR_LANG_HEURE_SUISSE", "Swiss time");

define("VAR_LANG_DBD_ARBITRE", "Referee");
define("VAR_LANG_DBD_AUTRE_FONCTION", "Other function");
define("VAR_LANG_DBD_CHTB", "Tchouk Up");
define("VAR_LANG_DBD_CIVILITE", "Civilit&eacute;");
define("VAR_LANG_DBD_FORMATION", "Formation");
define("VAR_LANG_DBD_LANGUE", "Language");
define("VAR_LANG_DBD_PAYS", "Country");
define("VAR_LANG_DBD_STATUS", "Status");
define("VAR_LANG_DBD_ORIGINE_ADRESSE", "Origin of the adresse");
define("VAR_LANG_DBD_RAISON_SOCIALE", "Corporate name");
define("VAR_LANG_DBD_MEDIA_TYPE", "Type of media");
define("VAR_LANG_DBD_MEDIA_CANTON", "Média canton/state");

define("VAR_LANG_LIST_MODIFIER",
    "La modification d'une entr&eacute;e aura comme effet de changer la valeur de toutes ressources utilisant cette valeur.");
define("VAR_LANG_LIST_INSERER",
    "L'insertion d'une nouvelle entrée engendre une nouvelle possibilité partout o&ugrave; la liste est utilis&eacute;e.");
define("VAR_LANG_LIST_SUPPRIMER",
    "La suppression d’une option (entr&eacute;e) peut avoir de lourde cons&eacute;quence sur la coh&eacute;rence des donn&eacute;es, seul le webmaster peut supprimer des entr&eacute;es dans les listes, si vous voulez supprimer une entrée prenez contact avec lui.");
define("VAR_LANG_CONTACT", "Contact");
define("VAR_LANG_TELEPHONE", "Téléphone");
define("VAR_LANG_PORTABLE", "Mobile");

define("VAR_LANG_SAISON", "Saison");

define("VAR_LANG_CHTB_TITLE", "tchoukup");
define("VAR_LANG_CHTB_DOWNLOAD", "Consult the tchoukup archives");

define("VAR_LANG_ADMINISTRATION", "Administration");

define("VAR_LANG_DERNIERES_NEWS", "Last news");
define("VAR_LANG_PROCHAINS_EVENEMENTS", "Next events");
define("VAR_LANG_PROCHAINS_MATCHS", "Next matches");

//juniors
define("VAR_LANG_ANNEE_DE_NAISSANCE", "Ann&eacute;e de naissance");
define("VAR_LANG_AGE_EN", "Age in");
define("VAR_LANG_JEUNESSESPORT", "Jeunesse+Sport");
define("VAR_LANG_J+S", "J+S");
