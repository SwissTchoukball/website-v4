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
define("VAR_LANG_EN_MODIFICATION", "Wird verändert...");

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
define("VAR_LANG_LISTE_EVENEMENTS", "Show coming events list");
define("VAR_LANG_AUCUN_EVENEMENT_A_VENIR", "No coming event in this category.");
define("VAR_LANG_AUCUNE_VACANCE_PENDANT_EVENEMENT", "No holiday during this event.");


//comite
define("VAR_LANG_DOMAINES_RESPONSABILITE", "Verantwortungen:");
define("VAR_LANG_DOMAINE_RESPONSABILITE", "Verantwortung:");

//clubs
define("VAR_LANG_VISIT_US", "Zur Website");
define("VAR_LANG_NB_CLUBS", "Es gibt %d Vereine Mitglieder zu " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ".");

//Equipe suisse
define("VAR_LANG_ES_PHOTO", "Bild");
define("VAR_LANG_ES_NOM_PRENOM", "Name, vorname");
define("VAR_LANG_ES_POSTE_FONCTION", "Posten/Funktion");
define("VAR_LANG_ES_CLUB", "Klub");
define("VAR_LANG_ES_EXPERIENCE", "Erfahrung");
define("VAR_LANG_ACCES_FITB_BDD",
    "Accéder à la base de données de la <acronym title='Fédération Internationale de Tchoukball'>FITB</acronym>");
define("VAR_LANG_ARBITRE_INTER", "Arbitres internationaux");
define("VAR_LANG_ARBITRES", "Schiedsrichter");

// Telechargement
define("VAR_LANG_DOC_NOM_LIEN_FLYER_PRESENTATION",
    "Das Vorstellungsprospekt downloaden"); // nom lien flyer presentation
define("VAR_LANG_DOC_FLYER_PRESENTATION", "document/De_flyer_presentation.pdf"); // flyer presentation

// championnat
$aujourd_hui = getdate();
$anneeStart = $aujourd_hui["year"];
define("VAR_LANG_CHAMPIONNAT_NON_COMMENCE",
    "Die " . $anneeStart . "-" . ($anneeStart + 1) . " Meisterschaft hat noch nicht begonnen.");

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

// news letter
define("VAR_LANG_INSCRIPTION", "Anmeldung");
define("VAR_LANG_DESINSCRIPTION", "Abmeldung");

// Championnat
define("VAR_LANG_CHAMPIONNAT", "Meistershaft");
define("VAR_LANG_CHAMPIONNAT_SUISSE", "Schweizerischer Meistershaft");
define("VAR_LANG_RENCONTRES_A_VENIR", "Comming games");
define("VAR_LANG_LIGUE", "League");
define("VAR_LANG_TOUR", "Turn");
define("VAR_LANG_TOUR_FINAL", "Ende turn");
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
define("VAR_LANG_PAS_DE_MATCH", "Pas de match");
define("VAR_LANG_AUTO_QUALIF", "est automatiquement qualifié");
define("VAR_LANG_DECLARE_FORFAIT", "a déclaré forfait");
define("VAR_LANG_DISQUALIFIE", "a été disqualifié");
define("VAR_LANG_SCORE_FINAL", "Score final");
define("VAR_LANG_SURVOL_TABLEAU", "Survolez le tableau pour avoir plus d'informations");

//quick menu
define("VAR_LANG_QUICK_NEWS", "News");
define("VAR_LANG_QUICK_DOWNLOAD", "Download");
define("VAR_LANG_AUCUNE_NEWS_POUR_CATEGORIE", "In dieser Kategorie sind keine News erhältlich. ");
define("VAR_LANG_AUCUNE_FICHIER_POUR_CATEGORIE", "In dieser Kategorie ist keine Datei erhältlich. ");

// admin
define("VAR_LANG_NOM", "Name");
define("VAR_LANG_PRENOM", "Vorname");
define("VAR_LANG_USERNAME", "Benutzername");
define("VAR_LANG_PASSWORD", "Passwort");
define("VAR_LANG_AUTO_CONNECTION", "Remember me");
define("VAR_LANG_SUPPRIMER_AUTO_CONNEXION", "Auf die automatische Verbindung verzichten ");
define("VAR_LANG_SE_LOGUER", "Sich einloggen");
define("VAR_LANG_DECONNEXION", "Sich ausloggen");
define("VAR_LANG_MODIFIER_MON_PROFIL", "Modifier mon profil");

define("VAR_LANG_NON_SPECIFIE", "Nicht angegeben");
define("VAR_LANG_NON_DEFINI", "nicht definiert");
define("VAR_LANG_OPTION_VEROUILLEE", "Gesperrte Option");

define("VAR_LANG_ACCES_INTERDIT", "Kein Zutritt");
define("VAR_LANG_TEXTE_ACCES_INTERDIT", "Sie haben keinen Zugang zum Admin-Teil dieser Webseite.");
define("VAR_LANG_TITRE", "Titre");
define("VAR_LANG_FORMAT", "Format");
define("VAR_LANG_TAILLE", "Taille");

// International
define("VAR_LANG_EQUIPE", "Mannschaft");
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
define("VAR_LANG_GENEVE_2005", "WORLD BEACH TCHOUKBALL CHAMPIONSHIP<br>Geneva 2005");
define("VAR_LANG_TAIWAN_2004", "WORLD TCHOUKBALL CHAMPIONSHIP 2004<br>Kaoshiung, Taiwan ROC");
define("VAR_LANG_ITALIE_2003", "EUROPEEN TCHOUKBALL CHAMPIONSHIP 2003<br>Rimini, Italy");
define("VAR_LANG_GENEVE_2005_SHORT", "Genf 2005");
define("VAR_LANG_TAIWAN_2004_SHORT", "Taiwan 2004");
define("VAR_LANG_ITALIE_2003_SHORT", "Italien 2003");

// Commission
define("VAR_LANG_LIEN_PAGE_COMMISSION", "Link zur Kommission");

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

define("VAR_LANG_CLUB_FSTB", "Verein");

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

define("VAR_LANG_CLASSEMENT_CHAMPIONNAT", "Ranglistentabelle der Meisterschaft");
define("VAR_LANG_DERNIERES_NEWS", "Die letzten News");
define("VAR_LANG_PROCHAINS_EVENEMENTS", "Kommende Events");
define("VAR_LANG_PROCHAINS_MATCHS", "Kommende Matchs");

//juniors
define("VAR_LANG_ANNEE_DE_NAISSANCE", "Ann&eacute;e de naissance");
define("VAR_LANG_AGE_EN", "Alte im");
define("VAR_LANG_JEUNESSESPORT", "Jugend+Sport");
define("VAR_LANG_J+S", "J+S");

// TCHOUKBALL GAMES

define("VAR_LANG_RETOUR_LISTE_JEUX", "Retour à la liste des jeux");
define("VAR_LANG_SON_JEU", "Son jeu");
define("VAR_LANG_SES_JEUX", "Ses jeux");
define("VAR_LANG_AUTEUR", "Auteur");
define("VAR_LANG_AUTEURS", "Auteurs");
define("VAR_LANG_VISITER_SITE", "Zur Website");
define("VAR_LANG_NOM_JEU", "Nom du jeu");
define("VAR_LANG_DESCRIPTION_COURTE", "Description courte");
define("VAR_LANG_DESCRIPTION_LONGUE", "Description longue");
define("VAR_LANG_ADRESSE_EMAIL", "Adresse email");
define("VAR_LANG_SITE_WEB", "Site web");
?>
