<?php

///////////////////////////////////////////// variable d'etat.  /////////////////////////////////////////////
// pour supprimer l'acces a la partie admin... pour pouvoir utilisé la partie admin en temp que super
// utilisateur (userLevel = 0), il faut avoir un cookie d'auto connexion.
$ETAT_ADMIN_EN_MAINTENANCE = false;
// pour tout le site
$ETAT_EN_MAINTENANCE_TOTALE = false;

// afficher le programme ou afficher un texte pour dire que le championnat n'a pas encore commencé
$ETAT_PROGRAMME_CHAMPIONNAT_COMMENCE = true;

// possibilite d'inserer/modifier/supprimer directement un evenement championnat.
$ETAT_ACCESS_EVENT_CHAMPIONNAT = false;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

// images définies
define("SPACER", "/pictures/spacer.gif");
define("VAR_IMG_DRBRANDT", "/pictures/drbrandt.gif");
define("VAR_REP_IMAGE_EQUIPE_SUISSE", "/pictures/equipesSuisses/");
define("VAR_IMAGE_FLYER_PRESENTATION", "/pictures/flyer_presentation_miniature.gif");
define("VAR_IMAGE_TERRAIN_PRESENTATION", "/pictures/terrain_presentation.gif");
define("VAR_IMAGE_BANNIERE_ARBITRE", "/pictures/banniere_arbitre.gif");
define("VAR_IMAGE_LOGO_SWISSOLYMPIC", "/logos/logo_swissolympic.gif");
define("VAR_IMAGE_PHOTO_SPONSORING", "/pictures/photo_page_commission.sponsoring.jpg");
define("VAR_IMG_NEWS_LETTER_PICT", "/pictures/newsLetterPicture.jpg");
define("VAR_IMG_TAIWAN_2004", "/pictures/Taiwan2004.jpg");
define("VAR_IMG_GENEVE_2005", "/pictures/affiche_FITB_beach.jpg");
define("VAR_IMAGE_FEDERATION_JUNIORS", "/pictures/federation_juniors.jpg");
define("VAR_IMAGE_FORMATION_ARBITRE", "/pictures/formation_arbitre.jpg");
define("VAR_IMAGE_FORMATION_ARBITRE_ENSEMBLE", "/pictures/arbitre_junior_ensemble.jpg");
define("VAR_IMAGE_FORMATION_ARBITRE_FEEDBACK", "/pictures/arbitre_junior_feedback.jpg");
define("VAR_IMAGE_FORMATION_GESTIONNAIRE_CLUB", "/pictures/formation_gestionnaire_club.jpg");
define("VAR_IMAGE_FORMATION_JS", "/pictures/formation_js.jpg");
define("VAR_IMAGE_FORMATION_JS2", "/pictures/formation_js2.jpg");

define("VAR_IMAGE_RELGES_POINT_MARQUE", "/pictures/fig_point_marque.png");
define("VAR_IMAGE_RELGES_POINT_PERDU", "/pictures/fig_point_perdu.png");
define("VAR_IMAGE_RELGES_FAUTE_1", "/pictures/fig_faute_part1.png");
define("VAR_IMAGE_RELGES_FAUTE_2", "/pictures/fig_faute_part2.png");
define("VAR_IMAGE_CARTE_SUISSE", "/pictures/carte_suisse.jpg"); // A CHANGER POUR LE VRAI SERVEUR
define("VAR_IMAGE_GRAPHIQUE_STATISTIQUE", "/pictures/graphique_statistique.png");
define("VAR_IMAGE_PRESSE_TSR", "/pictures/tsr_2004-08-08.jpg");
define("VAR_IMAGE_RSS", "/pictures/rss.png");

define("PATH_DOCUMENTS", "/documents/");
define("PATH_UPLOADS", "/uploads/");
