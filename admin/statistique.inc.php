<div class="statistiquesGlobales">
<?		statInsererPageSurf(__FILE__); ?>
<p align="center">
<img src="<? echo "image_gif.php?image=".FICHIER_GRAPHE_STAT_LANGUE;?>">
</p>
<h3>Pages les plus surfées</h3>
<table class="tableauStatistiquesGlobales">
    <caption>Partie WEB</caption>
<?
// $TypePage : admin / page normale
function afficherStatsSurf($typePage,$mois,$annee, $nbPageAffichee){
	global $VAR_G_MOIS;
	
	echo "<tr>";
        echo "<td class='moisStatistique' colspan='2'>".$VAR_G_MOIS[$mois-1]." ".$annee."</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<th>Pages</th>";
		echo "<th>Nombre de fois</th>";
	echo "</tr>";
	
	$requeteSQL = "SELECT * FROM `StatistiqueSurf` WHERE `page` LIKE '%".$typePage."%' AND mois='".$mois."' AND annee='".$annee."' ORDER BY `nbFois` DESC LIMIT ".$nbPageAffichee;
	$recordset = mysql_query($requeteSQL);
	while($record = mysql_fetch_array($recordset)){
		$start = strpos($record["page"],$typePage)+strlen($typePage);
		$page = substr($record["page"],$start,strlen($record["page"])-$start);
	
		echo "<tr><td>".$page."</td>";
        echo "<td>".$record["nbFois"]."</td></tr>";
	}
}

$nbPageAffichee = 10;

afficherStatsSurf(VAR_HREF_PATH_PAGE_PRINCIPALE,date('m'),date('Y'),$nbPageAffichee);
afficherStatsSurf(VAR_HREF_PATH_PAGE_PRINCIPALE,date('m')-1,date('Y'),$nbPageAffichee);
afficherStatsSurf(VAR_HREF_PATH_PAGE_PRINCIPALE,date('m')-2,date('Y'),$nbPageAffichee);
?></table>
<br>
<table class="tableauStatistiquesGlobales">
    <caption>Partie ADMIN</caption><?
$nbPageAffichee = 5;

afficherStatsSurf(VAR_HREF_PATH_ADMIN,date('m'),date('Y'),$nbPageAffichee);
afficherStatsSurf(VAR_HREF_PATH_ADMIN,date('m')-1,date('Y'),$nbPageAffichee);
afficherStatsSurf(VAR_HREF_PATH_ADMIN,date('m')-2,date('Y'),$nbPageAffichee);


?></table>
</div>