<div id="sidebar">
	<?php include('includes/nav.php'); ?>
	<div id="partners">
		<?php
		echo "<a href='/partenaires'>Nos partenaires</a>";
        $requeteSQL = "SELECT * FROM Sponsors WHERE afficherFooter>='1' ORDER BY afficherFooter";
        $recordset= mysql_query($requeteSQL);
        while($record = mysql_fetch_array($recordset)){
            echo "<a href='".$record["lienWeb"]."' target='_blank'>";
            echo "<img class='logoSponsorsMenu' src='/".$record["lienLogoSidebar"]."' />";
            echo "</a>";
        }
		?>
	</div>
	<div class="hashtag">#tchouksuisse</div>
</div>