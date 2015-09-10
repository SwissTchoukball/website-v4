<?
if (isset($_GET["download"]) && $_GET["download"] == 'true') {
/*
?>

	<form name="chtbSaison" action="" method="post">
        <table border="0" align="center">
            <tr>
                <td>
                    <p><? echo $agenda_annee; ?> :</p>
                </td>
                <td>
                    <select name="annee" id="select" onChange="chtbSaison.submit();">
                     <? 
                    // recherche de la premiere date
                    $requeteAnneeMin = "SELECT MIN( Download.date ) FROM `Download` WHERE idType='8' AND date <> '0000-00-00'";
                    $recordset = mysql_query($requeteAnneeMin) or die ("<H3>Aucune date existe</H3>");
                    $dateMin = mysql_fetch_array($recordset) or die ("<H3>erreur extraction</H3>");
                    $anneeMin = annee($dateMin[0]);
                    
                    //recherche de la derniere date
                    $requeteAnneeMax = "SELECT MAX( Download.date ) FROM `Download` WHERE idType='8' AND date <> '0000-00-00'";
                    $recordset = mysql_query($requeteAnneeMax) or die ("<H3>Aucune date existe</H3>");
                    $dateMax = mysql_fetch_array($recordset) or die ("<H3>erreur extraction</H3>");
                    $anneeMax = annee($dateMax[0]);
                    $moisMax = mois($dateMax[0]);
                    //Si le dernier numéro est sortie entre janvier et août de l'année X,
                    //il fait parti de la saison X-1 - X.
                    //Sinon, de la saison X - X+1.
                    if ($moisMax < 9){
                    	$anneeMax--;
                    }
                    
                    if($_POST['annee'] == ""){
                        $annee = annee(date_actuelle()) - 1;
                        if(mois(date_actuelle())<9){
                            $annee--;
                        }
                    } else {
	                    $annee = $_POST['annee'];
                    }
                    for($i=$anneeMin;$i<=$anneeMax;$i++){
                        if($annee == $i)
                            echo "<option selected value='$i'>".VAR_LANG_SAISON." $i-".($i+1)."</option>";
                        else
                            echo "<option value='$i'>".VAR_LANG_SAISON." $i-".($i+1)."</option>";
                    }			
                    ?></select>
                </td>
            </tr>
        </table>
	</form>

	<? */
		$dateMin = $annee."-09-01";
		$dateMax = ($annee+1)."-09-01";
		//$requete = "SELECT * FROM `Download` WHERE idType='8' AND date >= '$dateMin ' AND date < '$dateMax' ORDER BY `id`";
		$requete = "SELECT * FROM `Download` WHERE idType='8' ORDER BY `id` DESC";

		$recordset = mysql_query($requete) or die ("<H3>Aucune date existe</H3>");
		//encadrerHaut();
		
		
		$nbMaxCol = 3;
		$nbCol = 0;
		
		echo "<table class='tableauCHTB' align='center'>";
		echo "<tr>";
		while($record = mysql_fetch_array($recordset)){
					
			$imageFile = PATH_DOCUMENTS.$_SESSION["__langue__"]."_".substr($record["fichier"],0,strlen($record["fichier"])-3)."jpg";
			$pdfFile = PATH_DOCUMENTS.$_SESSION["__langue__"]."_".$record["fichier"];
			
			// par defaut la premiere langue.
			if(!is_file($imageFile)){
				$imageFile = PATH_DOCUMENTS.$VAR_TABLEAU_DES_LANGUES[0][0]."_".substr($record["fichier"],0,strlen($record["fichier"])-3)."jpg";
			}
			if(!is_file($pdfFile)){
				$pdfFile = PATH_DOCUMENTS.$VAR_TABLEAU_DES_LANGUES[0][0]."_".$record["fichier"];
			}	
			
		
			if($nbCol % $nbMaxCol == 0){
				echo "<td>";
				echo "<h3>".$record["titre".$_SESSION["__langue__"]]."</h3>";
				echo "<a href='$pdfFile'><img border='0' src='".$imageFile."'/></a>";
				echo "<p align='center'>".date_sql2MonthYear($record["date"],$VAR_G_MOIS)."</p>";
				echo "<p align='center'><a href='$pdfFile'>".tailleFichier($pdfFile)."</a></p>";			
				echo "</td>";
			}
			else if($nbCol % $nbMaxCol == $nbMaxCol-1){
				echo "<td>";			
				echo "<h3>".$record["titre".$_SESSION["__langue__"]]."</h3>";
				echo "<a href='$pdfFile'><img border='0' src='".$imageFile."'/></a>";
				echo "<p align='center'>".date_sql2MonthYear($record["date"],$VAR_G_MOIS)."</p>";
				echo "<p align='center'><a href='$pdfFile'>".tailleFichier($pdfFile)."</a></p>";
				echo "</td>";		
				echo "</tr>";
			}
			else{
				echo "<td>";			
				echo "<h3>".$record["titre".$_SESSION["__langue__"]]."</h3>";
				echo "<a href='$pdfFile'><img border='0' src='".$imageFile."'/></a>";
				echo "<p align='center'>".date_sql2MonthYear($record["date"],$VAR_G_MOIS)."</p>";
				echo "<p align='center'><a href='$pdfFile'>".tailleFichier($pdfFile)."</a></p>";
				echo "</td>";	
			}
			$nbCol++;
		}
		
		if($nbCol % $nbMaxCol == $nbMaxCol-1){
		}
		else if($nbCol % $nbMaxCol > 0){
			while($nbCol % $nbMaxCol < $nbMaxCol-1){
				$nbCol++;
			}
		}
		
		echo "</table>";
		echo "<p align='center'><a href='index.php?".gardemenuselection($menuselection,$smenuselection)."'>".VAR_LANG_RETOUR."</a></p>";
	
}
else{
?>
<div class="chtb">
<?
	$request="SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '21' ORDER BY paragrapheNum;";
	$recordset = mysql_query($request) or die ("<H3>Paragraphe inconnu</H3>");
	
	$textAppondu=$textAppondu."<p class='consulterArchivesTchoukUp'><a href='?download=true".gardemenuselection($menuselection,$smenuselection)."'>".VAR_LANG_CHTB_DOWNLOAD."</a></p>";
	//logo Get Adobe Reader
    //$textAppondu=$textAppondu."<p align='center'><a href='http://www.adobe.com/products/acrobat/readstep2.html' target='_blank'><img src='pictures/get_adobe_reader.gif' alt='Get Adobe Reader' /></a></p><br />";
	
	$i=0;
	while($text = mysql_fetch_array($recordset)){ // Affichage de tout les paragraphes
		if($i==0){ // premier paragraphe = titre + image
            // première image flottante
            $request2="SELECT fichier FROM Download WHERE idType = '8' ORDER BY date DESC LIMIT 0,1";
            $recordset2 = mysql_query($request2) or die  ("<H3>Image 1 inconnue</H3>");
            $record2 = mysql_fetch_array($recordset2);
            
            $imageFile = PATH_DOCUMENTS.$_SESSION["__langue__"]."_".substr($record2["fichier"],0,strlen($record2["fichier"])-3)."jpg";
			
			// par defaut la premiere langue.
			if(!is_file($imageFile)){
				$imageFile = PATH_DOCUMENTS.$VAR_TABLEAU_DES_LANGUES[0][0]."_".substr($record2["fichier"],0,strlen($record2["fichier"])-3)."jpg";
			}
            
            //titre
            $textAppondu=$textAppondu."<img class='imageFlottanteDroite' src='".$imageFile."' alt='".$record2["fichier"]."' />";
			$textAppondu=$textAppondu."<h3>".$text["paragraphe".$_SESSION["__langue__"]]."</h3>";
		}
		else{
            if($i==k){ // Deuxième image flottante masquée (il faut mettre un chiffre (correspondant à un §)à la place de k pour afficher)
            $request3="SELECT fichier FROM Download WHERE idType = '8' ORDER BY date DESC LIMIT 1,1";
            $recordset3 = mysql_query($request3) or die  ("<H3>Image 2 inconnue</H3>");
            $record3 = mysql_fetch_array($recordset3);
            
            $textAppondu=$textAppondu."<img class='imageFlottanteGauche' src='".PATH_DOCUMENTS.$_SESSION["__langue__"]."_".substr($record3["fichier"],0,strlen($record3["fichier"])-3)."jpg' alt='".$record3["fichier"]."' />";
            }
			$textAppondu=$textAppondu."<p align='justify'>".$text["paragraphe".$_SESSION["__langue__"]]."</p><br/>";
		}
		$i++;	
	}
	
	echo $textAppondu;
        
			echo "Email"." : "; 
			echo emailperso('redaction@tchoukball.ch','redaction@tchoukball.ch','Abonnement tchoukup'); 
			echo "<br>";
    echo "</div>";
}
?>
<fb:like-box href="http://www.facebook.com/tchoukup" width="600" height="62" show_faces="false" stream="false" header="false"></fb:like-box>

