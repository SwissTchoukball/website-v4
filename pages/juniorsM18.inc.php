<?
if($_SESSION["debug_tracage"])echo __FILE__."<BR>";
statInsererPageSurf(__FILE__);

$afficherNumero = false;
?>
<div class="equipe-es">
	<div class="photo-equipe-es"><img src="<? echo VAR_IMAGE_PHOTOS_EQUIPES_PATH."m18_f_2013-02-09.jpg";?>"></div>
	<div class="legende-photo-equipe-es"></div>
	<div class="photo-equipe-es"><img src="<? echo VAR_IMAGE_PHOTOS_EQUIPES_PATH."m18_m_2013-02-09.jpg";?>"></div>
	<div class="legende-photo-equipe-es"></div>
</div>
<div class="liste-joueur-es">
	<?
		include "affichage.team.inc.php";

		$query = getTeamQuery(3);
		$recordset = mysql_query($query) or die ("<H1>mauvaise requete</H1>");

		//$requeteSQL = "SELECT *, `equipeM18` AS `idPosteEquipe` FROM `Personne`, `ClubsFstb` WHERE `equipeM18`>'0' AND `ClubsFstb`.`id`=`Personne`.`idClub` ORDER BY `equipeM18`, `nom`, `prenom`";
		//$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");


		while($record = mysql_fetch_array($recordset)){
			afficherPersonneTeam($record,"_port",$afficherNumero);
		}
	?>
</div>

<div class="acceder-bdd-fitb-es"><a href="http://www.fitbcompetitions.org" target="_blank"><? echo VAR_LANG_ACCES_FITB_BDD; ?></a></div>