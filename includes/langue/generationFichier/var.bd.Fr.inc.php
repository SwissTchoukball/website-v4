<?php
//MENU_WEB
$VAR_TAB_MENU_WEB = array(
array(new Menu("News","","","0","100","1","0","0",""), array(new Menu("News","pages/news.inc.php","pages/news.inc.php","0","100","51","0","0",""),
new Menu("Newsletter","pages/news.letter.inc.php","pages/news.letter.inc.php","0","100","50","0","0",""))),
array(new Menu("F&eacute;d&eacute;ration","","","0","100","2","0","0",""), array(new Menu("Comit&eacute;","pages/comite.inc.php","pages/comite.inc.php","0","100","14","1","1",""),
new Menu("Commissions","pages/commissions.inc.php","pages/commissions.inc.php","0","100","49","1","20",""),
new Menu("Clubs","pages/clubs.inc.php","pages/clubs.inc.php","0","100","15","1","2",""),
new Menu("Arbitrage","pages/federation.arbitre.inc.php","pages/federation.arbitre.inc.php","0","100","16","1","11",""),
new Menu("Juniors","pages/juniors.inc.php","pages/juniors.inc.php","0","100","17","1","12",""),
new Menu("Suisse tchoukball","pages/chtb.download.inc.php","pages/chtb.download.inc.php","0","100","55","0","0",""))),
array(new Menu("Tchoukball","","","0","100","3","0","0",""), array(new Menu("Pr&eacute;sentation","pages/presentation.inc.php","pages/presentation.inc.php","0","100","18","1","13",""),
new Menu("Historique","pages/historique.inc.php","pages/historique.inc.php","0","100","19","1","3",""),
new Menu("R&egrave;gles","pages/regles.inc.php","pages/regles.inc.php","0","100","20","1","4",""),
new Menu("Charte","pages/charte.inc.php","pages/charte.inc.php","0","100","21","1","5",""))),
array(new Menu("Agenda","pages/agenda.inc.php","pages/agenda.inc.php","0","100","4","0","0","")),
array(new Menu("Championnat","","","0","100","5","0","0",""), array(new Menu("Programme","pages/programme.inc.php","pages/programme.inc.php","0","100","22","1","6",""),
new Menu("R&eacute;sultats","pages/championnat.resultats.inc.php","pages/championnat.resultats.inc.php","0","100","23","1","6",""),
new Menu("Classement","pages/championnat.classement.inc.php","pages/championnat.classement.inc.php","0","100","24","1","6",""))),
array(new Menu("Formation","","","0","100","6","0","0",""), array(new Menu("Arbitre","pages/formation.arbitre.inc.php","pages/formation.arbitre.inc.php","0","100","25","1","11",""),
new Menu("Arbitre junior","pages/formation.arbitre.junior.inc.php","pages/formation.arbitre.junior.inc.php","0","100","53","1","11",""),
new Menu("Gestionnaire de club","pages/gestionnaireDeClub.inc.php","pages/gestionnaireDeClub.inc.php","0","100","26","1","17",""),
new Menu("JS","pages/js.inc.php","pages/js.inc.php","0","100","27","1","7",""),
new Menu("Swiss Olympic","pages/swissolympic.inc.php","pages/swissolympic.inc.php","0","100","28","1","16",""))),
array(new Menu("Equipes nationales","","","0","100","7","0","0",""), array(new Menu("Equipes f&eacute;minine","pages/femmes.inc.php","pages/femmes.inc.php","0","100","30","0","0",""),
new Menu("Equipe masculine","pages/hommes.inc.php","pages/hommes.inc.php","0","100","29","0","0",""),
new Menu("Arbitres internationaux","pages/arbitres.inc.php","pages/arbitres.inc.php","0","100","41","0","0",""))),
array(new Menu("International","","","0","100","8","0","0",""), array(new Menu("Gen&egrave;ve 2005","pages/geneve2005.inc.php","pages/geneve2005.inc.php","0","100","54","1","20",""),
new Menu("Taiwan 2004","pages/taiwan2004.inc.php","pages/taiwan2004.inc.php","0","100","35","1","10",""),
new Menu("Italie 2003","pages/italie2003.inc.php","pages/italie2003.inc.php","0","100","34","1","9",""))),
array(new Menu("Photos","http://www.tchoukball.ch/photos","http://www.tchoukball.ch/photos","1","100","37","1","18","")),
array(new Menu("Vid&eacute;os","pages/videos.inc.php","pages/videos.inc.php","0","100","36","0","18","")),
array(new Menu("Sponsors","","","0","100","10","0","0",""), array(new Menu("Pr&eacute;sentation de nos partenaires","pages/presentation.sponsors.inc.php","pages/presentation.sponsors.inc.php","0","100","38","1","15",""),
new Menu("Commission sponsoring","pages/commission.sponsoring.inc.php","pages/commission.sponsoring.inc.php","0","100","39","1","15",""))),
array(new Menu("M&eacute;dia","pages/media.inc.php","pages/media.inc.php","0","100","11","0","19","")),
array(new Menu("Links","pages/links.inc.php","pages/links.inc.php","0","100","12","0","0","")),
array(new Menu("Download","","","0","100","13","0","0",""), array(new Menu("Tous","pages/download.all.inc.php","pages/download.all.inc.php","0","100","48","0","0",""),
new Menu("Promotion/Sponsoring","pages/download.presentation.inc.php","pages/download.presentation.inc.php","0","100","42","0","0",""),
new Menu("Championnat","pages/download.championnat.inc.php","pages/download.championnat.inc.php","0","100","43","0","0",""),
new Menu("Arbitrage","pages/download.arbitrage.inc.php","pages/download.arbitrage.inc.php","0","100","44","0","0",""),
new Menu("Tournois","pages/download.tournois.inc.php","pages/download.tournois.inc.php","0","100","45","0","0",""),
new Menu("R&egrave;glements/conseils","pages/download.reglements.inc.php","pages/download.reglements.inc.php","0","100","46","0","0",""),
new Menu("M&eacute;moire AOS","pages/download.memoireAOS.inc.php","pages/download.memoireAOS.inc.php","0","100","47","0","0",""))));
?>

<?php
//MENU_ADMIN
$VAR_TAB_MENU_ADMIN = array(
array(new Menu("Carnet d'adresses du site","","","0","10","1","0","0",""), array(new Menu("Mes infos","admin/mes.infos.inc.php","admin/mes.infos.inc.php","0","10","3","0","0",""),
new Menu("Modifier mes infos","admin/modifier.mes.infos.inc.php","admin/modifier.mes.infos.inc.php","0","10","6","0","0",""),
new Menu("Afficher le carnet","admin/afficher.carnet.inc.php","admin/afficher.carnet.inc.php","0","10","2","0","0",""),
new Menu("Ins&eacute;rer une personne","admin/inserer.contact.inc.php","admin/inserer.contact.inc.php","0","5","7","0","0",""))),
array(new Menu("Annuaire FSTB","","","0","5","35","0","0",""), array(new Menu("Afficher","admin/afficher.annuaire.inc.php","admin/afficher.annuaire.inc.php","0","5","36","0","0",""),
new Menu("Nouveau","admin/inserer.annuaire.inc.php","admin/inserer.annuaire.inc.php","0","5","37","0","0",""),
new Menu("Importer","admin/annuaire.importer.csv.inc.php","admin/annuaire.importer.csv.inc.php","0","5","38","0","0",""),
new Menu("Gestion des listes","admin/annuaire.gestion.liste.inc.php","admin/annuaire.gestion.liste.inc.php","0","5","39","0","0",""))),
array(new Menu("Agenda","","","0","10","9","0","0",""), array(new Menu("Ins&eacute;rer &eacute;v&eacute;nement","admin/inserer.agenda.inc.php","admin/inserer.agenda.inc.php","0","5","11","0","0",""),
new Menu("Modifier &eacute;v&eacute;nement","admin/modifier.agenda.inc.php","admin/modifier.agenda.inc.php","0","5","12","0","0",""),
new Menu("Supprimer &eacute;v&eacute;nement","admin/supprimer.agenda.inc.php","admin/supprimer.agenda.inc.php","0","5","13","0","0",""),
new Menu("Importation pour outlook","admin/agenda.outlook.inc.php","admin/agenda.outlook.inc.php","0","10","20","0","0",""))),
array(new Menu("News","","","0","5","14","0","0",""), array(new Menu("Ins&eacute;rer news","admin/inserer.news.inc.php","admin/inserer.news.inc.php","0","5","16","0","0",""),
new Menu("Modifier news","admin/modifier.news.inc.php","admin/modifier.news.inc.php","0","5","17","0","0",""),
new Menu("Supprimer news","admin/supprimer.news.inc.php","admin/supprimer.news.inc.php","0","5","18","0","0",""),
new Menu("Enlever option premiere news =>","admin/deselection.toutes.premieres.news.inc.php","admin/deselection.toutes.premieres.news.inc.php","0","0","19","0","0",""))),
array(new Menu("Championnat","","","0","4","24","0","0",""), array(new Menu("Ins&eacute;rer un match","admin/inserer.championnat.match.inc.php","admin/inserer.championnat.match.inc.php","0","4","28","0","0",""),
new Menu("Modifier un match","admin/modifier.championnat.match.inc.php","admin/modifier.championnat.match.inc.php","0","4","29","0","0",""),
new Menu("Supprimer un match","admin/supprimer.championnat.match.inc.php","admin/supprimer.championnat.match.inc.php","0","4","30","0","0",""),
new Menu("Modifier classement","admin/modifier.championnat.classement.inc.php","admin/modifier.championnat.classement.inc.php","0","4","31","0","0",""),
new Menu("Nouvelle phase","admin/inserer.championnat.tour.inc.php","admin/inserer.championnat.tour.inc.php","0","4","25","0","0",""),
new Menu("Modifier phase","admin/modifier.championnat.tour.inc.php","admin/modifier.championnat.tour.inc.php","0","4","26","0","0",""),
new Menu("Supprimer phase","admin/supprimer.championnat.tour.inc.php","admin/supprimer.championnat.tour.inc.php","0","4","27","0","0",""),
new Menu("Classement","pages/championnat.classement.inc.php","pages/championnat.classement.inc.php","0","4","32","0","0",""),
new Menu("resultats","pages/championnat.resultats.inc.php","pages/championnat.resultats.inc.php","0","4","34","0","0",""))),
array(new Menu("Statistique","","","0","5","8","0","0",""), array(new Menu("Statistiques globales","admin/statistique.inc.php","admin/statistique.inc.php","0","5","21","0","0","creerGraphe"),
new Menu("Statistique login surf","admin/statistique.personne.surf.inc.php","admin/statistique.personne.surf.inc.php","0","0","22","0","0",""),
new Menu("Statistique login admin","admin/statistique.personne.admin.inc.php","admin/statistique.personne.admin.inc.php","0","0","23","0","0",""))),
array(new Menu("Traduction","","","0","7","40","0","0",""), array(new Menu("Corps de page","admin/translate.body.inc.php","admin/translate.body.inc.php","0","7","41","0","0",""),
new Menu("Donn&eacute;es divers","admin/translate.choix.table.champ.inc.php","admin/translate.choix.table.champ.inc.php","0","7","42","0","0",""))),
array(new Menu("Reg&eacute;n&eacute;rer fichiers de langues =>","http://www.tchoukball.ch/includes/langue/generationFichier/gen.fichier.bd.php","http://www.tchoukball.ch/includes/langue/generationFichier/gen.fichier.bd.php","1","0","5","0","0","")),
array(new Menu("test","pages/chtb.download.inc.php","pages/chtb.download.inc.php","0","0","33","0","0","")));
?>

<?php
//NEWSLETTER
$VAR_TAB_NEWSLETTER = array(
"Vous voulez rester inform&eacute; de la progression du tchoukball ?<br />
Vous voulez conna�tre les derniers r&eacute;sultats des matchs internationaux ?<br />
Vous voulez avoir des informations sur le travail des diverses commissions ?",
"Inscrivez-vous au bulletin d�information de la FSTB !",
"Il s�agit d�un email envoy&eacute; avec une cadence de 3 &agrave; 8 fois par mois contenant toutes les informations n&eacute;cessaire pour se maintenir au courant de ce qui se passe sur la plan&egrave;te tchoukball.",
"Inscription",
"Entrez votre adresse email ci-dessous et appuyez sur le bouton � inscription � pour recevoir le bulletin de la FSTB en fran&ccedil;ais. Vous recevrez dans les minutes suivantes un email confirmant votre inscription.",
"Ce service de newsletter fonctionnera dans un premier temps &agrave; titre provisoire sp&eacute;cialement pour vous maintenir inform&eacute; des r&eacute;sultats des &eacute;quipes suisses durant le tournoi international.<br />
Cependant si le service donne pleines satisfactions  il sera introduit &agrave; la rentr&eacute;e pour une dur&eacute;e ind&eacute;termin&eacute;e.",
"D&eacute;sinscription",
"Pour vous d&eacute;sinscrire du bulletin d�information de la FSTB, il suffit d�inscrire votre adresse email ci-dessous et d�appuyez sur le bouton � d&eacute;sincription �. Apr&egrave;s avoir re&ccedil;u un email de confirmation, vous ne recevez plus le bulletin de la FSTB.");
?>

<?php
//historique
$VAR_TAB_HISTORIQUE = array(
"Le tchoukball est n&eacute; des r&eacute;flexions et des recherches du Dr Hermann Brandt, d&eacute;but&eacute;es au cours des ann&eacute;es soixante. Il r&eacute;sulte d'une &eacute;tude scientifique critique des sports d'&eacute;quipes les plus populaires. Le Dr Brandt, m&eacute;decin genevois, a c�toy&eacute;, tout au long de sa carri&egrave;re un grand nombre d'athl&egrave;tes bless&eacute;s plus au moins gravement lors de la pratique de leur sport.",
"Il constata que ces traumatismes &eacute;taient dus &agrave; l'ex&eacute;cution de mouvements inadapt&eacute;s &agrave; la physiologie de l'individu, ainsi qu'aux nombreuses formes d'agression pr&eacute;sentes dans certains sports. Son analyse a renforc&eacute; cette inqui&eacute;tude relative &agrave; la valeur &eacute;ducative des sports modernes, qui pour lui ne doivent pas aboutir &agrave; la fabrication syst&eacute;matique de champions, mais �contribuer &agrave; l'&eacute;dification d'une soci&eacute;t&eacute; humaine valable� (Brandt H., � �tude scientifique des sports d'&eacute;quipe - Le Tchoukball, le sport de demain �, Ed. Roulet, Gen&egrave;ve, 1971). Il con&ccedil;oit donc un nouveau jeu, le tchoukball, qu'il expose dans le cadre de son � �tude scientifique des sports d'&eacute;quipe �. Le tchoukball se pr&eacute;sente comme un m&eacute;lange de pelote basque, de handball et de volley-ball. Il s'agit d'un sport de balle et d'&eacute;quipe qui se joue &agrave; l'aide de deux surfaces de renvoi (les cadres) et se caract&eacute;rise par la suppression de toutes formes d'agressions corporelles entre les adversaires. Par son caract&egrave;re ludique, il est con&ccedil;u pour inciter chaque individu &agrave; pratiquer ce sport, quels que soient son �ge, son sexe ou ses capacit&eacute;s athl&eacute;tiques. ");
?>

<?php
//REGLES_TCHOUKBALL
$VAR_TAB_REGLES_TCHOUKBALL = array(
"Un joueur marque un point pour son &eacute;quipe",
"si la balle, &agrave; son retour du cadre de renvoi, touche le sol avant qu'un adversaire ne la r&eacute;cup&egrave;re.",
"Un joueur donne un point &agrave; l'&eacute;quipe adverse si :",
"1. Il manqe le cadre.<br />
2. Il fait rebondir la balle, apr&egrave;s tir au cadre, hors du terrain.<br />
3. La balle tombe dans la zone interdite &agrave; l'aller ou au retour du lancer.<br />
4. Il tire au cadre et la balle rebondit sur lui-m�me.",
"Un joueur commet une faute lorsque :",
"1. Il se d&eacute;place en dribblant avec la balle au sol ou en l'air (l'erreur de r&eacute;ception n'est pas consid&eacute;r&eacute;e comme une faute).<br />
2. Il effectue plus de trois empreintes au sol en possession du ballon (la r&eacute;ception avec un ou deux pieds au sol compte pour une empreinte).<br />
3. Il effectue une quatri&egrave;me passe pour le compte de son &eacute;quipe (l'engagement en compte pas pour une passe, on compte par contre une passe lorsque la balle est envoy&eacute;e ou d&eacute;vi&eacute;e par un joueur vers un de ses co&eacute;quipiers).<br />
",
"4. Il laisse tomber la balle pendant une action de jeu.<br />
5. Il g�ne l'adversaire pendeant ses actions de jeu (passe, tir au cadre, r&eacute;ception, d&eacute;placement, ...).<br />
6. Il prend contact avec la zone \"interdite\", sur une passe ou un tir, avant que la balle n'ait quitt&eacute; sa main (apr&egrave;s le tir ou la passe, le joueur doit sortir de la zone le plus rapidement possible sans g�ner l'adversaire).",
"7. Il joue avec les pieds ou les jambes (en dessous des genoux).<br />
8. Il g�ne l'adversaire pendant ses actions de jeu (passe, tir au cadre, r&eacute;ception, d&eacute;placemen, ...).<br />
9. Il lance intentionnellement le ballon sur un adversaire.<br />
10. Il r&eacute;cup&egrave;re la balle tir&eacute;e au cadre par un de ses co&eacute;quipiers.<br />
11. Le mauvais rebond se produit lorsque la balle touche le bord du cadre ou qu'il ne respecte pas la r&eacute;gle du \"miroir\" &agrave; cause des crochets ou des &eacute;lastiques. Ainsi, lorsque la trajectoire de la balle est modifi&eacute;e par un mauvais rebond, le point ne compte pas et le jeu reprend &agrave; partir de l'endroit o� le ballon est tomb&eacute;. La possession de la balle change de camp.<br />
12. Il garde la balle plus de 3 secondes.");
?>

<?php
//CHARTE_TCHOUKBALL
$VAR_TAB_CHARTE_TCHOUKBALL = array(
"Charte du Tchoukball ",
"1. Le jeu exclut toute recherche de prestige, tant personnel que collectif. <br><br>
Sur le plan personnel, l'attitude du joueur implique le respect de tout autre joueur, adversaire ou co&eacute;quipier, qu'il soit plus fort ou plus faible. <br><br>
Le jeu &eacute;tant ouvert &agrave; toutes les capacit&eacute;s, inn&eacute;es ou acquises, on rencontrera fatalement tous les nivaux qualitatifs de joueurs; le respect ou la consid&eacute;ration, dus &agrave; chacun, oblige tout joueur &agrave; adapter son propre comportement technique et tactique aux circonstances du moment. <br><br>
Sur le plan collectif, un r&eacute;sultat, quel qu'il soit, n'engage jamais la r&eacute;putation de qui que ce soit et surtout ne donne droit &agrave; aucun genre de \"sectarisme\". D'une victoire on peut retirer du plaisir, voire de la joie, mais jamais une satisfaction d'orgueil. La joie de gagner est un encouragement, l'orgueil de la victoire comporte en germe une lutte de prestige que nous condamnons comme source de conflits entre humains, &agrave; tous les degr&eacute;s. ",
"2. Le jeu comporte un \"don de soi\" permanent : d'abord une surveillance constante des circuits de la balle, ensuite l'observation objective et sympathisante des joueurs. Le don de soi est la participation subjective aux &eacute;v&eacute;nements; il a pour r&eacute;sultat de \"m�ler\" les personnalit&eacute;s &agrave; la confrontation r&eacute;ciproque des r&eacute;actions au jeu : ",
"a) Le sens du rendement collectif de l'&eacute;quipe : il soude les co&eacute;quipiers les uns aux autres; il apprend &agrave; estimer, &agrave; appr&eacute;cier leurs valeurs; il cr&eacute;e le sentiment de l'unit&eacute; dans l'effort du petit groupe. <br><br>
b) L'assimilation des attitudes de groupe dit \"adversaire\" &agrave; qui il s'agit d'opposer un jeu opportun mais ne comportant jamais et &agrave; aucun degr&eacute; un sentiment d'hostilit&eacute;. <br><br>
c) Le souci majeur de tout joueur doit �tre la recherche du beau jeu. L'exp&eacute;rience universelle dans les sports se r&eacute;sume par l'expression courante : \"Le beau jeu appelle le beau jeu\".",
"Cette disposition d'esprit est la base de l'action sociale du Tchoukball : elle permet de s'orienter vers la perfection et d'&eacute;viter toujours l'action n&eacute;gative envers l'adversaire. <br><br>
C'est plus qu'une r&egrave;gle de jeu, c'est une r&egrave;gle de conduite permanente, composante psychique du comportement, base de la personnalit&eacute; sociale. <br><br>
L'objectif est donc la suppression des conflits dans une intention identique : l'id&eacute;e de \"fair play\" &eacute;tant d&eacute;pass&eacute;e, il ne s'agit pas de concessions faites &agrave; l'adversaire, mais d'actions communes liant les &eacute;quipes l'une &agrave; l'autre o� le beau jeu de l'une appuie et rend possible le beau jeu de l'autre. ",
"3. Le jeu devient un exercice social par l'activit&eacute; physique : c'est une mise en commun des moyens d'ex&eacute;cution, le meilleur portant la responsabilit&eacute; \"d'apprendre\" aux moins bons; il n'y a pas de v&eacute;ritable championnat, mais d'une course &agrave; la \"comp&eacute;tence\". <br><br>
Lorsque l'on dit que \"les meilleurs gagnent\", il faut sous-entendre qu'�tre le meilleur\" s'acquiert par la qualit&eacute; de la pr&eacute;paration. Il est bon alors que les r&eacute;sultats r&eacute;compensent la peine que se donnent les joueurs d'abord individuellement, puis dans un effort collectif. <br><br>
Dans cette limite l&agrave;, une victoire peut et doit entra�ner une satisfaction normale s'accompagnant du respect de l'adversaire. <br><br>
La victoire doit produire chez cet adversaire une stimulation (envie d'en faire autant) et non un sentiment d'&eacute;crasement. Les gagnants doivent s'employer &agrave; produire cette impression. Une satisfaction saine des vainqueurs est une mani&egrave;re de tendre la main aux perdants pour les inciter &agrave; poursuivre un entra�nement efficace. <br><br>
Pour ces raisons, la notion de \"champion\" doit c&eacute;der la place &agrave; une notion plus modeste et mieux adapt&eacute;e : celle de \"gagnant\". <br><br>
Jouer pour se perfectionner : c'est le sentiment que toute activit&eacute; de jeu doit comporter et d&eacute;velopper. C'est vers cette conclusion que doit tendre la pratique du Tchoukball, de la plus petite rencontre amicale &agrave; la plus s&eacute;rieuse confrontation \"au sommet\". ");
?>

<?php
//ARBITRE_FEDERATION
$VAR_TAB_ARBITRE_FEDERATION = array(
"Les r�les de l'arbitrage dans la f&eacute;d&eacute;ration",
"Sans r&egrave;gle, point de jeu !",
"Le respect des r&egrave;gles est donc une composante fondamentale du sport en g&eacute;n&eacute;ral, et du tchoukball en particulier. Mais l�arbitre a un r�le encore plus important &agrave; jouer : il procure aux joueurs et aux entra�neurs un � feed-back � important sur leur prestation. Il permet donc &agrave; tous de progresser. C�est pourquoi l�arbitrage est indispensable dans le jeu, et cela m�me si les joueurs montrent un comportement empreint de respect de l�autre et de fair-play !",
"L�arbitrage en suisse est g&eacute;r&eacute; par la Commission d�arbitrage, d&eacute;pendante du secteur Jeu de la FSTB. Les principales missions de la Commission d�arbitrage sont :",
"<a href=\"?lien=25\">- de former des arbitres et de veiller &agrave; leur formation continue</a><br />
- de promouvoir l�arbitrage au sein des clubs<br />
- de promouvoir un arbitrage de qualit&eacute; dans le <a href=\"?lien=5\">championnat suisse</a> et dans les tournois de clubs <br />
- de constituer une plate-forme de contact pour toutes les questions relatives aux r&egrave;gles et &agrave; l�arbitrage venant des membres de la FSTB, mais aussi des MEP, des associations sportives suisses, etc.");
?>

<?php
//JUNIOR_FEDERATION
$VAR_TAB_JUNIOR_FEDERATION = array(
"Afin d'offrir aux enfants et aux jeunes des activit&eacute;s plus vari&eacute;es et un meilleur encadrement, la F&eacute;d&eacute;ration suisse de tchoukball a cr&eacute;&eacute; une Commission juniors sp&eacute;cialement charg&eacute;e du d&eacute;veloppement du tchoukball aupr&egrave;s des jeunes. Pour d&eacute;velopper le tchoukball aupr&egrave;s des jeunes, une Commission juniors a &eacute;t&eacute; fond&eacute;e. Conform&eacute;ment au r&egrave;glement au sujet du mouvement junior, les principales missions de cette Commission sont :",
"- de donner aux jeunes l�occasion de s�entra�ner et de jouer avec d�autres jeunes de la m�me cat&eacute;gorie d��ge<br />
- de former la rel&egrave;ve et de cr&eacute;er ainsi une base solide au tchoukball suisse<br />
- de rendre effective la devise � un sport pour tous �<br />
- de promouvoir la Charte du tchoukball et la <a href=\"http://wwwedu.ge.ch/co/epco/charte.html\" target=\"_blank\">Charte des droits de l�enfant dans le sport</a>",
"Pour ce faire, diff&eacute;rentes activit&eacute;s sont propos&eacute;es par la FSTB et les clubs, comme les entra�nements interclubs juniors M15 et M18 ainsi que les tournois juniors M12 et M15.",
"Concr&egrave;tement, la Commission junior travaille dans les domaines suivants :",
"- mettre en place une strat&eacute;gie g&eacute;n&eacute;rale pour d&eacute;velopper le mouvement junior en Suisse<br />
- g&eacute;rer et organiser les entra�nements et les matchs des &eacute;quipes interclubs M15 et M18<br />
- coordonner les activit&eacute;s juniors durant l�ann&eacute;e (tournois, entra�nements interclubs, camps, matchs divers)<br />
- conseiller et aider les clubs qui souhaiteraient ouvrir un nouvel entra�nement pour des juniors<br />
- conseiller les clubs dans l�organisation de leurs tournois juniors",
"Pour plus d�information au sujet du mouvement junior en Suisse ou pour toute question au sujet des activit&eacute;s sus-mentionn&eacute;es, merci de prendre contact avec la Commission junior.");
?>

<?php
//PRESENTATION_TCHOUKBALL
$VAR_TAB_PRESENTATION_TCHOUKBALL = array(
"Tactique",
"Le tchoukball est un v&eacute;ritable sport d'&eacute;quipe !<br />
M&eacute;lange de volleyball, de handball et de squash, le tchoukball demande une vraie coh&eacute;sion au sein de l'&eacute;quipe. Tous les joueuses et joueurs sur le terrain sont compl&eacute;mentaires. Il est impossible de gagner un match en ne comptant que sur les prouesses techniques de quelques-uns.",
"Accessible",
"5 minutes d'explications et c'est parti, on commence &agrave; jouer !<br />
Pour le d&eacute;butant le tchoukball est ludique d&egrave;s les premi&egrave;res minutes. Les gestes de base sont simples et naturels. Une condition physique moyenne suffit amplement et les r&egrave;gles debase sont ais&eacute;es &agrave; comprendre. L'absence de contact permet de jouer en &eacute;quipe mixte. Pour commencer &agrave; jouer, aucun mat&eacute;riel particulier est n&eacute;cessaire. C'est un sport absolument id&eacute;al pour toute personne souhaitant reprendre une activit&eacute; physique en &eacute;quipe.<br />
<br />
Pourtant en comp&eacute;tition le tchoukball devient un sport particuli&egrave;rement intense,&eacute;prouvant et complet. Les gestes techniques demand&eacute;s deviennent de plus en plus complexes et spectaculaires.",
"Intense",
"Un sport passionnant, complet et d&eacute;foulant de part l'absence d'obstruction, les gestes techniques demand&eacute;s, le rythme soutenu desrencontres et de l'intelligence de jeu requise, le tchoukball est un sport particuli&egrave;rementdynamique et plaisant &agrave; jouer.",
"Fair-Play",
"Une philosophie exemplaire<br />
En pr&eacute;conisant le fair-play en interdisant tout geste d'agresssion et d'obstruction envers l'&eacute;quipe adverse, jouer devient � jouer avec l'autre � et non � jouer contre l'autre �. Gagner, c'est � jouer mieux que l'autre � et non � d&eacute;truire le jeu de l'autre �<br />
<br />
Par des r&egrave;gles adapt&eacute;es, le tchoukball cherche &agrave; &eacute;liminer l'anti-jeu et l'agressivit&eacute; inutile. Tout geste de g�ne ou d'obstruction est banni.",
"Les r&egrave;gles de base",
"- 1 terrain 10x20m et une balle de handball.<br />
- 2 &eacute;quipes de 7 joueuses/joueuses <br />
- 2 cadres de tchoukball (trampoline inclin&eacute;) sont plac&eacute;s de chaque c�t&eacute; du terrain",
"Et on joue !",
"- L'&eacute;quipe qui attaque se fait des passes et tire la balle sur le trampoline.<br />
A ce moment, l'autre &eacute;quipe doit essayer de rattraper la balle avant qu'elle ne touche le sol. Si elle y parvient, le jeu continue. Mais si elle n'y arrive pas, l'&eacute;quipe attaquante marque le point.");
?>

<?php
//FORMATION_ARBITRE
$VAR_TAB_FORMATION_ARBITRE = array(
"La formation d'arbitres",
"La formation des arbitres est une des missions de la <a href=\"?lien=16\">Commission d�arbitrage</a> de la FSTB.",
"En Suisse, on distingue 2 niveaux d�arbitres : les arbitres 1, qui peuvent arbitrer les matchs de tournois et du championnat suisse, et les arbitres 2, qui peuvent, en plus de l�arbitrage des tournois et du championnat suisse, �tre candidats &agrave; un titre d�arbitre international (titre FITB) et participer &agrave; la formation des arbitres 1. Une liste des arbitres est tenue &agrave; jour par la Commission d�arbitrage.",
"Chaque ann&eacute;e, la Commission d�arbitrage organise un cours de formation pour les arbitres 1. Ce cours se d&eacute;roule sur deux journ&eacute;es s&eacute;par&eacute;es par quatre mois de pratique durant lesquels les candidats arbitres 1 sont visionn&eacute;s par des arbitres 2 pour leur donner des conseils et juger de leur niveau d�arbitrage. Le cours de formation pour devenir arbitre 1 est ouvert &agrave; toutes les personnes de 18 ans r&eacute;volus ma�trisant les r&egrave;gles de base du tchoukball et poss&eacute;dant quelque exp&eacute;rience du jeu. Le formulaire d'inscription permet de s�inscrire pour le prochain cours, qui d&eacute;bute chaque ann&eacute;e au mois de novembre. Les germanophones sont les bienvenus !",
"Le cours permettant de devenir arbitre 2 a lieu en fonction de la demande. Pour de plus amples informations &agrave; ce sujet, veuillez prendre contact avec la Commission d�arbitrage.");
?>

<?php
//FORMATION_ARBITRE_JUNIOR
$FORMATION_ARBITRE_JUNIOR = array(
"La formation d'arbitres juniors",
"La Commission d�arbitrage propose une formation d�arbitre junior FSTB. Cette formation s�adresse aux juniors M15 et a pour but d�initier les jeunes &agrave; l�arbitrage et de leur donner des outils pour arbitrer les matchs des juniors M12 et M15. Ce cours est &eacute;galement une porte d�entr&eacute;e possible pour le cours d�arbitre 1 FSTB (adultes).",
"Les objectifs de cette formation sont :",
"Clarifier les r&egrave;gles principales (contenu des r&egrave;gles FITB) et la technique d�arbitrage (position, gestes, sifflet)",
"Nommer des arbitres juniors M15 pour leur permettre d�arbitrer les M12 et les M15 dans les tournois de clubs.");
?>

<?php
//FORMATION_GESTIONNAIRE_CLUB
$VAR_TAB_FORMATION_GESTIONNAIRE_CLUB = array(
"La formation de gestionnaire de club",
"Sur un jour sont discut&eacute;s les principaux outils pratiques n&eacute;cessaires &agrave; une gestion efficace d�un club de tchoukball. Ouvert aux personnes ayant 18 ans r&eacute;volus, cette formation est id&eacute;ale pour les personnes souhaitant cr&eacute;er un club. Il n�est pas n&eacute;cessaire d��tre membres de la FSTB (ouverts aux MEP, etc.) pour pouvoir y participer.<br />
<br />
Selon l�ann&eacute;e, des th&egrave;mes sp&eacute;cifiques peuvent �tre propos&eacute;s (par ex. cr&eacute;er un entra�nement junior
)",
"Questions ?",
"Daniel Buschbeck : 078 / 680 15 14.");
?>

<?php
//FORMATION_JS
$VAR_TAB_FORMATION_JS = array(
"Les formations Jeunesse et Sport",
"30 ans et des poussi&egrave;res apr&egrave;s sa naissance, le tchoukball a la chance de pouvoir b&eacute;n&eacute;ficier des offres de JS",
"Les moniteurs JS Tchoukball",
"Ainsi, gr�ce &agrave; la formation Tchoukball JS, les moniteurs Tchoukball peuvent:<br />
<br />
- Acc&eacute;der &agrave; une formation compl&egrave;te de moniteur de tchoukball, y compris &agrave; des connaissances relatives au d&eacute;veloppement de l'enfant-adolescent d'un point de vue sportif, &agrave; la planification de saisons et d'entra�nements
<br />
",
"REMARQUE : nous accueillons les bras ouverts les MEP qui souhaitent obtenir une formation compl&egrave;te sur le Tchoukball. Un programme sp&eacute;cialement adapt&eacute; leur est d'ailleurs propos&eacute; s'il suivent le cours de moniteur JS (certains cours en commun avec des tchoukeurs de club, certains cours entre MEP pour acqu&eacute;rir les bases techniques). Il leur est toutefois conseill&eacute; d'avoir une certaine pratique du tchoukball (quelques entra�nement au sein d'un club peuvent �tre organis&eacute;s. Il suffit de contacter pour cela le club pr&egrave;s de chez soi ou de contacter Carole Greber).",
"- Participer &agrave; des modules de perfectionnement sp&eacute;cifiques au Tchoukball et des modules de perfectionnement interdisciplinaires organis&eacute;s par d'autres sports dans le cadre de JS (. Depuis 2003, tout moniteur tchoukball JS peut participer &agrave; des cours sur le coaching et sur la condition physique. Planning annuel.<br />
<br />
- Permettre &agrave; leur club de recevoir &agrave; terme des subventions pour les entra�nements donn&eacute;s &agrave; des jeunes entre 10 et 20 ans.",
"Les coachs JS",
"Le coach JS est un conseiller, un administrateur
Il permet entre autre de d&eacute;clarer les activit&eacute;s de son club &agrave; JS. N�oubliez pas de faire appel &agrave; lui ! pour plus d�information.",
"Questions ?");
?>

<?php
//FORMATION_SWISSOLYMPIC
$VAR_TAB_FORMATION_SWISSOLYMPIC = array(
"La formation de base d�entra�neur de performances Swiss Olympic",
"L'&eacute;cole f&eacute;d&eacute;rale de sport int&eacute;gr&eacute;e &agrave; l'Office f&eacute;d&eacute;ral de sport collabore &eacute;troitement avec Swiss Olympic pour offrir aux entra�neurs une formation et un perfectionnement &agrave; la fois modernes et efficaces.<br />
<br />
Certains entra�neurs de tchoukball ont d&eacute;j&agrave; eu l�occasion d�y participer. A cette occasion, ils ont redig&eacute; des m&eacute;moires.",
"Ceux-ci sont t&eacute;l&eacute;chargeables dans la rubrique \"Download\".");
?>

<?php
//COMISSION_SPONSORING
$VAR_TAB_COMISSION_SPONSORING = array(
"Commission sponsoring",
"Responsable commission sponsoring : ",
"Alain Waser : 079 543 28 35",
"La commission sponsoring s'occupe principalement de deux t�ches :",
"Trouver des partenaires afin d'aider au d&eacute;veloppement de la F&eacute;d&eacute;ration suisse de Tchoukball. Pour ce faire, la commission a mis en place un concept permettant d'offrir des contre-prestations tr&egrave;s int&eacute;ressantes &agrave; nos partenaires en terme de visibilit&eacute; et d'image.",
"Assister les clubs dans leur recherche de sponsors, en leur fournissant entres autres, des conseils et des documents. ",
"Diff&eacute;rents partenariats sont propos&eacute;s, de l'annonce 1/8 de page dans le Suisse Tchoukball jusqu'au partenariat complet avec la f&eacute;d&eacute;ration qui s'&eacute;tend sur la dur&eacute;e de la saison.<br />
<br />
Pour plus de renseignements, consultez notre dossier sponsoring. L'ordre d'insertion pour une annonce dans le Suisse Tchoukball est disponible sous la rubrique download<br />
<br />
Si vous aimeriez un renseignement sur le sponsoring au sein de la F&eacute;d&eacute;ration Suisse de Tchoukball ou si vous d&eacute;sirez collaborer avec nous, n'h&eacute;sitez pas &agrave; nous contacter. Nous nous ferons un plaisir de pouvoir vous renseigner.");
?>

<?php
//INTRO_SPONSORS
$VAR_TAB_INTRO_SPONSORS = array(
"Ces entreprises soutiennent la F&eacute;d&eacute;ration Suisse de Tchoukball et, par cons&eacute;quent, la pratique du tchoukball dans notre pays.",
"Visitez donc leur site. Un simple click suffit !");
?>

<?php
//MEDIA
$VAR_TAB_MEDIA = array(
"Informations aux m&eacute;dias",
"Personne de contact :",
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
array("http://home.datacomm.ch/hveveve/tchouk_unineuch.htm","Tchoukball club uni Neuch�tel"),
array("http://www.ctbc.ch","Tchoukball Club Chavannes"),
array("http://www.tchoukballgeneve.ch","Tchoukball club Carouge"),
array("http://www.tchoukballmorges.ch","Tchoukball club Morges"),
array("http://www.delemont.ch/tchoukballade","Tchoukball club Del&eacute;mont"),
array("http://www.tchoukball-lachauxdefonds.net","Tchoukball Club La Chaux-de-Fonds")),
array("F&eacute;d&eacute;ration Internationale de Tchoukball",array("http://www.tchoukball.org","http://www.tchoukball.org")),
array("Europe",array("http://www.tchoukball.it/","F&eacute;d&eacute;ration italienne"),
array("http://www.tchoukball.org.uk/","F&eacute;d&eacute;ration anglaise"),
array("http://www.tchoukball-belgium.be","F&eacute;d&eacute;ration Belge"),
array("http://match86.fr.fm/","Mign&eacute; Auxances Tchoukball (france)"),
array("http://www.tchoukball.com/","Paris First Tchouk Ball Club"),
array("http://tchoukballove.misto.cz/","R&eacute;public Czech Tchoukball"),
array("http://www.tchoukball.at","Tchoukball en Autriche")),
array("Amerique du nord",array("http://www.tchoukball.net/","U.S. TchoukBall Association"),
array("http://www.tchoukball.us/","Tchoukball.us"),
array("http://www.tchoukball.ca/","Canada Tchoukball Association")),
array("Amerique du sud",array("http://www.tchoukbrasil.hpg.ig.com.br/","Tchoukball brasil"),
array("","Argentina Association of Tchoukball")),
array("Asie",array("http://163.16.47.136/ROCTBA/","Republic of China Tchoukball"),
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
array("Vid&eacute;o de la finale de championnat suisse de mai 2003","Toutes les vid&eacute;os sont identiques, seule la qualit&eacute; est diff&eacute;rente.",array("Vid&eacute;o *.mov (QuickTime)","Videos/tchoukball_finale_2003.mov"),
array("Vid&eacute;o *.wmv (Windows)","Videos/tchoukball_finale_2003.wmv"),
array("Vid&eacute;o au format *.mpeg (Movie) basse qualit&eacute;","Videos/tchoukball_finale_2003_low_quality.mpg"),
array("Vid&eacute;o *.mpeg (Movie) haute qualit&eacute;","Videos/tchoukball_finale_2003_high_quality.mpg")));
?>

<?php
//SPONSORS
$VAR_TAB_SPONSORS = array(
array("Nos partenaires officiels",array("Le Centre Sportif du Val-de-Travers est un complexe flambant neuf se trouvant au c�ur du canton de Neuch�tel. Avec ses multiples terrains de sport (int&eacute;rieur et ext&eacute;rieur), sa piscine et ses chambres modernes et fonctionnelles, il est l'endroit id&eacute;al pour organiser vos camps de vacances.","logos/logo_val_de_travers.gif","http://www.centresportif-vdt.ch"),
array("Tchoukball Promotion a &eacute;t&eacute; le premier partenaire de la FSTB. Il fournit du mat&eacute;riel de premi&egrave;re qualit&eacute; pour la pratique du tchoukball (des cadres, des balles de tchoukball, des &eacute;quipements textiles, etc.) &agrave; des prix tr&egrave;s int&eacute;ressants.","logos/tchoukball_promotion.gif","http://www.tchoukballpromotion.ch/"),
array("Petroplus, et plus sp&eacute;cialement la Raffinerie Petroplus de Crissier produit pr&egrave;s d'un quart de la demande suisse en produits p&eacute;troliers. Cette grande entreprise a choisi de s'associer au tchoukball car tout deux partagent de nombreuses valeurs semblables (respect, esprit d'&eacute;quipe).","logos/logo_petroplus.gif","http://www.petroplus.ch/French/")),
array("Sponsors",array("Rivella, la seule boisson rafra�chissante saine, naturelle qui se d&eacute;cline en trois couleurs.","logos/rivella-red.gif","http://www.rivella.ch")),
array("Organismes partenaires",array("","logos/logo-respect.gif","http://www.lerespect.ch/"),
array("","logos/logo_js.gif","http://www.jeunesseetsport.ch/")));
?>

