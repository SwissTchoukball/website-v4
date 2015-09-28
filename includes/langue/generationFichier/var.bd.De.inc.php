<?php
//MENU_WEB
$VAR_TAB_MENU_WEB = array(
array(new Menu("News","","","0","100","1","0","0",""), array(new Menu("News","pages/news.inc.php","pages/news.inc.php","0","100","51","0","0",""),
new Menu("Newsletter","pages/news.letter.inc.php","pages/news.letter.inc.php","0","100","50","0","0",""))),
array(new Menu("Verband","","","0","100","2","0","0",""), array(new Menu("Komitee","pages/comite.inc.php","pages/comite.inc.php","0","100","14","1","1",""),
new Menu("Kommissionen","pages/commissions.inc.php","pages/commissions.inc.php","0","100","49","1","20",""),
new Menu("Klubs","pages/clubs.inc.php","pages/clubs.inc.php","0","100","15","1","2",""),
new Menu("Schiedsrichter","pages/federation.arbitre.inc.php","pages/federation.arbitre.inc.php","0","100","16","1","11",""),
new Menu("Junioren","pages/juniors.inc.php","pages/juniors.inc.php","0","100","17","1","12",""),
new Menu("Suisse tchoukball","pages/chtb.download.inc.php","pages/chtb.download.inc.php","0","100","55","0","0",""))),
array(new Menu("Tchoukball","","","0","100","3","0","0",""), array(new Menu("Vorstellung","pages/presentation.inc.php","pages/presentation.inc.php","0","100","18","1","13",""),
new Menu("Geschichte","pages/historique.inc.php","pages/historique.inc.php","0","100","19","1","3",""),
new Menu("Regeln","pages/regles.inc.php","pages/regles.inc.php","0","100","20","1","4",""),
new Menu("Charta","pages/charte.inc.php","pages/charte.inc.php","0","100","21","1","5",""))),
array(new Menu("Kalender","pages/agenda.inc.php","pages/agenda.inc.php","0","100","4","0","0","")),
array(new Menu("Meisterschaft","","","0","100","5","0","0",""), array(new Menu("Programm","pages/programme.inc.php","pages/programme.inc.php","0","100","22","1","6",""),
new Menu("Ergebnis","pages/championnat.resultats.inc.php","pages/championnat.resultats.inc.php","0","100","23","1","6",""),
new Menu("Rangliste","pages/championnat.classement.inc.php","pages/championnat.classement.inc.php","0","100","24","1","6",""))),
array(new Menu("Ausbildung","","","0","100","6","0","0",""), array(new Menu("Schiedsrichter","pages/formation.arbitre.inc.php","pages/formation.arbitre.inc.php","0","100","25","1","11",""),
new Menu("Arbitre junior","pages/formation.arbitre.junior.inc.php","pages/formation.arbitre.junior.inc.php","0","100","53","1","11",""),
new Menu("Klubverwaltung","pages/gestionnaireDeClub.inc.php","pages/gestionnaireDeClub.inc.php","0","100","26","1","17",""),
new Menu("JS","pages/js.inc.php","pages/js.inc.php","0","100","27","1","7",""),
new Menu("Swiss Olympic","pages/swissolympic.inc.php","pages/swissolympic.inc.php","0","100","28","1","16",""))),
array(new Menu("National-Mannschaften","","","0","100","7","0","0",""), array(new Menu("Damenmannschaft","pages/femmes.inc.php","pages/femmes.inc.php","0","100","30","0","0",""),
new Menu("Herrenmannschaft","pages/hommes.inc.php","pages/hommes.inc.php","0","100","29","0","0",""),
new Menu("Internationale Schiedsrichters","pages/arbitres.inc.php","pages/arbitres.inc.php","0","100","41","0","0",""))),
array(new Menu("International","","","0","100","8","0","0",""), array(new Menu("Gen&egrave;ve 2005","pages/geneve2005.inc.php","pages/geneve2005.inc.php","0","100","54","1","20",""),
new Menu("Taiwan 2004","pages/taiwan2004.inc.php","pages/taiwan2004.inc.php","0","100","35","1","10",""),
new Menu("Italien 2003","pages/italie2003.inc.php","pages/italie2003.inc.php","0","100","34","1","9",""))),
array(new Menu("Fotos","http://www.tchoukball.ch/photos","http://www.tchoukball.ch/photos","1","100","37","1","18","")),
array(new Menu("Videos","pages/videos.inc.php","pages/videos.inc.php","0","100","36","0","18","")),
array(new Menu("Sponsoren","","","0","100","10","0","0",""), array(new Menu("Partnervorstellung","pages/presentation.sponsors.inc.php","pages/presentation.sponsors.inc.php","0","100","38","1","15",""),
new Menu("SponsorenKommission","pages/commission.sponsoring.inc.php","pages/commission.sponsoring.inc.php","0","100","39","1","15",""))),
array(new Menu("Medien","pages/media.inc.php","pages/media.inc.php","0","100","11","0","19","")),
array(new Menu("Links","pages/links.inc.php","pages/links.inc.php","0","100","12","0","0","")),
array(new Menu("Download","","","0","100","13","0","0",""), array(new Menu("Alles","pages/download.all.inc.php","pages/download.all.inc.php","0","100","48","0","0",""),
new Menu("Werbung/Vorstellung","pages/download.presentation.inc.php","pages/download.presentation.inc.php","0","100","42","0","0",""),
new Menu("Meisterschaft","pages/download.championnat.inc.php","pages/download.championnat.inc.php","0","100","43","0","0",""),
new Menu("Schiedsrichter","pages/download.arbitrage.inc.php","pages/download.arbitrage.inc.php","0","100","44","0","0",""),
new Menu("Turnier","pages/download.tournois.inc.php","pages/download.tournois.inc.php","0","100","45","0","0",""),
new Menu("Empfehlungen/Regeln","pages/download.reglements.inc.php","pages/download.reglements.inc.php","0","100","46","0","0",""),
new Menu("Denkschriften AOS","pages/download.memoireAOS.inc.php","pages/download.memoireAOS.inc.php","0","100","47","0","0",""))));
?>

<?php
//MENU_ADMIN
$VAR_TAB_MENU_ADMIN = array(
array(new Menu("Carnet d'adresses du site","","","0","10","1","0","0",""), array(new Menu("Mes infos","admin/mes.infos.inc.php","admin/mes.infos.inc.php","0","10","3","0","0",""),
new Menu("Modifier mes infos","admin/modifier.mes.infos.inc.php","admin/modifier.mes.infos.inc.php","0","10","6","0","0",""),
new Menu("Afficher le carnet","admin/afficher.carnet.inc.php","admin/afficher.carnet.inc.php","0","10","2","0","0",""),
new Menu("Ins&eacute;rer une personne","admin/inserer.contact.inc.php","admin/inserer.contact.inc.php","0","5","7","0","0",""))),
array(new Menu("Annuaire FSTB","","","0","5","35","0","0",""), array(new Menu("Afficher","admin/afficher.annuaire.inc.php","admin/afficher.annuaire.inc.php","0","5","36","0","0",""),
new Menu("Neue","admin/inserer.annuaire.inc.php","admin/inserer.annuaire.inc.php","0","5","37","0","0",""),
new Menu("Importer","admin/annuaire.importer.csv.inc.php","admin/annuaire.importer.csv.inc.php","0","5","38","0","0",""),
new Menu("Gestion des listes","admin/annuaire.gestion.liste.inc.php","admin/annuaire.gestion.liste.inc.php","0","5","39","0","0",""))),
array(new Menu("Calender","","","0","10","9","0","0",""), array(new Menu("Ins&eacute;rer &eacute;v&eacute;nement","admin/inserer.agenda.inc.php","admin/inserer.agenda.inc.php","0","5","11","0","0",""),
new Menu("Modifier &eacute;v&eacute;nement","admin/modifier.agenda.inc.php","admin/modifier.agenda.inc.php","0","5","12","0","0",""),
new Menu("Supprimer &eacute;v&eacute;nement","admin/supprimer.agenda.inc.php","admin/supprimer.agenda.inc.php","0","5","13","0","0",""),
new Menu("Importation pour outlook","admin/agenda.outlook.inc.php","admin/agenda.outlook.inc.php","0","10","20","0","0",""))),
array(new Menu("News","","","0","5","14","0","0",""), array(new Menu("Ins&eacute;rer news","admin/inserer.news.inc.php","admin/inserer.news.inc.php","0","5","16","0","0",""),
new Menu("Modifier news","admin/modifier.news.inc.php","admin/modifier.news.inc.php","0","5","17","0","0",""),
new Menu("Supprimer news","admin/supprimer.news.inc.php","admin/supprimer.news.inc.php","0","5","18","0","0",""),
new Menu("Enlever option premiere news =>","admin/deselection.toutes.premieres.news.inc.php","admin/deselection.toutes.premieres.news.inc.php","0","0","19","0","0",""))),
array(new Menu("MeisterShaft","","","0","4","24","0","0",""), array(new Menu("Insert a game","admin/inserer.championnat.match.inc.php","admin/inserer.championnat.match.inc.php","0","4","28","0","0",""),
new Menu("Modify a game","admin/modifier.championnat.match.inc.php","admin/modifier.championnat.match.inc.php","0","4","29","0","0",""),
new Menu("Remove a game","admin/supprimer.championnat.match.inc.php","admin/supprimer.championnat.match.inc.php","0","4","30","0","0",""),
new Menu("Modify classement","admin/modifier.championnat.classement.inc.php","admin/modifier.championnat.classement.inc.php","0","4","31","0","0",""),
new Menu("Nouvelle phase","admin/inserer.championnat.tour.inc.php","admin/inserer.championnat.tour.inc.php","0","4","25","0","0",""),
new Menu("Modifier phase","admin/modifier.championnat.tour.inc.php","admin/modifier.championnat.tour.inc.php","0","4","26","0","0",""),
new Menu("Supprimer phase","admin/supprimer.championnat.tour.inc.php","admin/supprimer.championnat.tour.inc.php","0","4","27","0","0",""),
new Menu("Classement","pages/championnat.classement.inc.php","pages/championnat.classement.inc.php","0","4","32","0","0",""),
new Menu("resultats","pages/championnat.resultats.inc.php","pages/championnat.resultats.inc.php","0","4","34","0","0",""))),
array(new Menu("Statistique","","","0","5","8","0","0",""), array(new Menu("Statistiques globales","admin/statistique.inc.php","admin/statistique.inc.php","0","5","21","0","0","creerGraphe"),
new Menu("Statistique login surf","admin/statistique.personne.surf.inc.php","admin/statistique.personne.surf.inc.php","0","0","22","0","0",""),
new Menu("Statistique login admin","admin/statistique.personne.admin.inc.php","admin/statistique.personne.admin.inc.php","0","0","23","0","0",""))),
array(new Menu("Translate","","","0","7","40","0","0",""), array(new Menu("Page body","admin/translate.body.inc.php","admin/translate.body.inc.php","0","7","41","0","0",""),
new Menu("Others data","admin/translate.choix.table.champ.inc.php","admin/translate.choix.table.champ.inc.php","0","7","42","0","0",""))),
array(new Menu("Reg&eacute;n&eacute;rer fichiers de langues =>","http://www.tchoukball.ch/includes/langue/generationFichier/gen.fichier.bd.php","http://www.tchoukball.ch/includes/langue/generationFichier/gen.fichier.bd.php","1","0","5","0","0","")),
array(new Menu("test","pages/chtb.download.inc.php","pages/chtb.download.inc.php","0","0","33","0","0","")));
?>

<?php
//NEWSLETTER
$VAR_TAB_NEWSLETTER = array(
"Vous voulez rester inform&eacute; de la progression du tchoukball ?<br />
Vous voulez connaître les derniers r&eacute;sultats des matchs internationaux ?<br />
Vous voulez avoir des informations sur le travail des diverses commissions ?",
"Inscrivez-vous au bulletin d’information de la FSTB !",
"Il s’agit d’un email envoy&eacute; avec une cadence de 3 &agrave; 8 fois par mois contenant toutes les informations n&eacute;cessaire pour se maintenir au courant de ce qui se passe sur la plan&egrave;te tchoukball.",
"Inscription",
"Entrez votre adresse email ci-dessous et appuyez sur le bouton « inscription » pour recevoir le bulletin de la FSTB en fran&ccedil;ais. Vous recevrez dans les minutes suivantes un email confirmant votre inscription.",
"Ce service de newsletter fonctionnera dans un premier temps &agrave; titre provisoire sp&eacute;cialement pour vous maintenir inform&eacute; des r&eacute;sultats des &eacute;quipes suisses durant le tournoi international.<br />
Cependant si le service donne pleines satisfactions  il sera introduit &agrave; la rentr&eacute;e pour une dur&eacute;e ind&eacute;termin&eacute;e.",
"D&eacute;sinscription",
"Pour vous d&eacute;sinscrire du bulletin d’information de la FSTB, il suffit d’inscrire votre adresse email ci-dessous et d’appuyez sur le bouton « d&eacute;sincription ». Apr&egrave;s avoir re&ccedil;u un email de confirmation, vous ne recevez plus le bulletin de la FSTB.");
?>

<?php
//historique
$VAR_TAB_HISTORIQUE = array(
"Der Tchoukball ist durch Überlegungen und Forschungen in den 60. Jahren von Dr. Hermann Brandt geboren worden. Er setzt sich aus einer wissenschaftlichen kritischen Studie der Volkssportarten mit zwei Mannschaften zusammen. Dr. Brandt, ein Genfer Arzt, hat w&auml;hrend seiner beruflichen T&auml;tigkeit eine grosse Anzahl von Athleten betreut, die durch ihre sportliche Aus&uuml;bung mehr oder weniger schlimm verletzt waren.",
"Er stellte fest, dass diese Verletzungen von den physiologisch unangepassten Bewegungen und von den vielen Formen der Angriffe, die in einigen Sportarten vorhanden sind, verursacht worden sind. Seine Analyse hat seine Beunruhigungen bez&uuml;glich der erzieherischen Werte der modernen Sportarten verst&auml;rkt, denn diese sollen nicht zwangsl&auml;ufig zu der systematischen Herausbildung von Siegern, sondernvielmehr \" zu einer g&uuml;ltigen menschliche Gesellschaft \" beitragen (Brandt.H., \"Etude scientifique des sports d'&eacute;quipe- le Tchoukball, le sport de demain\", Ed. Roulet, Gen&egrave;ve, 1971). Er entwirft folgerichtig ein neues Spiel, den Tchoukball, den er im Rahmen seiner \" Etude scientifique des sports d'&eacute;quipe \" erkl&auml;rt. Der Tchoukball setzt sich als eine Mischung von \" Pelote Basque \", Handball und Volleyball zusammen. Es handelt sich um einen Ball- und Mannschaftssport; man spielt ihn mit Hilfe von 2 reflektierenden Fl&auml;chen (die Rahmen) und ohne jeglichen k&ouml;rperlichen Angriff zwischen den Gegnern. Dieser Sport will jeden Menschen auffordern, spielerisch seinen K&ouml;rper zu bewegen, unabh&auml;ngig von Alter, Geschlecht uns athletischen F&auml;higkeiten.");
?>

<?php
//REGLES_TCHOUKBALL
$VAR_TAB_REGLES_TCHOUKBALL = array(
"Ein spieler gewinnt einen Punkt wenn",
"der Ball, nach dem Abprall auf dem Rahmen, den Boden ber&uuml;hrt, ohne dass ihn ein Gegner auff&auml;ngt.",
"Ein Spieler gibt der gegnerischen Mannschaft einen Punkt wenn :",
"1. Er den Rahmen verpasst<br />
2. Der Ball, nach dem Abprall auf dem Rahmen, ausserhalb des Feldes den Boden ber&uuml;hrt<br />
3. Der Ball, vor oder nach dem Abprall, in die verbotene Zone f&auml;llt.<br />
4. Der Ball nach dem Abprall ihn wieder trifft.",
"Ein Spieler macht einen Fehler wenn :<br />
",
"1. er mit dem Ball dribbelt, auf dem Boden oder in der Luft<br />
2. er mehr als drei Schritte mit dem Ball in der Hand macht (jedoch z&auml;hlt die Ballannahme mit zwei F&uuml;ssen am Boden nur als ein Schritt)<br />
3. er einen vierten Pass f&uuml;r seine Mannschaft macht (nur der Anstoss gilt nicht als ein Pass)<br />
",
"4. er den Ball w&auml;hrend des Spiels fallen l&auml;sst<br />
5. er einen Gegner w&auml;hrend des Spiels st&ouml;rt<br />
6. er im Ballbesitz die verbotene Zone betritt (er muss auch sonst diese Zone so schnell wie m&ouml;glich verlassen, um die anderen Spieler nicht zu st&ouml;ren)",
"7. Der Ball ihn unter dem Knie ber&uuml;hrt<br />
8. Er mit dem Ball sich ausserhalb des Feldes bewegt <br />
9. Er absichtlich den Ball auf einen Gegner wirft<br />
10. Er den Ball auff&auml;ngt, den ein Partner auf den Rahmen geworfen hat <br />
11. Er den Ball so auf den Rahmen wirft, dass er nicht das Netz sondern den wirklichen Rahmen ber&uuml;hrt und dadurch eine falsche Flugbahn nimmt. Bei diesem Fehler f&auml;ngt das Spiel wieder an, wo der Ball den Boden erreicht hat.");
?>

<?php
//CHARTE_TCHOUKBALL
$VAR_TAB_CHARTE_TCHOUKBALL = array(
"Charte du Tchoukball ",
"1. Le jeu exclut toute recherche de prestige, tant personnel que collectif. <br><br>
Sur le plan personnel, l'attitude du joueur implique le respect de tout autre joueur, adversaire ou co&eacute;quipier, qu'il soit plus fort ou plus faible. <br><br>
Le jeu &eacute;tant ouvert &agrave; toutes les capacit&eacute;s, inn&eacute;es ou acquises, on rencontrera fatalement tous les nivaux qualitatifs de joueurs; le respect ou la consid&eacute;ration, dus &agrave; chacun, oblige tout joueur &agrave; adapter son propre comportement technique et tactique aux circonstances du moment. <br><br>
Sur le plan collectif, un r&eacute;sultat, quel qu'il soit, n'engage jamais la r&eacute;putation de qui que ce soit et surtout ne donne droit &agrave; aucun genre de \"sectarisme\". D'une victoire on peut retirer du plaisir, voire de la joie, mais jamais une satisfaction d'orgueil. La joie de gagner est un encouragement, l'orgueil de la victoire comporte en germe une lutte de prestige que nous condamnons comme source de conflits entre humains, &agrave; tous les degr&eacute;s. ",
"2. Le jeu comporte un \"don de soi\" permanent : d'abord une surveillance constante des circuits de la balle, ensuite l'observation objective et sympathisante des joueurs. Le don de soi est la participation subjective aux &eacute;v&eacute;nements; il a pour r&eacute;sultat de \"mêler\" les personnalit&eacute;s &agrave; la confrontation r&eacute;ciproque des r&eacute;actions au jeu : ",
"a) Le sens du rendement collectif de l'&eacute;quipe : il soude les co&eacute;quipiers les uns aux autres; il apprend &agrave; estimer, &agrave; appr&eacute;cier leurs valeurs; il cr&eacute;e le sentiment de l'unit&eacute; dans l'effort du petit groupe. <br><br>
b) L'assimilation des attitudes de groupe dit \"adversaire\" &agrave; qui il s'agit d'opposer un jeu opportun mais ne comportant jamais et &agrave; aucun degr&eacute; un sentiment d'hostilit&eacute;. <br><br>
c) Le souci majeur de tout joueur doit être la recherche du beau jeu. L'exp&eacute;rience universelle dans les sports se r&eacute;sume par l'expression courante : \"Le beau jeu appelle le beau jeu\".",
"Cette disposition d'esprit est la base de l'action sociale du Tchoukball : elle permet de s'orienter vers la perfection et d'&eacute;viter toujours l'action n&eacute;gative envers l'adversaire. <br><br>
C'est plus qu'une r&egrave;gle de jeu, c'est une r&egrave;gle de conduite permanente, composante psychique du comportement, base de la personnalit&eacute; sociale. <br><br>
L'objectif est donc la suppression des conflits dans une intention identique : l'id&eacute;e de \"fair play\" &eacute;tant d&eacute;pass&eacute;e, il ne s'agit pas de concessions faites &agrave; l'adversaire, mais d'actions communes liant les &eacute;quipes l'une &agrave; l'autre où le beau jeu de l'une appuie et rend possible le beau jeu de l'autre. ",
"3. Le jeu devient un exercice social par l'activit&eacute; physique : c'est une mise en commun des moyens d'ex&eacute;cution, le meilleur portant la responsabilit&eacute; \"d'apprendre\" aux moins bons; il n'y a pas de v&eacute;ritable championnat, mais d'une course &agrave; la \"comp&eacute;tence\". <br><br>
Lorsque l'on dit que \"les meilleurs gagnent\", il faut sous-entendre qu'être le meilleur\" s'acquiert par la qualit&eacute; de la pr&eacute;paration. Il est bon alors que les r&eacute;sultats r&eacute;compensent la peine que se donnent les joueurs d'abord individuellement, puis dans un effort collectif. <br><br>
Dans cette limite l&agrave;, une victoire peut et doit entraîner une satisfaction normale s'accompagnant du respect de l'adversaire. <br><br>
La victoire doit produire chez cet adversaire une stimulation (envie d'en faire autant) et non un sentiment d'&eacute;crasement. Les gagnants doivent s'employer &agrave; produire cette impression. Une satisfaction saine des vainqueurs est une mani&egrave;re de tendre la main aux perdants pour les inciter &agrave; poursuivre un entraînement efficace. <br><br>
Pour ces raisons, la notion de \"champion\" doit c&eacute;der la place &agrave; une notion plus modeste et mieux adapt&eacute;e : celle de \"gagnant\". <br><br>
Jouer pour se perfectionner : c'est le sentiment que toute activit&eacute; de jeu doit comporter et d&eacute;velopper. C'est vers cette conclusion que doit tendre la pratique du Tchoukball, de la plus petite rencontre amicale &agrave; la plus s&eacute;rieuse confrontation \"au sommet\". ");
?>

<?php
//ARBITRE_FEDERATION
$VAR_TAB_ARBITRE_FEDERATION = array(
"Der Bund, Schiedsrichterrubrik",
"Ohne Regeln kein Spiel ! ",
"Regeln sind ganz allgemein ein grundlegender Bestandteil des Sports, und speziell des Tchoukballs. Der Schiedsrichter hat jedoch eine noch wichtigere Rolle zu spielen: er gibt den Spielern und dem Trainer einen wichtigen „feed-back“ &uuml;ber ihre Leistung. Er hilf also allen, Fortschritte zu machen. Dazu sind die Schiedsrichter unerl&auml;sslich, sogar wenn die Spieler ein respektvolles Verhalten zeigen und fair play praktizieren.",
"Die Schweizer Schiedsrichter werden von der Schiedsrichterkommission kontrolliert, die von dem FSTB-Spielbezirk abh&auml;ngig ist. Der wichtigste Auftrag der Kommission besteht in folgenden Punkten:",
"<a href=\"?lien=25\">- den Schiedsrichtern eine Ausbildung zu geben und sie langfristig zu unterst&uuml;tzen;</a><br />
- die Klubs darin zu f&ouml;rdern, Schiedsrichter auszubilden; <br />
- ein qualitativ hochstehendes Schiedsrichteramt in der <a href=\"?lien=5\">Schweizer Meisterschaft</a> und in den Klubturnieren zu sichern;<br />
- eine Kontaktzone zu bilden. <br />
<br />
F&uuml;r alle weitere Fragen &uuml;ber Regeln oder Schiedsrichter wenden Sie sich bitte an");
?>

<?php
//JUNIOR_FEDERATION
$VAR_TAB_JUNIOR_FEDERATION = array(
"Um den Kindern und den Jugendlichen mehr und vielseitige Aktivit&auml;ten und eine bessere Betreuung anzubieten, hat der Schweizer Tchoukballbund eine Junioren-Kommission gegr&uuml;ndet, die f&uuml;r die Entwicklung des Tchoukballs bei den Jugendlichen verantwortlich ist. Nach er Vorschrift f&uuml;r Juniorentchoukball sind die wichtigsten Aufgaben dieser Kommission:",
"- den Kindern die M&ouml;glichkeit zu geben, mit anderen Jugendlichen derselben Alterskategorie zu spielen<br />
- den Nachwuchs auszubilden und so dem Schweizer Tchoukball eine gute Basis zu geben<br />
- den Grundsatz \"Ein Sport f&uuml;r alle\" zu verwirklichen<br />
- die Tchoukballcharta f&uuml;r <a href=\"http://wwwedu.ge.ch/co/epco/charte.html\" target=\"_blank\">Kinderrechte im Sport</a> zu f&ouml;rdern",
"Dazu bieten der Schweizer Bund und die Klubs verschiedene Aktivit&auml;ten an, so etwa Juniorentrainingslager f&uuml;r M15 (unter 15 Jahre alt) und M18 (unter 18 Jahre alt), oder Juniorenturniere f&uuml;r M15 und M12 (unter 15,12 Jahre alt).",
"Die Juniorenkommission arbeitet an folgenden Punkten :",
"- sie definiert eine allgemeine Strategie f&uuml;r die Entwicklung des Tchoukballs bei Jugendlichen.<br />
- sie organisiert Trainingslager und Spiele f&uuml;r M15 und M18 Spieler. <br />
- sie koordiniert die Aktivit&auml;ten f&uuml;r Junioren w&auml;hrend des ganzen Jahres (Turniere, Trainingslager, Tchoukballlager, Spiele...)<br />
- sie unterst&uuml;tzt die Klubs dabei, Trainingslager und Turniere f&uuml;r Junioren zu organisieren.",
"F&uuml;r weitere Information &uuml;ber Junioren im Tchoukball, wenden Sie sich bitte an die Juniorenkommission.");
?>

<?php
//PRESENTATION_TCHOUKBALL
$VAR_TAB_PRESENTATION_TCHOUKBALL = array(
"Taktisch",
"Tchoukball ist ein richtiger Mannschaftssport !<br />
Tchoukball ist eine Mischung zwischen Volleyball, Handball und Squash;er f&ouml;rdert eine richtige Koh&auml;sion im Herzen der Mannschaft. Alle Spielerinnenund Spieler auf dem Spielfeld erg&auml;nzen sich. Es ist unm&ouml;glich, ein Spiel zugewinnen wenn man nur auf die technischen Heldentaten von einigen Spielern z&auml;hlt.",
"Zug&auml;nglich",
"Nur 5 Minuten Erkl&auml;rungen gen&uuml;gen und es kann gleich losgehen!<br />
F&uuml;r den Anf&auml;nger ist Tchoukball von den ersten Minuten an spielbar. Die Grundbewegungen sind einfach und nat&uuml;rlich. Eine mittlere k&ouml;rperliche Kondition gen&uuml;gt und die Grundregeln sind leicht zu verstehen. Da niemand den Anderen ber&uuml;hren darf, ist es leicht mit gemischten Mannschaften zu spielen. Beim Spielanfang braucht man kein besonderes Material. Es ist ein absolut idealer Sport f&uuml;r jede Person, die eine k&ouml;rperliche Aktivit&auml;t in einer Mannschaft wieder aufnehmen will. Dennoch wird der Wettkampf beim Tchoukball besonders intensiv, erprobend und vollst&auml;ndig. Die gef&ouml;rderten technischen Bewegungen werden immer mehr komplex und eindrucksvoll.",
"Intensiv",
"Dieser Sport begeistert, der ganze K&ouml;rper wird angesprochen und jeder kann sich verausgaben. Tchoukball ist ein besonders dynamischer Sport. Ohne Verhinderungen, mit den n&ouml;tigen technischen Bewegungen, dem anhaltendem Rhythmus der Begegnungen und der geforderten Intelligenz des Spieles ist er angenehm zu spielen.",
"Fair",
"Eine besondere Philosophie.<br />
Durch die Bef&uuml;rwortung des Fairness bedeutet das Spielen \"mit dem Anderen spielen \" und nicht \" gegen den Anderen spielen \" : jeder Angriff und jede Behinderung der Gegenmannschaft ist verboten. Gewinnen heisst : \" besser spielen als der Andere \" und nicht \" das Spiel des Anderen behindern \". Durch angepasste Regeln will der Tchoukball das Gegenspiel und den unn&ouml;tigen Angriff verhindern. Jede Behinderung oder Verhinderung der Spieler ist verboten.",
"Die Grundregeln",
"- 1 Spielfeld 10* 20m. und ein Ball, wie f&uuml;r den Handball<br />
- 2 Mannschaften, mit je 7 Spielerinnen/Spieler<br />
- 2 Tchoukball Rahmen (geneigte Trampolins) stehen auf jeder Seite des Spielfeldes.",
"Nun kann es losgehen !",
"- Die Mannschaft, die angreift, wirft sich den Ball zu und schiesst den Ball auf das Trampolin. In diesem Moment muss die andere Mannschaft versuchen, den Ball zu fangen, bevor er auf den Boden f&auml;llt. Wenn es ihr gelingt, geht das Spiel weiter. Jedoch, wenn es ihr nicht gelingt, gewinnt die angreifende Mannschaft einen Punkt. <br />
Weitere Aukunft bekommen Sie unter der der Nummer 078 / 759 25 34 oder erika.mesmer@tchoukball.ch");
?>

<?php
//FORMATION_ARBITRE
$VAR_TAB_FORMATION_ARBITRE = array(
"Ausbildung der Schiedsrichter",
"Die Ausbildung der Schiedsrichter ist eine der <a href=\"?lien=16\">Aufgaben</a> der Schiedsrichter Kommission der FSTB.",
"In der Schweiz gibt es zwei Stufen f&uuml;r Schiedsrichter: Die Schiedsrichter 1 d&uuml;rfen in Klubturnieren oder in der Schweizer Meisterschaft ein Spiel leiten, w&auml;hrend die Schiedsrichter 2 sich zus&auml;tzlich um einen internationalen Schiedsrichtertitel bewerben k&ouml;nnen. Sie k&ouml;nnen auch an der Ausbildung der Schiedsrichter 1 teilnehmen. Eine Liste der Schiedsrichter wird regelm&auml;ssig von der Schiedsrichter Kommission auf den neusten Stand gebracht.",
"Jedes Jahr organisiert die Kommission einen Ausbildungskurs f&uuml;r Schiedsrichter 1. Dieser Kurs dauert zwei Tage, zwischen 4 Monate Praxis liegen. W&auml;hrend dieser Zeit werden die Kandidaten von Schiedsrichtern der Stufe 2 &uuml;ber pr&uuml;ft, die ihnen Ratschl&auml;ge  erteilen und ihr Ausbildungsniveau beurteilen. <br />
Der Ausbildungskurs ist f&uuml;r alle Spieler&uuml;ber 18 Jahre offen, die mit den Regeln vertraut sind und einige Spielerfahrung besitzen. Das Einschreibungsformular erlaubt, sich f&uuml;r den n&auml;chsten Kurs anzumelden, der jedes Jahr im November beginnt. Deutschsprachige Kandidaten sind willkommen!",
"Der Kurs f&uuml;r Schiedsrichter 2 wird je nach Anfrage organisiert. F&uuml;r weitere Informationen wenden Sie sich bitte an die Kommission.");
?>

<?php
//FORMATION_ARBITRE_JUNIOR
$FORMATION_ARBITRE_JUNIOR = array(
"La formation d'arbitres juniors",
"La Commission d’arbitrage propose une formation d’arbitre junior FSTB. Cette formation s’adresse aux juniors M15 et a pour but d’initier les jeunes &agrave; l’arbitrage et de leur donner des outils pour arbitrer les matchs des juniors M12 et M15. Ce cours est &eacute;galement une porte d’entr&eacute;e possible pour le cours d’arbitre 1 FSTB (adultes).",
"Les objectifs de cette formation sont :",
"Clarifier les r&egrave;gles principales (contenu des r&egrave;gles FITB) et la technique d’arbitrage (position, gestes, sifflet)",
"Nommer des arbitres juniors M15 pour leur permettre d’arbitrer les M12 et les M15 dans les tournois de clubs.");
?>

<?php
//FORMATION_GESTIONNAIRE_CLUB
$VAR_TAB_FORMATION_GESTIONNAIRE_CLUB = array(
"Ausbildung der Klubverwalter",
"W&auml;hrend eines Tages werden die wichtigsten Punkte diskutiert, die zur effizienten Verwaltung eines Tchoukballklubs notwendig sind. Dieser Kurs ist f&uuml;r alle Personen &uuml;ber 18 Jahre zug&auml;nglich und ideal f&uuml;r die verbandes die einen neuen Klub gr&uuml;nden wollen. Dazu ist es nicht n&ouml;tig, Mitglied des Schweizerischen Tchoukballverbandes zu sein (Turnlehrer sind zum Beispiel willkommen). <br />
<br />
Je nach Jahresprogramm k&ouml;nnen auch spezifische Themen angeboten werden, etwa der Aufbau eines Juniorentrainings.",
"F&uuml;r weitere Fragen :",
"Daniel Buschbeck : 078 / 680 15 14.");
?>

<?php
//FORMATION_JS
$VAR_TAB_FORMATION_JS = array(
"Jugend und Sport Ausbildung",
"30 Jahre nach seiner Gr&uuml;ndung ist Tchoukball endlich Mitglied bei \"Jugend und Sport\" geworden.",
"Die JS Trainer",
"Dank der JS Tchoukball-Ausbildung k&ouml;nnen Tchoukball-Trainer:<br />
<br />
- eine vollst&auml;ndige Tchoukball-Trainerausbildung absolvieren, die auch Kenntnisse &uuml;ber die Entwicklung von Kindern und Jungendlichen im Sport oder Trainingsplanung enth&auml;lt. ",
"Anmerkung: Turnlehrer, die eine komplette Ausbildung f&uuml;r Tchoukball w&uuml;nschen, sind sehr willkommen. Ihnen wird ein spezielles Programm angeboten, wenn sie den JS Trainerkurs absolvieren (Manche Kurse werden zusammen mit den anderen Teilnehmern abgehalten; andere ausschliesslich f&uuml;r Turnlehrer, um sich die technischen Basisbegriffe anzweignen. Doch ist es empfehlenswert, schon einige Praxis hinter sich zu haben (Wenden Sie sich f&uuml;r weitere Fragen an Carole Greber, die f&uuml;r die Ausbildung verantwortlich ist=.",
"- an Fortbildungskursen(speziell f&uuml;r Tchoukball oder allgemein) teilnehmen. Seit 2003 kann jeder Tchoukball Trainer  Kurse &uuml;ber Coaching oder k&ouml;rperliche Fitness absolvieren (Jahresplanung).<br />
<br />
- ihren Klub in die lage versetzen, Subventionen f&uuml;r jedes Training f&uuml;r Kinder im Alter von 10 bis 20 Jahren zu bekommen.",
"Die JS Coachs",
"Der JS Coach ist ein Berater, ein Verwalter. Er kann Ihnen u.a. dabei behilflich sein, alle Aktivit&auml;ten bei JS anzumelden. Vergessen Sie nicht, ihn um Rat zu fragen! F&uuml;r weitere Informationen.",
"Fragen ?");
?>

<?php
//FORMATION_SWISSOLYMPIC
$VAR_TAB_FORMATION_SWISSOLYMPIC = array(
"Die Grundausbildung f&uuml;r Leistungstrainer bei SwissOlympic",
"Die Bundessportschule, die zum Bundesamt f&uuml;r Sport geh&ouml;rt, arbeitet mit SwissOlympic zusammen, um den Trainern eine moderne und wirksame Ausbildung anzubieten. <br />
<br />
Manche Tchoukballtrainer haben an diesem Programm bereits teilgenommen :",
"Anschliessend haben sie einen Abschlussbericht verfasst. Diese k&ouml;nnen Sie unter der Rubrik \"Download\" finden.");
?>

<?php
//COMISSION_SPONSORING
$VAR_TAB_COMISSION_SPONSORING = array(
"Sponsoren Kommission",
"F&uuml;r die Kommission verantwortlich : ",
"Alain Waser : 079 543 28 35",
"Die Sponsoren Kommission hat zwei wichtige Aufgaben :",
"Sie sucht Partner, um die Entwicklung des Tchoukballs in der Schweiz zu f&ouml;rdern. Dazu hat sie ein Konzept Vausgearbeitet, das diesen Partnern sehr interessante Leistungen f&uuml;r Sichtbarkeit anbietet.",
"Sie unterst&uuml;tzt die Klubs in ihrer Suche nach Sponsoren indem sie ihnen zum Beispiel Dokumente zur Verf&uuml;gen stellt oder mit Ratschl&auml;gen zur Seite steht. ",
"Mehrere arten von Partnerschaft werden angeboten, von einer Werbeseite im \"Suisse Tchoukball\" bis zur kompletten Partnerschaft mit der FSTB w&auml;hrend einer ganzen Saison.<br />
<br />
Weitere Informationen finden Sie in unserer Sponsorenkte. Die Insertionsliste k&ouml;nnen Sie in der Rubrik \"Download\" finden. <br />
<br />
F&uuml;r alle weiteren Fragen z&ouml;gern Sie nicht, Kontakt mit uns aufzunehmen.");
?>

<?php
//INTRO_SPONSORS
$VAR_TAB_INTRO_SPONSORS = array(
"Die folgenden Unternehmen unterst&uuml;tzen die FSTB und damit auch die Entwicklung des Tchoukballs in der Schweiz.",
"Besuchen Sie ihre Website, ein Klick reicht !");
?>

<?php
//MEDIA
$VAR_TAB_MEDIA = array(
"Informations f&uuml;r Medien",
"Verantwortlich f&uuml;r Medien :",
"Justine Guillaume : 079 / 6288426 ",
"Fichier &agrave; t&eacute;l&eacute;charger");
?>

<?php
//LIENS
$VAR_TAB_LIENS = array(
array("National",array("http://www.lausannetchoukball.ch/","Tchoukball club Lausanne - LTBC"),
array("http://www.tchoukballgeneve.ch/","Tchoukball club Gen&egrave;ve - Tchoukball club Meyrin"),
array("http://www.tchoukball-club-fribourg.ch/","Tchoukball club Fribourg"),
array("http://www.tchoukball-club-sion.ch/","Tchoukball club Sion"),
array("http://home.datacomm.ch/hveveve/tchouk_unineuch.htm","Tchoukball club uni Neuchâtel"),
array("http://www.ctbc.ch","Tchoukball Club Chavannes"),
array("http://www.tchoukballgeneve.ch","Tchoukball club Carouge"),
array("http://www.tchoukballmorges.ch","Tchoukball club Morges"),
array("http://www.delemont.ch/tchoukballade","Tchoukball club Del&eacute;mont"),
array("http://www.tchoukball-lachauxdefonds.net","Tchoukball Club La Chaux-de-Fonds")),
array("Internationaler Tchoukballbund",array("http://www.tchoukball.org","http://www.tchoukball.org")),
array("Europa",array("http://www.tchoukball.it/","Italienischer Bund"),
array("http://www.tchoukball.org.uk/","Englischer Bund"),
array("http://www.tchoukball-belgium.be","F&eacute;d&eacute;ration Belge"),
array("http://match86.fr.fm/","Mign&eacute; Auxances Tchoukball (france)"),
array("http://www.tchoukball.com/","Paris First Tchouk Ball Club"),
array("http://tchoukballove.misto.cz/","R&eacute;public Czech Tchoukball"),
array("http://www.tchoukball.at","Tchoukball en Autriche")),
array("Nordamerika",array("http://www.tchoukball.net/","U.S. TchoukBall Association"),
array("http://www.tchoukball.us/","Tchoukball.us"),
array("http://www.tchoukball.ca/","Canada Tchoukball Association")),
array("S&uuml;damerika",array("http://www.tchoukbrasil.hpg.ig.com.br/","Tchoukball brasil"),
array("","Argentina Association of Tchoukball")),
array("Asien",array("http://163.16.47.136/ROCTBA/","Republic of China Tchoukball"),
array("http://laida.lzp.ks.edu.tw/tchouk/ball/indexe.htm","Kashouing County Tchoukball Committee"),
array("http://www3.starcat.ne.jp/~tchouk/","Toyoyama tchoukball club Japon"),
array("http://www.tchouk.net/","Tachikawa Tchoukball Club")));
?>

<?php
//VIDEOS
$VAR_TAB_VIDEOS = array(
array("Spot de pub vid&eacute;o de la finale du championnat suisse de tchoukball 2003-2004 (36 sec.)","Fichier en format Divx, pour t&eacute;l&eacute;charger les codec : <a href=\"http://www.divx.com\" target=\"_blank\">www.divx.com</a>",array("spot-low.avi","Videos/spot-low.avi"),
array("spot-med.avi","Videos/spot-med.avi"),
array("spot-high.avi","Videos/spot-high.avi")),
array("Filme der schweizer Meisterschaftsfinale (Mai 2003)","Alle Videos sind gleich, nur die Qualit&auml;t ist ge&auml;ndert.",array("Video *.mov (QuickTime)","Videos/tchoukball_finale_2003.mov"),
array("Video *.wmv (Windows)","Videos/tchoukball_finale_2003.wmv"),
array("Video au format *.mpeg (Movie)","Videos/tchoukball_finale_2003_low_quality.mpg"),
array("Video *.mpeg (Movie)","Videos/tchoukball_finale_2003_high_quality.mpg")));
?>

<?php
//SPONSORS
$VAR_TAB_SPONSORS = array(
array("Unsere offiziellen Partner",array("Das Sportzentrum \"Val-de-Travers\" ist ein brandneuer Sportkomplex, Im zentrum des Kantons Neuenburg. Mit seinen zahlreichen Sportpl&auml;tzen (im Freien und &uuml;berdacht), seinem Schwimmbad und seinen modernen Zimmern ist er der ideale Ort f&uuml;r Ihre Ferienlager.","logos/logo_val_de_travers.gif","http://www.centresportif-vdt.ch"),
array("Tchoukball Promotion war der erste Partner der FSTB. Er bietet qualitativ hochstehendes Material f&uuml;r Tchoukball an,  wie Rahmen, B&auml;lle,usw. zu interessanten Preisen.","logos/tchoukball_promotion.gif","http://www.tchoukballpromotion.ch/"),
array("Petroplus, et plus sp&eacute;cialement la Raffinerie Petroplus de Crissier produit pr&egrave;s d'un quart de la demande suisse en produits p&eacute;troliers. Cette grande entreprise a choisi de s'associer au tchoukball car tout deux partagent de nombreuses valeurs semblables (respect, esprit d'&eacute;quipe).","logos/logo_petroplus.gif","http://www.petroplus.ch/French/")),
array("Sponsors",array("Rivella, la seule boisson rafraîchissante saine, naturelle qui se d&eacute;cline en trois couleurs.","logos/rivella-red.gif","http://www.rivella.ch")),
array("Partner-Organisationen",array("","logos/logo-respect.gif","http://www.lerespect.ch/"),
array("","logos/logo_js.gif","http://www.jeunesseetsport.ch/")));
?>

