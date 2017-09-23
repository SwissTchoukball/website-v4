<?php
// fichier de variables en langue "fr"

//inclure le fichier generer par la bd.
// include "generationFichier/var.bd.Fr.inc.php";

if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}

define("VAR_LANG_ASSOCIATION_NAME", "Swiss Tchoukball");
define("VAR_LANG_ASSOCIATION_NAME_ARTICLE", VAR_LANG_ASSOCIATION_NAME);
define("VAR_LANG_EN_CONSTRUCTION", "En construction...");

define("VAR_LANG_NO_JAVA_SCRIPT_MAIL", "JavaScript doit être activ&eacute; pour afficher ce lien.");

$VAR_G_JOURS_SEMAINE = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$VAR_G_MOIS = array(
    "janvier",
    "f&eacute;vrier",
    "mars",
    "avril",
    "mai",
    "juin",
    "juillet",
    "août",
    "septembre",
    "octobre",
    "novembre",
    "d&eacute;cembre"
);

/* JOURS DE LA SEMAINE avec le 0 vide */

$jourDeLaSemaine = array();

$jourDeLaSemaine[1] = 'lundi';
$jourDeLaSemaine[2] = 'mardi';
$jourDeLaSemaine[3] = 'mercredi';
$jourDeLaSemaine[4] = 'jeudi';
$jourDeLaSemaine[5] = 'vendredi';
$jourDeLaSemaine[6] = 'samedi';
$jourDeLaSemaine[7] = 'dimanche';

/* MOIS DE L'ANNEE avec le 0 vide  */

$moisDeLAnnee = array();

$moisDeLAnnee[1] = 'janvier';
$moisDeLAnnee[2] = 'février';
$moisDeLAnnee[3] = 'mars';
$moisDeLAnnee[4] = 'avril';
$moisDeLAnnee[5] = 'mai';
$moisDeLAnnee[6] = 'juin';
$moisDeLAnnee[7] = 'juillet';
$moisDeLAnnee[8] = 'août';
$moisDeLAnnee[9] = 'septembre';
$moisDeLAnnee[10] = 'octobre';
$moisDeLAnnee[11] = 'novembre';
$moisDeLAnnee[12] = 'décembre';

// exemple de date : Lundi 19 avril 2004
function afficherDateComplete()
{
    global $VAR_G_JOURS_SEMAINE;
    global $VAR_G_MOIS;

    $aujourdhui = getdate();
    echo $VAR_G_JOURS_SEMAINE[$aujourdhui["wday"]] . " " . $aujourdhui["mday"] . " " . $VAR_G_MOIS[$aujourdhui["mon"] - 1] . " " . $aujourdhui["year"];
}

define("VAR_LANG_INSERER", "Insérer");
define("VAR_LANG_MODIFIER", "Modifier");
define("VAR_LANG_SUPPRIMER", "Supprimer");
define("VAR_LANG_APPLIQUER", "Appliquer");
define("VAR_LANG_ETAPE_SUIVANTE", "Etape Suivante");
define("VAR_LANG_ETAPE_1", "Etape 1");
define("VAR_LANG_ETAPE_2", "Etape 2");
define("VAR_LANG_ETAPE_3", "Etape 3");
define("VAR_LANG_ETAPE_4", "Etape 4");
define("VAR_LANG_ETAPE_5", "Etape 5");
define("VAR_LANG_ANNULER", "Annuler");
define("VAR_LANG_ATTENTION", "Attention");
define("VAR_LANG_RETOUR", "Retour");
define("VAR_LANG_ACCUEIL", "Accueil");
define("VAR_LANG_INTROUVABLE", "Introuvable");
define("VAR_LANG_PLUS_INFOS", "Plus d'infos");
define("VAR_LANG_LEGENDE", "Légende");
define("VAR_LANG_RESPONSABLE", "Responsable");
define("VAR_LANG_RESPONSABLES", "Responsables");
define("VAR_LANG_COMPETITIONS", "Compétitions");
define("VAR_LANG_MONTANT", "Montant");

// AGENDA
$agenda_annee = "Ann&eacute;e";
$agenda_categories = "Cat&eacute;gories";
$agenda_actualiser = "Actualiser";
$agenda_le = "le";
$agenda_du = "du";
$agenda_au = "au";
$agenda_de = "de";
$agenda_a = "à";
$agenda_type = "Type";
$agenda_description = "Description";
$agenda_lieu = "Lieu";
$agenda_debut = "D&eacute;but";
$agenda_fin = "Fin";
$agenda_date = "Date";
$agenda_heure = "Heure";
$agenda_a_venir = "A venir";
$agenda_evenement = "Événement";
$agenda_toutes = "Toutes";
$agenda_vacances = "Vacances";
define("VAR_LANG_DESCRIPTION", "Description");
define("VAR_LANG_AUCUN_EVENEMENT_A_VENIR", "Aucun événement à venir dans cette catégorie.");
define("VAR_LANG_AUCUNE_VACANCE_PENDANT_EVENEMENT",
    "Cet événement n'a lieu en même temps qu'aucune vacance répertoriée.");

//comite
define("VAR_LANG_DOMAINES_RESPONSABILITE", "Domaines de responsabilit&eacute;");
define("VAR_LANG_DOMAINE_RESPONSABILITE", "Domaine de responsabilit&eacute;");

//clubs
define("VAR_LANG_NB_CLUBS", "Il y a %d clubs adhérants à " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ".");

// Telechargement
define("VAR_LANG_DOC_NOM_LIEN_FLYER_PRESENTATION",
    "T&eacute;l&eacute;charger le flyer de pr&eacute;sentation"); // nom lien flyer presentation
define("VAR_LANG_DOC_FLYER_PRESENTATION", "document/Fr_flyer_presentation.pdf"); // flyer presentation

// download
define("DOWNLOAD_ENTETE_DESCRIPTION", "Description du fichier");
define("VAR_LANG_TELECHARGER", "Télécharger");
define("VAR_LANG_TOUT", "Tout");
//define("VAR_LANG_PRESSE","Presse");

// news
define("VAR_LANG_NEWS_NON_TROUVEE", "La news demand&eacute;e n'existe pas dans la base de donn&eacute;es.");
define("VAR_LANG_NEWS_SUIVANTES", "News suivantes");
define("VAR_LANG_NEWS_PRECEDENTES", "News pr&eacute;c&eacute;dentes");
define("VAR_LANG_NEWS_BACK_TO_NEWS", "Revenir aux news");
define("VAR_LANG_LIRE_SUITE_ARTICLE", "Lire l'article en entier");

// Championnat
define("VAR_LANG_CHAMPIONNAT", "Championnat");
define("VAR_LANG_CHAMPIONNAT_SUISSE", "Championnat Suisse");
define("VAR_LANG_RENCONTRES_A_VENIR", "Rencontres à venir");
define("VAR_LANG_LIGUE", "Ligue");
define("VAR_LANG_TOUR", "Tour");
define("VAR_LANG_TOUR_FINAL", "Tour final");
define("VAR_LANG_TOUR_PRECEDENT", "Tour précédent");
define("VAR_LANG_GROUPE", "Groupe");
define("VAR_LANG_JOURNEE", "Journée");
define("VAR_LANG_ACTE", "Acte");
define("VAR_LANG_TYPE_MATCH", "Type de Match");
define("VAR_LANG_EQUIPE", "Equipe");
define("VAR_LANG_EQUIPES", "Equipes");
define("VAR_LANG_DATE", "Date");
define("VAR_LANG_A", "à"); // e.g. à 20:45
define("VAR_LANG_JOUERA_AVEC", "jouera avec");
define("VAR_LANG_SALLE", "Salle");
define("VAR_LANG_VILLE", "Ville");
define("VAR_LANG_CLASSEMENT", "Classement");
define("VAR_LANG_ANNEE", "Année");
define("VAR_LANG_SPECTATEURS", "spectateurs");
define("VAR_LANG_ARBITRE", "arbitre");
define("VAR_LANG_ARBITRES", "Arbitres");
define("VAR_LANG_NIVEAU", "niveau");
define("VAR_LANG_TYPE_POINTS", "type de points");

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

// admin
define("VAR_LANG_USERNAME", "Nom d'utilisateur");
define("VAR_LANG_EMAIL", "E-mail");
define("VAR_LANG_USERNAME_OR_EMAIL", "Nom d'utilisateur ou e-mail");
define("VAR_LANG_PASSWORD", "Mot de passe");
define("VAR_LANG_REPEAT_PASSWORD", "Répéter le mot de passe");
define("VAR_LANG_SEND_RESET_EMAIL", "Envoyer l'e-mail de réinitialisation");
define("VAR_LANG_RESET_PASSWORD", "Réinitialiser mon mot de passe");
define("VAR_LANG_AUTO_CONNECTION", "Se rappeler de moi");
define("VAR_LANG_LOGIN", "Se connecter");
define("VAR_LANG_LOGOUT", "Se déconnecter");
define("VAR_LANG_I_FORGOT_PASSWORD", "J'ai oublié mon mot de passe");

define("VAR_LANG_NON_SPECIFIE", "Non specifi&eacute;");
define("VAR_LANG_NON_DEFINI", "Non défini");

define("VAR_LANG_TITRE", "Titre");
define("VAR_LANG_FORMAT", "Format");
define("VAR_LANG_TAILLE", "Taille");

// International
define("VAR_LANG_CATEGORIE", "Catégorie");
define("VAR_LANG_CATEGORIES", "Catégories");
define("VAR_LANG_SCORE", "Score");
define("VAR_LANG_SCORES", "Scores");
define("VAR_LANG_RESULTATS", "Résultats");
define("VAR_LANG_TERRAIN", "Terrain");
define("VAR_LANG_HEURE", "Heure");
define("VAR_LANG_MATCH", "Match");
define("VAR_LANG_RESULTAT", "R&eacute;sultat");
define("VAR_LANG_HEURE_SUISSE", "Heure suisse");

// liste annuaire et aussi les genérales
define("VAR_LANG_DBD_ARBITRE", "Arbitre");
define("VAR_LANG_DBD_AUTRE_FONCTION", "Autre fonction");
define("VAR_LANG_DBD_CHTB", "Tchouk Up");
define("VAR_LANG_DBD_CIVILITE", "Civilit&eacute;");
define("VAR_LANG_DBD_FORMATION", "Formation");
define("VAR_LANG_DBD_LANGUE", "Langue");
define("VAR_LANG_DBD_PAYS", "Pays");
define("VAR_LANG_DBD_STATUS", "Status");
define("VAR_LANG_DBD_ORIGINE_ADRESSE", "Origne de l'adresse");
define("VAR_LANG_DBD_RAISON_SOCIALE", "Raison sociale");
define("VAR_LANG_DBD_MEDIA_TYPE", "Type de m&eacute;dia");
define("VAR_LANG_DBD_MEDIA_CANTON", "Média canton/&eacute;tat");

define("VAR_LANG_CLUB_FSTB", "Club");

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
define("VAR_LANG_CHTB_DOWNLOAD", "Consultez les archives du tchoukup");

define("VAR_LANG_ADMINISTRATION", "Administration");

//accueil
define("VAR_LANG_DERNIERES_NEWS", "Dernières news");
define("VAR_LANG_PROCHAINS_EVENEMENTS", "Prochains évenements");
define("VAR_LANG_PROCHAINS_MATCHS", "Prochains matchs");

//juniors
define("VAR_LANG_ANNEE_DE_NAISSANCE", "Ann&eacute;e de naissance");
define("VAR_LANG_AGE_EN", "Âge en");
define("VAR_LANG_JEUNESSESPORT", "Jeunesse+Sport");
define("VAR_LANG_J+S", "J+S");
