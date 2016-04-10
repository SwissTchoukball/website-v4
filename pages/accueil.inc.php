<div id="laDerniereNews">

    <?php
    $TAILLE_NEWS = 2500;

    $requete = mysql_query("SELECT * FROM News ORDER by premiereNews DESC, Date DESC LIMIT 0,1");
    while($donnees = mysql_fetch_array($requete)) {

        $titre = $donnees['titre'.$_SESSION["__langue__"]];
        $corps = $donnees['corps'.$_SESSION["__langue__"]];
        if($titre==""){
            $titre = $donnees['titre'.$VAR_TABLEAU_DES_LANGUES[0][0]];
        }
        if($corps==""){
            $corps = $donnees['corps'.$VAR_TABLEAU_DES_LANGUES[0][0]];
        }

        echo "<h1>".$titre."</h1>";

        if($donnees['image'] != 0){ // On affiche l'image si il y en a une.
            $retourbis = mysql_query("SELECT * FROM Uploads WHERE id='".$donnees['image']."'");
            $donneesbis = mysql_fetch_array($retourbis);
            echo "<img src='" . PATH_TO_ROOT . "/uploads/".$donneesbis['fichier']."' alt='".$donneesbis['titre']."' />";
        }
        //afficherAvecEncryptageEmail(sizeNewsManager($corps, $TAILLE_NEWS, $donnees['id']));
        echo truncateHtml(markdown($corps), $TAILLE_NEWS, "... "."<p class='lireSuiteArticle'><a href='/news/".$donnees['id']."'>".VAR_LANG_LIRE_SUITE_ARTICLE."</a></p>");
        echo "<p class='date'>Posté ".date_sql2date_joli($donnees["date"],"le","Fr")."</p>";
        ?>
        <div class="socialButtons">
	        <a href="http://twitter.com/share" class="twitter-share-button" data-url="http://www.tchoukball.ch/news/<?php echo $donnees['id']; ?>" data-text="FSTB : <?php echo strip_tags($titre); ?>" data-count="none" data-via="tchouksuisse" data-lang="<?php echo strtolower($_SESSION['__langue__']); ?>">Tweet</a><!-- Javascript dans le footer -->
	        <fb:like href="http://www.tchoukball.ch/news/<?php echo $donnees['id']; ?>" send="true" layout="button_count" width="450" show_faces="false" font="lucida grande"></fb:like>
        </div>
        <?php
    }
    /*echo "<p class='center'>";
    randompicture(); //Image aléatoire
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    randompicture(); //Image aléatoire
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    randompicture(); //Image aléatoire
    echo "</p><br />";  */
    ?>
<!--<fb:like-box href="https://www.facebook.com/tchoukballsuisse" width="558" height="450" show_faces="false" header="false" stream="true" show_border="true"></fb:like-box>-->
</div>
<div id="classementChampionnatAccueil">

	<!--<a class="LienVersInscriptionNewsletter" href="/rester-informe">S'inscrire à la Newsletter</a>-->
    <?php

	// TODO: Use new youtube integration system
    // Vidéo TCHOUKBALL PROMOTION ou FSTB
    /*
    $channel="TP"; //TP ou FSTB
    if	($channel=="TP") {
    	echo "<h2><a href='http://www.youtube.com/tchoukballpromotion'>La dernière vidéo de<br />Tchoukball Promotion</a></h2><br />";
   	} elseif ($channel=="FSTB") {
    	echo "<h2><a href='http://www.youtube.com/tchoukballpromotion'>La dernière vidéo de<br />notre chaîne YouTube</a></h2><br />";
    }
    echo "<div class='TitreTPVideo'><a href='http://www.youtube.com/watch?v=".getLastYouTubeVideoID($channel)."'>".getLastYouTubeVideoTitle($channel)."</a></div>";
    ?>
    <iframe width="200" height="162" src="http://www.youtube.com/embed/<?php echo getLastYouTubeVideoID($channel); ?>" frameborder="0" allowfullscreen></iframe>
    <!--VIEUX CI-DESSOUS -->

    <?php
	*/

    //CHAMPIONNAT

    $annee = annee(date_actuelle());
    if(mois(date_actuelle())<8){
        $annee--;
    }
    $couleurLigneA = "#ffffff";
    $couleurLigneB = "#ffe6e7";

    // Affichage classement

	include('championnat.fonctions.tri.classement.A.inc.php');
	include('championnat.fonctions.tri.classement.B.inc.php');
	include('championnat.fonctions.tri.classement.C.inc.php');
	include('championnat.fonctions.tri.classement.D.inc.php');
	include('championnat.fonctions.tri.classement.E.inc.php');
	include('championnat.fonctions.tri.classement.F.inc.php');

	$debug=false;

	/* Sélection de la politique des points de l'année choisie. */

	$retourAnnee = mysql_query("SELECT * FROM Championnat_Saisons WHERE saison=".$annee."");
	$donnees = mysql_fetch_array($retourAnnee);
	$pointsMatchGagne = $donnees['pointsMatchGagne'];
	$pointsMatchNul = $donnees['pointsMatchNul'];
	$pointsMatchPerdu = $donnees['pointsMatchPerdu'];
	$pointsMatchForfait = $donnees['pointsMatchForfait'];
	$nbMatchGagnantTourFinal = $donnees['nbMatchGagnantTourFinal'];
	$nbMatchGagnantPlayoff = $donnees['nbMatchGagnantPlayoff'];
	$nbMatchGagnantPlayout = $donnees['nbMatchGagnantPlayout'];
	$nbMatchGagnantPromoReleg = $donnees['nbMatchGagnantPromoReleg'];

	/* Requête pour les noms de Catégorie et Tour afin de pas devoir les chercher à chaque fois avec une requête */

	$requeteNomCategorie="SELECT idCategorie AS id, categorie".$_SESSION['__langue__']." AS nom FROM Championnat_Categories";
	$retourNomCategorie=mysql_query($requeteNomCategorie);
	$nomCategorie=array();
	while($donnesNomCategorie=mysql_fetch_array($retourNomCategorie)){
		$nomCategorie[$donnesNomCategorie['id']]=$donnesNomCategorie['nom'];
	}

	$requeteNomTour="SELECT idTour AS id, tour".$_SESSION['__langue__']." AS nom FROM Championnat_Types_Tours";
	$retourNomTour=mysql_query($requeteNomTour);
	$nomTour=array();
	while($donnesNomTour=mysql_fetch_array($retourNomTour)){
		$nomTour[$donnesNomTour['id']]=$donnesNomTour['nom'];
	}



	/* Requête définissant les de classements à faire et leur type puis execution d'une boucle pour chaque classement du dernier tour de chaque catégorie ATTENTION : différent de la page Classement ! */

	$requeteNbClassementA = "SELECT DISTINCT idCategorie FROM Championnat_Equipes_Tours WHERE saison=".$annee." ORDER BY idCategorie";
	if($debug){
		echo "<br /><br />Categorie : ".$requeteNbClassementA;
	}
	$retourNbClassementA = mysql_query($requeteNbClassementA);
	$nbRows=mysql_num_rows($retourNbClassementA);
	if($nbRows==0){
		$championnatEnCours=0;
	}
	else{
		$championnatEnCours=1;
	}

	if($championnatEnCours){
    	echo "<h2><a href='".VAR_HREF_PAGE_PRINCIPALE."?".VAR_HREF_LIEN_MENU."=24'>".VAR_LANG_CLASSEMENT_CHAMPIONNAT."</a></h2>";
	}
	while($donneesNbClassementA = mysql_fetch_array($retourNbClassementA)){
		$requeteNbClassementB = "SELECT DISTINCT idTour FROM Championnat_Equipes_Tours WHERE saison=".$annee." AND idCategorie=".$donneesNbClassementA['idCategorie']." ORDER BY idTour DESC LIMIT 1";
		if($debug){
			echo "<br /><br />Tour : ".$requeteNbClassementB;
		}
		$retourNbClassementB = mysql_query($requeteNbClassementB);
		$donneesNbClassementB = mysql_fetch_array($retourNbClassementB);

		$requeteNbClassementC = "SELECT DISTINCT noGroupe FROM Championnat_Equipes_Tours WHERE saison=".$annee." AND idCategorie=".$donneesNbClassementA['idCategorie']." AND idTour=".$donneesNbClassementB['idTour']." ORDER BY noGroupe";
		if($debug){
			echo "<br /><br />Groupe : ".$requeteNbClassementC;
		}
		$retourNbClassementC = mysql_query($requeteNbClassementC);
		while($donneesNbClassementC = mysql_fetch_array($retourNbClassementC)){
			$idCategorie = $donneesNbClassementA['idCategorie'];
			$idTour =  $donneesNbClassementB['idTour'];
			$noGroupe =  $donneesNbClassementC['noGroupe'];
			if($debug){
				echo "<h4>ID TOUR : ".$idTour."</h4>";
			}
			// Informations pour les fonctions plus bas.
			$informations=array();
			$informations['annee']=$annee;
			$informations['idCategorie']=$idCategorie;
			$informations['idTour']=$idTour;
			$informations['noGroupe']=$noGroupe;


			/* Triage par points*/

			$groupeTriPoints = array();

			$requete = "SELECT * FROM Championnat_Equipes_Tours WHERE saison=".$annee." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$noGroupe." ORDER BY points DESC";
			if($debug){
				echo "<br /><br />Tri par points : ".$requete;
			}
			$retourTriPoints = mysql_query($requete);
			$a=0;
			while($donneesTriPoints = mysql_fetch_array($retourTriPoints)){ // Vérification du nombre de points pour chaque équipe
				if($a==0 OR $donneesTriPoints['points']!=$points){ // SI première équipe OU équipe précédante n'a pas le même nombre de points
					$a++; // Nouveau groupe de tri par points
					$b=1; // Réinitialisation du numéro d'équipe du groupe
					$points=$donneesTriPoints['points']; // Nombre de point qu'a le groupe de tri par points
				}
				$groupeTriPoints[$a][$b]=$donneesTriPoints['idEquipe']; //ID d'équipe placé dans un groupe de tri par points
				$b++; //Nouveau numéro d'équipe
			} // Fin boucle Tri Points
			$groupeTriPoints[0]=$a; //la position 0 du tableau étant libre, on peut lui donner le nombre de groupes a égalités pour la fonction.
			if($debug){
				echo "<br />Tableau de groupement des égalités de points : ";
				print_r($groupeTriPoints);
				echo "<br />";
				$etatMemoire = memory_get_usage();
				echo "memory_get_usage : ".$etatMemoire;
				echo "<br />";
			}



			$tableau=triParPointsInterne($informations,$groupeTriPoints,$debug);
			if($debug){
				echo "<br />Tableau de groupement des égalités de points interne : ";
				print_r($tableau);
				echo "<br />";
				$etatMemoire = memory_get_usage();
				echo "memory_get_usage : ".$etatMemoire;
				echo "<br />";
			}

			$tableau=triDifferenceScorePointsInterne($informations,$tableau,$debug);
			if($debug){
				echo "<br />Tableau de groupement des égalités de différence de points(score) interne : ";
				print_r($tableau);
				echo "<br />";
				$etatMemoire = memory_get_usage();
				echo "memory_get_usage : ".$etatMemoire;
				echo "<br />";
			}
			$tableau=triScorePointsMarquesInterne($informations,$tableau,$debug);
			if($debug){
				echo "<br />Tableau de groupement des égalités de points marqués interne : ";
				print_r($tableau);
				echo "<br />";
				$etatMemoire = memory_get_usage();
				echo "memory_get_usage : ".$etatMemoire;
				echo "<br />";
			}
			$tableau=triDifferenceScorePoints($informations,$tableau,$debug);
			if($debug){
				echo "<br />Tableau de groupement des égalités de goalaverage : ";
				print_r($tableau);
				echo "<br />";
				$etatMemoire = memory_get_usage();
				echo "memory_get_usage : ".$etatMemoire;
				echo "<br />";
			}
			$tableau=triScorePointsMarques($informations,$tableau,$debug);
			if($debug){
				echo "<br />Tableau de groupement des points marques : ";
				print_r($tableau);
				echo "<br />";
				$etatMemoire = memory_get_usage();
				echo "memory_get_usage : ".$etatMemoire;
				echo "<br />";
			}
			$tableau=triEgaliteParfaite($informations,$tableau,$debug);
			if($debug){
				echo "<br />Tableau de groupement des égalités parfaite : ";
				print_r($tableau);
				echo "<br />";
				$etatMemoire = memory_get_usage();
				echo "memory_get_usage : ".$etatMemoire;
				echo "<br />";
			}

	    	/* AFFICHAGE CLASSEMENT */

			if(($idTour == 4000 AND $nbMatchGagnantPlayoff>1) OR ($idTour == 3000 AND $nbMatchGagnantPlayout>1) OR ($idTour == 2000 AND $nbMatchGagnantPromoReleg>1) OR ($idTour == 10000 AND $nbMatchGagnantTourFinal>1)) { // Play-off, Play-out, Promotion/Relegation ou tour final de + de 1 match
				$typeClassement=1;
			}
			elseif(($idTour == 4000 AND $nbMatchGagnantPlayoff==1) OR ($idTour == 3000 AND $nbMatchGagnantPlayout==1) OR ($idTour == 2000 AND $nbMatchGagnantPromoReleg==1) OR ($idTour == 10000 AND $nbMatchGagnantTourFinal==1)){ // Play-off, Play-out, Promotion/Relegation, tour final de 1 match
				$typeClassement=2;
			}
			else{ //Tour normal
				$typeClassement=3;
			}

			if(!isset($idCategoriePrecedenteBoucle) OR $idCategoriePrecedenteBoucle!=$idCategorie){
				$idTourPrecedenteBoucle = 0;
				$noGroupePrecedenteBoucle = 0;
				echo "<h3>".$nomCategorie[$idCategorie]."</h3>";
			}
			if((!isset($idTourPrecedenteBoucle) OR $idTourPrecedenteBoucle!=$idTour) AND $idTour!=2000){
				$noGroupePrecedenteBoucle = 0;
				echo "<h4>".$nomTour[$idTour]."</h4>";
			}
			if((!isset($noGroupePrecedenteBoucle) OR $noGroupePrecedenteBoucle!=$noGroupe) AND $noGroupe!=0){
				echo "<h5>".VAR_LANG_GROUPE." ".$noGroupe."</h5>";
			}
			echo "<table class='tableauClassementAccueil'>";

			if($idTour!=10000){ // Pas un tour final
				$i=1;
				for($k=1;$k<count($tableau);$k++){
					$idEquipe=$tableau[$k][1];
					$requete="SELECT equipe, nbMatchJoue, nbMatchGagne, nbMatchNul, nbMatchPerdu, nbMatchForfait, nbPointMarque, nbPointRecu, goolaverage, points FROM Championnat_Equipes, Championnat_Equipes_Tours WHERE Championnat_Equipes.idEquipe=".$idEquipe." AND Championnat_Equipes_Tours.idEquipe=Championnat_Equipes.idEquipe AND saison=".$annee." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$noGroupe."";
					if($debug){
						echo "<br /><br />Affichage des équipes qui ne sont pas à égalité : ".$requete;
					}
					$retourEquipeClassement=mysql_query($requete);
					$donneesEquipeClassement=mysql_fetch_array($retourEquipeClassement);
					if($i%2==0){
	                    $style = "background-color:".$couleurLigneA.";";
					}
					else{
	                    $style = "background-color:".$couleurLigneB.";";
					}
					echo "<tr style='".$style."'>";
					if(isset($idTourPrecedenteBoucle) AND $idTourPrecedenteBoucle==$idTour AND $noGroupePrecedenteBoucle!=$noGroupe){ //On ne doit pas remettre le classement à zéro !
						$classement=$k+$dernierClassement-1;
					}
					else{
						$classement=$k;
					}
					echo "<td>".$donneesEquipeClassement['equipe']."</td>";
					echo "<td>".afficherRang($idTour, $typeClassement, $donneesEquipeClassement['nbMatchGagne'], $donneesEquipeClassement['nbMatchPerdu'], $nbMatchGagnantPromoReleg, $nbMatchGagnantTourFinal, $classement)."</td>";
					echo "</tr>";
					$i++;
				}
			}
			else{ //Tour final. Il faut aussi classer par le type de match.
			    /*$requeteC = "SELECT DISTINCT Championnat_Matchs.saison, Championnat_Matchs.idCategorie, Championnat_Matchs.idTour, Championnat_Matchs.noGroupe, Championnat_Equipes_Tours.idEquipe, idTypeMatch, points, goolaverage, nbMatchJoue, nbMatchGagne, nbMatchPerdu, nbMatchForfait, nbPointMarque, nbPointRecu, equipe
				FROM Championnat_Equipes_Tours, Championnat_Matchs, Championnat_Equipes
				WHERE Championnat_Equipes_Tours.saison =".$annee."
				AND Championnat_Matchs.saison =".$annee."
				AND Championnat_Equipes_Tours.idCategorie =".$idCategorie."
				AND Championnat_Matchs.idCategorie =".$idCategorie."
				AND Championnat_Equipes_Tours.idTour =".$idTour."
				AND Championnat_Matchs.idTour =".$idTour."
				AND Championnat_Equipes_Tours.noGroupe =".$noGroupe."
				AND Championnat_Matchs.noGroupe =".$noGroupe."
				AND (
				equipeA = Championnat_Equipes_Tours.idEquipe
				OR equipeB = Championnat_Equipes_Tours.idEquipe
				)
				AND Championnat_Equipes.idEquipe = Championnat_Equipes_Tours.idEquipe
				ORDER BY Championnat_Matchs.idTypeMatch, nbMatchGagne DESC, Championnat_Equipes_Tours.points DESC , Championnat_Equipes_Tours.goolaverage DESC
				LIMIT 0 , 30";*/
		    $requeteC = "SELECT DISTINCT cm.saison, cm.idCategorie, cm.idTour, cm.noGroupe, cet.idEquipe, idTypeMatch, points, goolaverage, nbMatchJoue, nbMatchGagne, nbMatchPerdu, nbMatchForfait, nbPointMarque, nbPointRecu, position, equipe
						 FROM Championnat_Equipes_Tours cet, Championnat_Matchs cm, Championnat_Equipes ce
						 WHERE cet.saison =".$annee."
						 AND cm.saison =".$annee."
						 AND cet.idCategorie =".$idCategorie."
						 AND cm.idCategorie =".$idCategorie."
						 AND cet.idTour =".$idTour."
						 AND cm.idTour =".$idTour."
						 AND cet.noGroupe =".$noGroupe."
						 AND cm.noGroupe =".$noGroupe."
						 AND (equipeA = cet.idEquipe
						      OR equipeB = cet.idEquipe)
						 AND ce.idEquipe = cet.idEquipe
						 ORDER BY cm.idTypeMatch, nbMatchGagne DESC, cet.points DESC , cet.goolaverage DESC
						 LIMIT 0 , 30";
				$retourC = mysql_query($requeteC);
				$i=1;
				while($donneesC = mysql_fetch_array($retourC)){
					$idTypeMatch=$donneesC['idTypeMatch'];
					$nbMatchGagne=$donneesC['nbMatchGagne'];
					$nbMatchPerdu=$donneesC['nbMatchPerdu'];
					$nbMatchForfait=$donneesC['nbMatchForfait'];
					$nbMatchJoue=$nbMatchPerdu+$nbMatchGagne+$nbMatchForfait;
					$nbPointMarque=$donneesC['nbPointMarque'];
					$nbPointRecu=$donneesC['nbPointRecu'];
					$goolaverage=$donneesC['goolaverage'];
				$position=$donneesC['position'];
					$equipe=$donneesC['equipe'];
					if($i%2==0){
	                    $style = "background-color:".$couleurLigneA.";";
					}
					else{
	                    $style = "background-color:".$couleurLigneB.";";
					}
					echo "<tr style='".$style."'>";
					$nbMatchGagnant = $nbMatchGagnantTourFinal;
					echo "<td>".$equipe."</td>";
					echo "<td>".afficherRang($idTour, $typeClassement, $nbMatchGagne, $nbMatchPerdu, $nbMatchGagnantPromoReleg, $nbMatchGagnantTourFinal, $position)."</td>";
					echo "</tr>";

					$i++;
				}
			}
			echo "</table>";

			$idCategoriePrecedenteBoucle=$idCategorie;
			$idTourPrecedenteBoucle=$idTour;
			$noGroupePrecedenteBoucle=$noGroupe;
			$dernierClassement=count($tableau);
		}
	}

    ?>

</div>
<div id="dernieresNews">
    <?php echo "<h4><a href='".VAR_HREF_PAGE_PRINCIPALE."?".VAR_HREF_LIEN_MENU."=51'>".VAR_LANG_DERNIERES_NEWS."</a></h4>"; ?>
    <ul>
    <?php
    $requete = mysql_query("SELECT * FROM News ORDER by date DESC LIMIT 0,5");
    while($donnees = mysql_fetch_array($requete)) {
        $titre = $donnees['titre'.$_SESSION["__langue__"]];
        if($titre==""){
            $titre = $donnees['titre'.$VAR_TABLEAU_DES_LANGUES[0][0]];
        }
        echo "<li>";
        echo date_sql2date($donnees["date"]);
        echo " : ";
        echo "<a href='/news/".$donnees['id']."'>".$titre."</a>";
        echo "</li>";
    }
    ?>
    </ul>
</div>
<div id="prochainsEvenements">
    <?php echo "<h4><a href='".VAR_HREF_PAGE_PRINCIPALE."?".VAR_HREF_LIEN_MENU."=4'>".VAR_LANG_PROCHAINS_EVENEMENTS."</a></h4>"; ?>
    <ul>
    <?php
    $aujourdhui = date_actuelle();
    $requete = mysql_query("SELECT * FROM Calendrier_Evenements WHERE dateDebut > '".$aujourdhui."' AND dateDebut != 0 ORDER BY dateDebut LIMIT 0,5");
    while($donnees = mysql_fetch_array($requete)) {
        echo "<li>";
        echo date_sql2date($donnees["dateDebut"]);
        echo " : ";
        echo "<a href='calendrier-evenement-".$donnees['id']."'>".$donnees["titre"]."</a>";
        echo "</li>";

    }
    ?>
    </ul>
</div>
<div id="prochainsMatchs">
    <?php echo "<h4><a href='".VAR_HREF_PAGE_PRINCIPALE."?".VAR_HREF_LIEN_MENU."=22'>".VAR_LANG_PROCHAINS_MATCHS."</a></h4>"; ?>
    <ul>
    <?php
    $aujourdhui = date_actuelle();
    $requete = mysql_query("SELECT * FROM Championnat_Matchs WHERE dateDebut >= '".$aujourdhui."' AND dateDebut != 0 ORDER BY dateDebut LIMIT 0,5");
    while($donnees = mysql_fetch_array($requete)) {
        echo "<li>";
        echo date_sql2date($donnees["dateDebut"]);
        echo " : ";
        echo "<a href='calendrier-evenement-M".$donnees['idMatch']."'>";
        $requeteA = "SELECT * FROM Championnat_Equipes WHERE idEquipe=".$donnees['equipeA']."";
        $retourA = mysql_query($requeteA);
        $donneesA = mysql_fetch_array($retourA);
        echo $donneesA['equipe'];
        echo " - ";
        $requeteA = "SELECT * FROM Championnat_Equipes WHERE idEquipe=".$donnees['equipeB']."";
        $retourA = mysql_query($requeteA);
        $donneesA = mysql_fetch_array($retourA);
        echo $donneesA['equipe'];
        if($donnees['dateReportDebut']!='0000-00-00' AND $donnees['dateReportFin']!='0000-00-00' AND $donnees['heureReportDebut']!='00:00:00' AND $donnees['heureReportFin']!='00:00:00'){
            echo " : Match reporté au ".date_sql2date($donnees['dateReportDebut']);
        }
        echo "</a></li>";

    }
    ?>
    </ul>
</div>
