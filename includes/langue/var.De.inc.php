<?php
// fichier de variables en langue "de"

//inclure le fichier generer par la bd.
// include "generationFichier/var.bd.De.inc.php";

if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}

define("VAR_LANG_ASSOCIATION_NAME", "Swiss Tchoukball");
define("VAR_LANG_ASSOCIATION_NAME_ARTICLE", VAR_LANG_ASSOCIATION_NAME);
define("VAR_LANG_EN_CONSTRUCTION", "Im Aufbau...");

define("VAR_LANG_NO_JAVA_SCRIPT_MAIL", "JavaScript muss aktiviert sein, um diese Webseite richtig anzuzeigen.");

$VAR_G_JOURS_SEMAINE = array("Sontag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
$VAR_G_MOIS = array(
    "Januar",
    "Februar",
    "M&auml;rz",
    "April",
    "Mai",
    "Juni",
    "Juli",
    "August",
    "September",
    "Oktober",
    "November",
    "Dezembre"
);

/* JOURS DE LA SEMAINE avec le 0 vide */

$jourDeLaSemaine = array();

$jourDeLaSemaine[1] = 'Montag';
$jourDeLaSemaine[2] = 'Dienstag';
$jourDeLaSemaine[3] = 'Mittwoch';
$jourDeLaSemaine[4] = 'Donnerstag';
$jourDeLaSemaine[5] = 'Freitag';
$jourDeLaSemaine[6] = 'Samstag';
$jourDeLaSemaine[7] = 'Sontag';

/* MOIS DE L'ANNEE avec le 0 vide  */

$moisDeLAnnee = array();

$moisDeLAnnee[1] = 'Januar';
$moisDeLAnnee[2] = 'Februar';
$moisDeLAnnee[3] = 'März';
$moisDeLAnnee[4] = 'April';
$moisDeLAnnee[5] = 'Mai';
$moisDeLAnnee[6] = 'Juni';
$moisDeLAnnee[7] = 'Juli';
$moisDeLAnnee[8] = 'August';
$moisDeLAnnee[9] = 'September';
$moisDeLAnnee[10] = 'Oktober';
$moisDeLAnnee[11] = 'November';
$moisDeLAnnee[12] = 'Dezember';

// exemple de date : Montag, den 19. April 2004.
function afficherDateComplete()
{
    global $VAR_G_JOURS_SEMAINE;
    global $VAR_G_MOIS;

    $aujourdhui = getdate();
    echo $VAR_G_JOURS_SEMAINE[$aujourdhui["wday"]] . ", den " . $aujourdhui["mday"] . ". " . $VAR_G_MOIS[$aujourdhui["mon"] - 1] . " " . $aujourdhui["year"];

}

define("VAR_LANG_INSERER", "Einfügen");
define("VAR_LANG_MODIFIER", "Verändern");
define("VAR_LANG_SUPPRIMER", "Löschen");
define("VAR_LANG_APPLIQUER", "Anwenden");
define("VAR_LANG_ETAPE_SUIVANTE", "Nächste Etappe");
define("VAR_LANG_ETAPE_1", "Etappe 1");
define("VAR_LANG_ETAPE_2", "Etappe 2");
define("VAR_LANG_ETAPE_3", "Etappe 3");
define("VAR_LANG_ETAPE_4", "Etappe 4");
define("VAR_LANG_ETAPE_5", "Etappe 5");
define("VAR_LANG_ANNULER", "Abbrechen");
define("VAR_LANG_ATTENTION", "Vorsicht");
define("VAR_LANG_RETOUR", "Zurück");
define("VAR_LANG_ACCUEIL", "Home");
define("VAR_LANG_INTROUVABLE", "Kann nicht gefunden werden");
define("VAR_LANG_PLUS_INFOS", "Plus d'infos");
define("VAR_LANG_LEGENDE", "Legende");
define("VAR_LANG_RESPONSABLE", "Verantwochtliche");
define("VAR_LANG_RESPONSABLES", "Verantwochtlichen");
define("VAR_LANG_COMPETITIONS", "Competitions");
define("VAR_LANG_MONTANT", "Betrag");

// AGENDA
$agenda_annee = "Jahr";
$agenda_categories = "Klasse";
$agenda_actualiser = "Aktualisieren";
$agenda_le = "am";
$agenda_du = "von";
$agenda_au = "bis";
$agenda_de = "von";
$agenda_a = "bis";
$agenda_type = "Typ";
$agenda_description = "Beschreibung";
$agenda_lieu = "Ort";
$agenda_debut = "Anfang";
$agenda_fin = "Ende";
$agenda_date = "Datum";
$agenda_heure = "Stunde";
$agenda_a_venir = "Zu kommen";
$agenda_evenement = "Event";
$agenda_toutes = "Alles";
$agenda_vacances = "Holiday";
define("VAR_LANG_DESCRIPTION", "Beschreibung");
define("VAR_LANG_AUCUN_EVENEMENT_A_VENIR", "No coming event in this category.");
define("VAR_LANG_AUCUNE_VACANCE_PENDANT_EVENEMENT", "No holiday during this event.");


//comite
define("VAR_LANG_DOMAINES_RESPONSABILITE", "Verantwortungen");
define("VAR_LANG_DOMAINE_RESPONSABILITE", "Verantwortung");

//clubs
define("VAR_LANG_NB_CLUBS", "Es gibt %d Vereine Mitglieder zu " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ".");

// Telechargement
define("VAR_LANG_DOC_NOM_LIEN_FLYER_PRESENTATION",
    "Das Vorstellungsprospekt downloaden"); // nom lien flyer presentation
define("VAR_LANG_DOC_FLYER_PRESENTATION", "document/De_flyer_presentation.pdf"); // flyer presentation

// download
define("DOWNLOAD_ENTETE_DESCRIPTION", "Dokumentbeschreibung");
define("VAR_LANG_TELECHARGER", "Download");
define("VAR_LANG_TOUT", "Alle");
//define("VAR_LANG_PRESSE","Presse");

// news
define("VAR_LANG_NEWS_NON_TROUVEE", "Die gesuchte News gibt es in der Datenbank nicht. ");
define("VAR_LANG_NEWS_SUIVANTES", "N&atilde;chsten Nachrichten");
define("VAR_LANG_NEWS_PRECEDENTES", "Vorigen Nachrichten");
define("VAR_LANG_NEWS_BACK_TO_NEWS", "Zurück zu den News");
define("VAR_LANG_LIRE_SUITE_ARTICLE", "Lesen den Nachrichten");

// Championnat
define("VAR_LANG_CHAMPIONNAT", "Meistershaft");
define("VAR_LANG_CHAMPIONNAT_SUISSE", "Schweizerischer Meistershaft");
define("VAR_LANG_RENCONTRES_A_VENIR", "Comming games");
define("VAR_LANG_LIGUE", "League");
define("VAR_LANG_TOUR", "Runde");
define("VAR_LANG_TOUR_FINAL", "Finalerunde");
define("VAR_LANG_TOUR_PRECEDENT", "Vorherige Runde");
define("VAR_LANG_GROUPE", "Groupe");
define("VAR_LANG_JOURNEE", "Tag");
define("VAR_LANG_ACTE", "Akt");
define("VAR_LANG_TYPE_MATCH", "Type de Match");
define("VAR_LANG_EQUIPE", "Mannschaft");
define("VAR_LANG_EQUIPES", "Mannschaften");
define("VAR_LANG_DATE", "Datum");
define("VAR_LANG_A", "um"); // e.g. um 20:45
define("VAR_LANG_JOUERA_AVEC", "jouera avec");
define("VAR_LANG_SALLE", "Sporthalle");
define("VAR_LANG_VILLE", "Stadt");
define("VAR_LANG_CLASSEMENT", "Ranglistentabelle");
define("VAR_LANG_ANNEE", "Jahr");
define("VAR_LANG_SPECTATEURS", "Zuchauer");
define("VAR_LANG_ARBITRE", "Schiedsrichter(in)");
define("VAR_LANG_ARBITRES", "Schiedsrichter");
define("VAR_LANG_NIVEAU", "Niveau");
define("VAR_LANG_TYPE_POINTS", "art von Punkten");


define("VAR_LANG_CLUB", "Club");
define("VAR_LANG_POINTS", "Points");
define("VAR_LANG_GAGNES", "Gagnés");
define("VAR_LANG_NULS", "Nuls");
define("VAR_LANG_PERDUS", "Perdus");
define("VAR_LANG_MARQUES", "Marqués");
define("VAR_LANG_ENCAISSES", "Encaissés");
define("VAR_LANG_GOOLAVERAGE", "Goolaverage");
define("VAR_LANG_MANAGER", "Manager"); // pour Tchoukball Games
define("VAR_LANG_PAS_EGALITES_PARFAITES", "Il n'y a actuellement pas d'égalités parfaites.");
define("VAR_LANG_EGALITE_ORDRE_ALPHABETIQUE", "Les égalités sont actuellement classées par ordre alphabétique.");
define("VAR_LANG_PAS_TOUCHER_POUR_RESOUDRE_EGALITE",
    "Ne rien toucher si les égalités vont se résoudre avec les matchs à venir.");
define("VAR_LANG_EGALITES_PARFAITES_RESOLUES_HASARD", "Égalitées parfaites résolues grâce au tir au sort");

// Coupe Suisse
define("VAR_LANG_INCONNU", "Unbekannt");
define("VAR_LANG_EDITION", "Edition");
define("VAR_LANG_SCORE_FINAL", "Score final");
define("VAR_LANG_SURVOL_TABLEAU", "Survolez le tableau pour avoir plus d'informations");

// Login
define("VAR_LANG_USER", "Benutzer");
define("VAR_LANG_USERNAME", "Benutzername");
define("VAR_LANG_EMAIL", "E-mail");
define("VAR_LANG_USERNAME_OR_EMAIL", "Benutzername oder e-mail");
define("VAR_LANG_PASSWORD", "Passwort");
define("VAR_LANG_REPEAT_PASSWORD", "Passwort wiederholen");
define("VAR_LANG_SEND_RESET_EMAIL", "Senden Reset-E-Mail");
define("VAR_LANG_RESET_PASSWORD", "Mein Passwort zurücksetzen");
define("VAR_LANG_AUTO_CONNECTION", "Remember me");
define("VAR_LANG_LOGIN", "Sich einloggen");
define("VAR_LANG_LOGOUT", "Sich ausloggen");
define("VAR_LANG_I_FORGOT_PASSWORD", "Ich habe mein passwort vergessen");

// Admin home
define("VAR_LANG_HELLO", "Hallo");
define("VAR_LANG_ADMIN_WELCOME", "Willkommen in der Admin-Zone von Swiss Tchoukball Website.");

define("VAR_LANG_NON_SPECIFIE", "Nicht angegeben");
define("VAR_LANG_NON_DEFINI", "nicht definiert");

define("VAR_LANG_TITRE", "Titre");
define("VAR_LANG_FORMAT", "Format");
define("VAR_LANG_TAILLE", "Taille");

// International
define("VAR_LANG_CATEGORIE", "Kategorie");
define("VAR_LANG_CATEGORIES", "Kategorien");
define("VAR_LANG_SCORE", "Score");
define("VAR_LANG_SCORES", "Scores");
define("VAR_LANG_RESULTATS", "Ergebnisse");
define("VAR_LANG_TERRAIN", "Ort");
define("VAR_LANG_HEURE", "Stunde");
define("VAR_LANG_MATCH", "Match");
define("VAR_LANG_RESULTAT", "Ergebnis");
define("VAR_LANG_HEURE_SUISSE", "Schweizerische Stunde");

define("VAR_LANG_DBD_ARBITRE", "Schiedsrichter");
define("VAR_LANG_DBD_AUTRE_FONCTION", "Andere Funktion");
define("VAR_LANG_DBD_CHTB", "Suisse Tchoukball");
define("VAR_LANG_DBD_CIVILITE", "Anrede");
define("VAR_LANG_DBD_FORMATION", "Ausbildung");
define("VAR_LANG_DBD_LANGUE", "Sprache");
define("VAR_LANG_DBD_PAYS", "Land");
define("VAR_LANG_DBD_STATUS", "Status");
define("VAR_LANG_DBD_ORIGINE_ADRESSE", "Ursprung der Adresse");
define("VAR_LANG_DBD_RAISON_SOCIALE", "Firmenbezeichnung");
define("VAR_LANG_DBD_MEDIA_TYPE", "Type de m&eacute;dia");
define("VAR_LANG_DBD_MEDIA_CANTON", "Kanton/Staat des Mediums");

define("VAR_LANG_LIST_MODIFIER",
    "Die Veränderung einer Eingabe führt zur Veränderung des Wertes aller Ressourcen, die diesen Wert gebrauchen. ");
define("VAR_LANG_LIST_INSERER",
    "Das Einfügen einer neuen Eingabe führt zu einer neuen Möglichkeit dort, wo die Liste gebraucht wird.");
define("VAR_LANG_LIST_SUPPRIMER",
    "Das Löschen einer Option (Eingabe) kann schwere Auswirkungen auf die Kohärenz der Daten haben; allein der Webmaster kann Eingaben in den Listen löschen. Nehmen Sie bitte mit ihm Kontakt auf, falls Sie eine Eingabe löschen möchten. ");
define("VAR_LANG_CONTACT", "Kontakt");
define("VAR_LANG_TELEPHONE", "Telefon");
define("VAR_LANG_PORTABLE", "Handy");

define("VAR_LANG_SAISON", "Saison");

define("VAR_LANG_CHTB_TITLE", "tchoukup");
define("VAR_LANG_CHTB_DOWNLOAD", "Schauen Sie sich das Archiv der tchoukup an");

define("VAR_LANG_ADMINISTRATION", "Verwaltung");

define("VAR_LANG_DERNIERES_NEWS", "Die letzten News");
define("VAR_LANG_PROCHAINS_EVENEMENTS", "Kommende Events");
define("VAR_LANG_PROCHAINS_MATCHS", "Kommende Matchs");

//juniors
define("VAR_LANG_ANNEE_DE_NAISSANCE", "Ann&eacute;e de naissance");
define("VAR_LANG_AGE_EN", "Alte im");
define("VAR_LANG_JEUNESSESPORT", "Jugend+Sport");
define("VAR_LANG_J+S", "J+S");
