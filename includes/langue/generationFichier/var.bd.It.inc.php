<?php
//MENU_WEB
$VAR_TAB_MENU_WEB = array(
array(new Menu("","","","0","100","1","0","0",""), array(new Menu("","pages/news.inc.php","pages/news.inc.php","0","100","51","0","0",""),
new Menu("","pages/news.letter.inc.php","pages/news.letter.inc.php","0","100","50","0","0",""))),
array(new Menu("","","","0","100","2","0","0",""), array(new Menu("","pages/comite.inc.php","pages/comite.inc.php","0","100","14","1","1",""),
new Menu("","pages/commissions.inc.php","pages/commissions.inc.php","0","100","49","1","20",""),
new Menu("","pages/clubs.inc.php","pages/clubs.inc.php","0","100","15","1","2",""),
new Menu("","pages/federation.arbitre.inc.php","pages/federation.arbitre.inc.php","0","100","16","1","11",""),
new Menu("","pages/juniors.inc.php","pages/juniors.inc.php","0","100","17","1","12",""))),
array(new Menu("","","","0","100","3","0","0",""), array(new Menu("","pages/presentation.inc.php","pages/presentation.inc.php","0","100","18","1","13",""),
new Menu("","pages/historique.inc.php","pages/historique.inc.php","0","100","19","1","3",""),
new Menu("","pages/regles.inc.php","pages/regles.inc.php","0","100","20","1","4",""),
new Menu("","pages/charte.inc.php","pages/charte.inc.php","0","100","21","1","5",""))),
array(new Menu("","pages/agenda.inc.php","pages/agenda.inc.php","0","100","4","0","0","")),
array(new Menu("","","","0","100","5","0","0",""), array(new Menu("","pages/programme.inc.php","pages/programme.inc.php","0","100","22","1","6",""),
new Menu("","pages/championnat.resultats.inc.php","pages/championnat.resultats.inc.php","0","100","23","1","6",""),
new Menu("","pages/championnat.classement.inc.php","pages/championnat.classement.inc.php","0","100","24","1","6",""))),
array(new Menu("","","","0","100","6","0","0",""), array(new Menu("","pages/formation.arbitre.inc.php","pages/formation.arbitre.inc.php","0","100","25","1","11",""),
new Menu("","pages/formation.arbitre.junior.inc.php","pages/formation.arbitre.junior.inc.php","0","100","53","1","11",""),
new Menu("","pages/gestionnaireDeClub.inc.php","pages/gestionnaireDeClub.inc.php","0","100","26","1","17",""),
new Menu("","pages/js.inc.php","pages/js.inc.php","0","100","27","1","7",""),
new Menu("","pages/swissolympic.inc.php","pages/swissolympic.inc.php","0","100","28","1","16",""))),
array(new Menu("","","","0","100","7","0","0",""), array(new Menu("","pages/femmes.inc.php","pages/femmes.inc.php","0","100","30","0","0",""),
new Menu("","pages/hommes.inc.php","pages/hommes.inc.php","0","100","29","0","0",""),
new Menu("","pages/arbitres.inc.php","pages/arbitres.inc.php","0","100","41","0","0",""))),
array(new Menu("","","","0","100","8","0","0",""), array(new Menu("","pages/geneve2005.inc.php","pages/geneve2005.inc.php","0","100","54","1","20",""),
new Menu("","pages/taiwan2004.inc.php","pages/taiwan2004.inc.php","0","100","35","1","10",""),
new Menu("","pages/italie2003.inc.php","pages/italie2003.inc.php","0","100","34","1","9",""))),
array(new Menu("","http://www.tchoukball.ch/photos","http://www.tchoukball.ch/photos","1","100","37","1","18","")),
array(new Menu("","pages/videos.inc.php","pages/videos.inc.php","0","100","36","0","18","")),
array(new Menu("","","","0","100","10","0","0",""), array(new Menu("","pages/presentation.sponsors.inc.php","pages/presentation.sponsors.inc.php","0","100","38","1","15",""),
new Menu("","pages/commission.sponsoring.inc.php","pages/commission.sponsoring.inc.php","0","100","39","1","15",""))),
array(new Menu("","pages/media.inc.php","pages/media.inc.php","0","100","11","0","19","")),
array(new Menu("","pages/links.inc.php","pages/links.inc.php","0","100","12","0","0","")),
array(new Menu("","","","0","100","13","0","0",""), array(new Menu("","pages/download.all.inc.php","pages/download.all.inc.php","0","100","48","0","0",""),
new Menu("","pages/download.presentation.inc.php","pages/download.presentation.inc.php","0","100","42","0","0",""),
new Menu("","pages/download.championnat.inc.php","pages/download.championnat.inc.php","0","100","43","0","0",""),
new Menu("","pages/download.arbitrage.inc.php","pages/download.arbitrage.inc.php","0","100","44","0","0",""),
new Menu("","pages/download.tournois.inc.php","pages/download.tournois.inc.php","0","100","45","0","0",""),
new Menu("","pages/download.reglements.inc.php","pages/download.reglements.inc.php","0","100","46","0","0",""),
new Menu("","pages/download.memoireAOS.inc.php","pages/download.memoireAOS.inc.php","0","100","47","0","0",""))));
?>

<?php
//MENU_ADMIN
$VAR_TAB_MENU_ADMIN = array(
array(new Menu("","","","0","10","1","0","0",""), array(new Menu("","admin/mes.infos.inc.php","admin/mes.infos.inc.php","0","10","3","0","0",""),
new Menu("","admin/modifier.mes.infos.inc.php","admin/modifier.mes.infos.inc.php","0","10","6","0","0",""),
new Menu("","admin/afficher.carnet.inc.php","admin/afficher.carnet.inc.php","0","10","2","0","0",""),
new Menu("","admin/inserer.contact.inc.php","admin/inserer.contact.inc.php","0","5","7","0","0",""))),
array(new Menu("","","","0","5","35","0","0",""), array(new Menu("","admin/afficher.annuaire.inc.php","admin/afficher.annuaire.inc.php","0","5","36","0","0",""),
new Menu("","admin/inserer.annuaire.inc.php","admin/inserer.annuaire.inc.php","0","5","37","0","0",""),
new Menu("","admin/annuaire.importer.csv.inc.php","admin/annuaire.importer.csv.inc.php","0","5","38","0","0",""),
new Menu("","admin/annuaire.gestion.liste.inc.php","admin/annuaire.gestion.liste.inc.php","0","5","39","0","0",""))),
array(new Menu("","","","0","10","9","0","0",""), array(new Menu("","admin/inserer.agenda.inc.php","admin/inserer.agenda.inc.php","0","5","11","0","0",""),
new Menu("","admin/modifier.agenda.inc.php","admin/modifier.agenda.inc.php","0","5","12","0","0",""),
new Menu("","admin/supprimer.agenda.inc.php","admin/supprimer.agenda.inc.php","0","5","13","0","0",""),
new Menu("","admin/agenda.outlook.inc.php","admin/agenda.outlook.inc.php","0","10","20","0","0",""))),
array(new Menu("","","","0","5","14","0","0",""), array(new Menu("","admin/inserer.news.inc.php","admin/inserer.news.inc.php","0","5","16","0","0",""),
new Menu("","admin/modifier.news.inc.php","admin/modifier.news.inc.php","0","5","17","0","0",""),
new Menu("","admin/supprimer.news.inc.php","admin/supprimer.news.inc.php","0","5","18","0","0",""),
new Menu("","admin/deselection.toutes.premieres.news.inc.php","admin/deselection.toutes.premieres.news.inc.php","0","0","19","0","0",""))),
array(new Menu("","","","0","4","24","0","0",""), array(new Menu("","admin/inserer.championnat.match.inc.php","admin/inserer.championnat.match.inc.php","0","4","28","0","0",""),
new Menu("","admin/modifier.championnat.match.inc.php","admin/modifier.championnat.match.inc.php","0","4","29","0","0",""),
new Menu("","admin/supprimer.championnat.match.inc.php","admin/supprimer.championnat.match.inc.php","0","4","30","0","0",""),
new Menu("","admin/modifier.championnat.classement.inc.php","admin/modifier.championnat.classement.inc.php","0","4","31","0","0",""),
new Menu("","admin/inserer.championnat.tour.inc.php","admin/inserer.championnat.tour.inc.php","0","4","25","0","0",""),
new Menu("","admin/modifier.championnat.tour.inc.php","admin/modifier.championnat.tour.inc.php","0","4","26","0","0",""),
new Menu("","admin/supprimer.championnat.tour.inc.php","admin/supprimer.championnat.tour.inc.php","0","4","27","0","0",""),
new Menu("","pages/championnat.classement.inc.php","pages/championnat.classement.inc.php","0","4","32","0","0",""),
new Menu("","pages/championnat.resultats.inc.php","pages/championnat.resultats.inc.php","0","4","34","0","0",""))),
array(new Menu("","","","0","5","8","0","0",""), array(new Menu("","admin/statistique.inc.php","admin/statistique.inc.php","0","5","21","0","0","creerGraphe"),
new Menu("","admin/statistique.personne.surf.inc.php","admin/statistique.personne.surf.inc.php","0","0","22","0","0",""),
new Menu("","admin/statistique.personne.admin.inc.php","admin/statistique.personne.admin.inc.php","0","0","23","0","0",""))),
array(new Menu("","","","0","7","40","0","0",""), array(new Menu("","admin/translate.body.inc.php","admin/translate.body.inc.php","0","7","41","0","0",""),
new Menu("","admin/translate.choix.table.champ.inc.php","admin/translate.choix.table.champ.inc.php","0","0","42","0","0",""))),
array(new Menu("","http://www.tchoukball.ch/includes/langue/generationFichier/gen.fichier.bd.php","http://www.tchoukball.ch/includes/langue/generationFichier/gen.fichier.bd.php","1","0","5","0","0","")),
array(new Menu("","pages/testnews.inc.php","pages/testnews.inc.php","0","0","33","0","0","")));
?>

<?php
//NEWSLETTER
$VAR_TAB_NEWSLETTER = array(
"",
"",
"",
"",
"",
"",
"",
"");
?>

<?php
//historique
$VAR_TAB_HISTORIQUE = array(
"",
"");
?>

<?php
//REGLES_TCHOUKBALL
$VAR_TAB_REGLES_TCHOUKBALL = array(
"",
"",
"",
"",
"",
"",
"",
"");
?>

<?php
//CHARTE_TCHOUKBALL
$VAR_TAB_CHARTE_TCHOUKBALL = array(
"",
"",
"",
"",
"",
"");
?>

<?php
//ARBITRE_FEDERATION
$VAR_TAB_ARBITRE_FEDERATION = array(
"",
"",
"",
"",
"");
?>

<?php
//JUNIOR_FEDERATION
$VAR_TAB_JUNIOR_FEDERATION = array(
"",
"",
"",
"",
"",
"");
?>

<?php
//PRESENTATION_TCHOUKBALL
$VAR_TAB_PRESENTATION_TCHOUKBALL = array(
"",
"",
"",
"",
"",
"",
"",
"",
"",
"",
"",
"");
?>

<?php
//FORMATION_ARBITRE
$VAR_TAB_FORMATION_ARBITRE = array(
"",
"",
"",
"",
"");
?>

<?php
//FORMATION_ARBITRE_JUNIOR
$FORMATION_ARBITRE_JUNIOR = array(
"",
"",
"",
"",
"");
?>

<?php
//FORMATION_GESTIONNAIRE_CLUB
$VAR_TAB_FORMATION_GESTIONNAIRE_CLUB = array(
"",
"",
"",
"");
?>

<?php
//FORMATION_JS
$VAR_TAB_FORMATION_JS = array(
"",
"",
"",
"",
"",
"",
"",
"",
"");
?>

<?php
//FORMATION_SWISSOLYMPIC
$VAR_TAB_FORMATION_SWISSOLYMPIC = array(
"",
"",
"");
?>

<?php
//COMISSION_SPONSORING
$VAR_TAB_COMISSION_SPONSORING = array(
"",
"",
"",
"",
"",
"",
"");
?>

<?php
//INTRO_SPONSORS
$VAR_TAB_INTRO_SPONSORS = array(
"",
"");
?>

<?php
//MEDIA
$VAR_TAB_MEDIA = array(
"",
"",
"",
"");
?>

<?php
//LIENS
$VAR_TAB_LIENS = array(
array("",array("http://www.lausannetchoukball.ch/",""),
array("http://www.tchoukballgeneve.ch/",""),
array("http://www.tchoukball-club-fribourg.ch/",""),
array("http://www.tchoukball-club-sion.ch/",""),
array("http://home.datacomm.ch/hveveve/tchouk_unineuch.htm",""),
array("http://www.ctbc.ch",""),
array("http://www.tchoukballgeneve.ch",""),
array("http://www.tchoukballmorges.ch",""),
array("http://www.delemont.ch/tchoukballade",""),
array("http://www.tchoukball-lachauxdefonds.net","")),
array("",array("http://www.tchoukball.org","")),
array("",array("http://www.tchoukball.it/",""),
array("http://www.tchoukball.org.uk/",""),
array("http://www.tchoukball-belgium.be",""),
array("http://match86.fr.fm/",""),
array("http://www.tchoukball.com/",""),
array("http://tchoukballove.misto.cz/",""),
array("http://www.tchoukball.at","")),
array("",array("http://www.tchoukball.net/",""),
array("http://www.tchoukball.us/",""),
array("http://www.tchoukball.ca/","")),
array("",array("http://www.tchoukbrasil.hpg.ig.com.br/",""),
array("","")),
array("",array("http://163.16.47.136/ROCTBA/",""),
array("http://laida.lzp.ks.edu.tw/tchouk/ball/indexe.htm",""),
array("http://www3.starcat.ne.jp/~tchouk/",""),
array("http://www.tchouk.net/","")));
?>

<?php
//VIDEOS
$VAR_TAB_VIDEOS = array(
array("","",array("","Videos/spot-low.avi"),
array("","Videos/spot-med.avi"),
array("","Videos/spot-high.avi")),
array("","",array("","Videos/tchoukball_finale_2003.mov"),
array("","Videos/tchoukball_finale_2003.wmv"),
array("","Videos/tchoukball_finale_2003_low_quality.mpg"),
array("","Videos/tchoukball_finale_2003_high_quality.mpg")));
?>

<?php
//SPONSORS
$VAR_TAB_SPONSORS = array(
array("",array("","logos/logo_val_de_travers.gif","http://www.centresportif-vdt.ch"),
array("","logos/tchoukball_promotion.gif","http://www.tchoukballpromotion.ch/"),
array("","logos/logo_petroplus.gif","http://www.petroplus.ch/French/")),
array("",array("","logos/rivella-red.gif","http://www.rivella.ch")),
array("",array("","logos/logo-respect.gif","http://www.lerespect.ch/"),
array("","logos/logo_js.gif","http://www.jeunesseetsport.ch/")));
?>

