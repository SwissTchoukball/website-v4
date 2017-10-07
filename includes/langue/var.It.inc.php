<?php
// fichier de variables en langue "it"

//inclure le fichier generer par la bd.
// include "generationFichier/var.bd.It.inc.php";

if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}

define("VAR_LANG_ASSOCIATION_NAME", "Swiss Tchoukball");
define("VAR_LANG_ASSOCIATION_NAME_ARTICLE", VAR_LANG_ASSOCIATION_NAME);
define("VAR_LANG_EN_CONSTRUCTION", "In costruzione...");

define("VAR_LANG_NO_JAVA_SCRIPT_MAIL", "Javscript deve essere attivato per visualizzare questo link.");

$VAR_G_JOURS_SEMAINE = array("Domenica", "Lunedi", "Martedi", "Mercoledi", "Giovedi", "Venerdi", "Sabato");
$VAR_G_MOIS = array(
    "Gennaio",
    "Febbraio",
    "Marzo",
    "Aprile",
    "Maggio",
    "Guigno",
    "Luglio",
    "Agosto",
    "Settembre",
    "Ottobre",
    "Novembre",
    "Dicembre"
);

/* JOURS DE LA SEMAINE avec le 0 vide */

$jourDeLaSemaine = array();

$jourDeLaSemaine[1] = 'lunedi';
$jourDeLaSemaine[2] = 'martedi';
$jourDeLaSemaine[3] = 'mercoledi';
$jourDeLaSemaine[4] = 'giovedi';
$jourDeLaSemaine[5] = 'venerdi';
$jourDeLaSemaine[6] = 'sabato';
$jourDeLaSemaine[7] = 'domenica';

/* MOIS DE L'ANNEE avec le 0 vide  */

$moisDeLAnnee = array();

$moisDeLAnnee[1] = 'gennaio';
$moisDeLAnnee[2] = 'febbraio';
$moisDeLAnnee[3] = 'marzo';
$moisDeLAnnee[4] = 'aprile';
$moisDeLAnnee[5] = 'maggio';
$moisDeLAnnee[6] = 'guigno';
$moisDeLAnnee[7] = 'luglio';
$moisDeLAnnee[8] = 'agosto';
$moisDeLAnnee[9] = 'sttembre';
$moisDeLAnnee[10] = 'ottobre';
$moisDeLAnnee[11] = 'novembre';
$moisDeLAnnee[12] = 'dicembre';

// exemple de date : Sabato 18 Aprile 2007
function afficherDateComplete()
{
    global $VAR_G_JOURS_SEMAINE;
    global $VAR_G_MOIS;

    $aujourdhui = getdate();
    echo $VAR_G_JOURS_SEMAINE[$aujourdhui["wday"]] . " " . $aujourdhui["mday"] . " " . $VAR_G_MOIS[$aujourdhui["mon"] - 1] . " " . $aujourdhui["year"];
}

define("VAR_LANG_INSERER", "Inserire");
define("VAR_LANG_MODIFIER", "Modificare");
define("VAR_LANG_SUPPRIMER", "Cancellare");
define("VAR_LANG_APPLIQUER", "Applicare");
define("VAR_LANG_ETAPE_SUIVANTE", "Tappa seguente");
define("VAR_LANG_ETAPE_1", "Tappa 1");
define("VAR_LANG_ETAPE_2", "Tappa 2");
define("VAR_LANG_ETAPE_3", "Tappa 3");
define("VAR_LANG_ETAPE_4", "Tappa 4");
define("VAR_LANG_ETAPE_5", "Tappa 5");
define("VAR_LANG_ANNULER", "Annulare");
define("VAR_LANG_ATTENTION", "Attenzione");
define("VAR_LANG_RETOUR", "Ritorno");
define("VAR_LANG_ACCUEIL", "Home");
define("VAR_LANG_INTROUVABLE", "Introvabile");
define("VAR_LANG_PLUS_INFOS", "Plus d'infos");
define("VAR_LANG_LEGENDE", "Legenda");
define("VAR_LANG_RESPONSABLE", "Responsabile");
define("VAR_LANG_RESPONSABLES", "Responsabili");
define("VAR_LANG_COMPETITIONS", "Competizioni");
define("VAR_LANG_MONTANT", "Ammontare");

// AGENDA
$agenda_annee = "Anno";
$agenda_categories = "Categoria";
$agenda_actualiser = "Attualizare";
$agenda_le = "the";
$agenda_du = "dal";
$agenda_au = "al";
$agenda_de = "from";
$agenda_a = "to";
$agenda_type = "Tipo";
$agenda_description = "Descrizione";
$agenda_lieu = "Luogo";
$agenda_debut = "Inizio";
$agenda_fin = "Fine";
$agenda_date = "Data";
$agenda_heure = "Ora";
$agenda_a_venir = "A venire";
$agenda_evenement = "Eventi";
$agenda_toutes = "All";
$agenda_vacances = "Holiday";
define("VAR_LANG_DESCRIPTION", "Descrizione");
define("VAR_LANG_AUCUN_EVENEMENT_A_VENIR", "No coming event in this category.");
define("VAR_LANG_AUCUNE_VACANCE_PENDANT_EVENEMENT", "No holiday during this event.");

//comite
define("VAR_LANG_DOMAINES_RESPONSABILITE", "Aree di responsabilit&agrave;");
define("VAR_LANG_DOMAINE_RESPONSABILITE", "Area di responsabilit&agrave;");

//clubs
define("VAR_LANG_NB_CLUBS", "Ci sono %d membri del club a " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ".");

// Telechargement
define("VAR_LANG_DOC_NOM_LIEN_FLYER_PRESENTATION",
    "Scaricare il flyer di presentazione (En)"); // nom lien flyer presentation
define("VAR_LANG_DOC_FLYER_PRESENTATION", "document/En_flyer_presentation.pdf"); // flyer presentation

// download
define("DOWNLOAD_ENTETE_DESCRIPTION", "Descrizione del flyer");
define("VAR_LANG_TELECHARGER", "Download");
define("VAR_LANG_TOUT", "Tutti");
//define("VAR_LANG_PRESSE","Press");

// news
define("VAR_LANG_NEWS_NON_TROUVEE", "La notizia richiesta non esiste nella base di dati.");
define("VAR_LANG_NEWS_SUIVANTES", "Notizie seguente");
define("VAR_LANG_NEWS_PRECEDENTES", "Notizie precedente");
define("VAR_LANG_NEWS_BACK_TO_NEWS", "Ritornare ai news");
define("VAR_LANG_LIRE_SUITE_ARTICLE", "Leggere l'articolo intero");

// Championnat
define("VAR_LANG_CHAMPIONNAT", "Campionato");
define("VAR_LANG_CHAMPIONNAT_SUISSE", "Championnat Suisse");
define("VAR_LANG_RENCONTRES_A_VENIR", "Incontri a venire");
define("VAR_LANG_LIGUE", "Lega");
define("VAR_LANG_TOUR", "Girone");
define("VAR_LANG_TOUR_FINAL", "Girone finale");
define("VAR_LANG_TOUR_PRECEDENT", "Girone precedente");
define("VAR_LANG_GROUPE", "Gruppo");
define("VAR_LANG_JOURNEE", "Girono");
define("VAR_LANG_ACTE", "Atto");
define("VAR_LANG_TYPE_MATCH", "Tipo di incontro");
define("VAR_LANG_EQUIPE", "squadra");
define("VAR_LANG_EQUIPES", "squadre");
define("VAR_LANG_DATE", "Data");
define("VAR_LANG_A", "alle"); // e.g. alle 20:45
define("VAR_LANG_JOUERA_AVEC", "giocherà con");
define("VAR_LANG_SALLE", "Palestra");
define("VAR_LANG_VILLE", "Città");
define("VAR_LANG_CLASSEMENT", "Classifica");
define("VAR_LANG_ANNEE", "Anno");
define("VAR_LANG_SPECTATEURS", "spettatori");
define("VAR_LANG_ARBITRE", "arbitro");
define("VAR_LANG_ARBITRES", "Arbitro");
define("VAR_LANG_NIVEAU", "livello");
define("VAR_LANG_TYPE_POINTS", "tipo di punti");


define("VAR_LANG_CLUB", "Club");
define("VAR_LANG_POINTS", "points");
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
define("VAR_LANG_INCONNU", "Inconnu");
define("VAR_LANG_EDITION", "Edition");
define("VAR_LANG_SCORE_FINAL", "Score final");
define("VAR_LANG_SURVOL_TABLEAU", "Survolez le tableau pour avoir plus d'informations");

// Login
define("VAR_LANG_USER", "Utente");
define("VAR_LANG_USERNAME", "Nome utente");
define("VAR_LANG_EMAIL", "E-mail");
define("VAR_LANG_USERNAME_OR_EMAIL", "Nome utente o e-mail");
define("VAR_LANG_PASSWORD", "Password");
define("VAR_LANG_REPEAT_PASSWORD", "Ripeti la password");
define("VAR_LANG_SEND_RESET_EMAIL", "Invia e-mail resettata");
define("VAR_LANG_RESET_PASSWORD", "Reimposta la mia password");
define("VAR_LANG_AUTO_CONNECTION", "Ricordami");
define("VAR_LANG_LOGIN", "Collegarsi");
define("VAR_LANG_LOGOUT", "Scollegarsi");
define("VAR_LANG_I_FORGOT_PASSWORD", "Ho dimenticato la mia password");

// Admin home
define("VAR_LANG_HELLO", "Buongiorno");
define("VAR_LANG_ADMIN_WELCOME", "Benvenuti nella sezione admin del sito web Swiss Tchoukball.");

define("VAR_LANG_NON_SPECIFIE", "Non specificato");
define("VAR_LANG_NON_DEFINI", "Indefinito");

define("VAR_LANG_TITRE", "Titre");
define("VAR_LANG_FORMAT", "Format");
define("VAR_LANG_TAILLE", "Taille");

// International
define("VAR_LANG_CATEGORIE", "Categoria");
define("VAR_LANG_CATEGORIES", "Categorie");
define("VAR_LANG_SCORE", "Score");
define("VAR_LANG_SCORES", "Scores");
define("VAR_LANG_RESULTATS", "Risultati");
define("VAR_LANG_TERRAIN", "Campo");
define("VAR_LANG_HEURE", "Ora");
define("VAR_LANG_MATCH", "Incontro");
define("VAR_LANG_RESULTAT", "Risultato");
define("VAR_LANG_HEURE_SUISSE", "Ora svizzera");

define("VAR_LANG_DBD_ARBITRE", "Arbitro");
define("VAR_LANG_DBD_AUTRE_FONCTION", "Altra Funzione");
define("VAR_LANG_DBD_CHTB", "Suisse Tchoukball");
define("VAR_LANG_DBD_CIVILITE", "Civilit&agrave;");
define("VAR_LANG_DBD_FORMATION", "Formazione");
define("VAR_LANG_DBD_LANGUE", "Lingua");
define("VAR_LANG_DBD_PAYS", "Nazione");
define("VAR_LANG_DBD_STATUS", "Statuto");
define("VAR_LANG_DBD_ORIGINE_ADRESSE", "Indirizzo");
define("VAR_LANG_DBD_RAISON_SOCIALE", "Aziendo");
define("VAR_LANG_DBD_MEDIA_TYPE", "Tipo di media");
define("VAR_LANG_DBD_MEDIA_CANTON", "Media cantone/stato");

define("VAR_LANG_LIST_MODIFIER",
    "La modifica di un'entrata cambierà il valore di tutte le risorse che usano questo valore.");
define("VAR_LANG_LIST_INSERER",
    "L'inserzione di una nuova entrata genera una nuova possiblità ovunque la lista è usata.");
define("VAR_LANG_LIST_SUPPRIMER",
    "La cancellazione di un opzione può avere gravi conseguenzi sulla coerenza dei dati. Solo il webmaster può sopprimere delle entrate nelle liste. Se desiderate cancellare un'entrata, contatare l'amministratore.");
define("VAR_LANG_CONTACT", "Contatto");
define("VAR_LANG_TELEPHONE", "Telefono");
define("VAR_LANG_PORTABLE", "Mobile");

define("VAR_LANG_SAISON", "Stagione");

define("VAR_LANG_CHTB_TITLE", "tchoukup");
define("VAR_LANG_CHTB_DOWNLOAD", "Vedere gli archivi di tchoukup");

define("VAR_LANG_ADMINISTRATION", "Amministrazione");

define("VAR_LANG_DERNIERES_NEWS", "Ultime notizie");
define("VAR_LANG_PROCHAINS_EVENEMENTS", "Prossimi eventi");
define("VAR_LANG_PROCHAINS_MATCHS", "Prossimi incontro");

//juniors
define("VAR_LANG_ANNEE_DE_NAISSANCE", "Ann&eacute;e de naissance");
define("VAR_LANG_AGE_EN", "Età nel");
define("VAR_LANG_JEUNESSESPORT", "Giovani+Sport");
define("VAR_LANG_J+S", "G+S");
