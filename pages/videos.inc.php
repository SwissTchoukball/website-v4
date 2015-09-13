<h3>Nos vidéos YouTube</h3>

<iframe class="last-yt-video fullsite" src="http://www.youtube.com/embed?max-results=1&controls=1&showinfo=1&rel=0&listType=user_uploads&list=tchoukballsuisse" frameborder="0" allowfullscreen></iframe>


<h3>Nos recommendations de chaînes YouTube</h3>
<div class="yt-channel-list">
	<div class="g-ytsubscribe" data-channel="tchoukballsuisse" data-layout="full" data-count="default"></div>
	<div class="g-ytsubscribe" data-channel="tchoukballpromotion" data-layout="full" data-count="default"></div>
	<div class="g-ytsubscribe" data-channel="youtchouk" data-layout="full" data-count="default"></div>
</div>
<?php
/*

if(isset($_GET['idVideo'])){
$idVideo=intval($_GET['idVideo']); //anti-injectionSQL
$requete="SELECT * FROM Videos WHERE id=".$idVideo;
$retour=mysql_query($requete);
$donnees=mysql_fetch_assoc($retour);
$fichier=$donnees['fichier'];
if(isset($_GET['partie'])){
	$partie=intval($_GET['partie']);
	$fichier=$donnees['fichier'.$partie];
}
?>
<p><a href="/videos">Retour à la liste des vidéos</a></p>
<h2><?php echo $donnees['nom'.$_SESSION['__langue__']]; ?></h2>
<video class="fullsite" controls autoplay>
	<source src="/Videos/videos/<?php echo $fichier; ?>.mp4" type="video/mp4">
</video>
<?php
}
else{
	?>
    <table class="videos">
    <?php
    $requeteA = "SELECT * FROM TypeVideos ORDER BY ordre";
    $retourA = mysql_query($requeteA);
    while($donneesA = mysql_fetch_array($retourA)) {
    	echo "<tr><th colspan='8'>";
    		echo $donneesA["nomType".$_SESSION["__langue__"]];
    	echo "</th></tr>";
    	//echo $requeteA;
    	$retour = mysql_query("SELECT * FROM Videos WHERE idTypeVideos = '".$donneesA["id"]."' ORDER BY date DESC");
    	while($donnees = mysql_fetch_array($retour)) {
			echo "<tr>";
			if(is_file($_ENV["DOCUMENT_ROOT"] . VAR_IMAGE_VIDEOS_PATH.$donnees["fichier"].".jpg")) {
				echo "<td>";
				echo "<img src='".VAR_IMAGE_VIDEOS_PATH.$donnees["fichier"].".jpg' alt='".$donnees["nom".$_SESSION["__langue__"]]."' />";
				echo "</td>";
				echo "<td>";
				echo $donnees["nom".$_SESSION["__langue__"]] . "<br />";
				//echo "</td>";
			}
			else {
				echo "<td colspan='2'>";
				echo $donnees["nom".$_SESSION["__langue__"]] . "<br />";
				//echo "</td>";
			}
			if(is_file($_ENV["DOCUMENT_ROOT"] . VAR_HREF_VIDEOS_PATH.$donnees["fichier"].".mp4")) {
				//echo "<td>";
				if(is_file($_ENV["DOCUMENT_ROOT"] . VAR_HREF_VIDEOS_PATH.$donnees["fichier2"].".mp4")){
					echo "<a href='/videos/".$donnees['id']."'>Voir la partie 1</a><br />";
					echo "<a href='".VAR_HREF_VIDEOS_PATH.$donnees["fichier"].".mp4'>Télécharger la partie 1 (".tailleFichier($_ENV["DOCUMENT_ROOT"] . VAR_HREF_VIDEOS_PATH.$donnees["fichier"].".mp4").")</a><br />";

					echo "<a href='/videos/".$donnees['id']."&partie=2'>Voir la partie 2</a><br />";
					echo "<a href='".VAR_HREF_VIDEOS_PATH.$donnees["fichier2"].".mp4'>Télécharger la partie 2 (".tailleFichier($_ENV["DOCUMENT_ROOT"] . VAR_HREF_VIDEOS_PATH.$donnees["fichier2"].".mp4").")</a><br />";
					if(is_file($_ENV["DOCUMENT_ROOT"] . VAR_HREF_VIDEOS_PATH.$donnees["fichier3"].".mp4")){
						echo "<a href='/videos/".$donnees['id']."&partie=3'>Voir la partie 3</a><br />";
						echo "<a href='".VAR_HREF_VIDEOS_PATH.$donnees["fichier3"].".mp4'>Télécharger la partie 3 (".tailleFichier($_ENV["DOCUMENT_ROOT"] . VAR_HREF_VIDEOS_PATH.$donnees["fichier3"].".mp4").")</a><br />";
					}
				}
				else{
					echo "<a href='/videos/".$donnees['id']."'>Voir</a><br />";
					echo "<a href='".VAR_HREF_VIDEOS_PATH.$donnees["fichier"].".mp4'>Télécharger (".tailleFichier($_ENV["DOCUMENT_ROOT"] . VAR_HREF_VIDEOS_PATH.$donnees["fichier"].".mp4").")</a>";
				}
				echo "</td>";
			}
			else {
				//echo "<td colspan='2'>";
				echo "N/A";
				echo "</td>";
			}
			echo "</tr>";
		}
    }
	?>
    </table>
	<?php
}
*/
?>
<script src="https://apis.google.com/js/platform.js"></script>
