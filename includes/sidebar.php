<div id="sidebar">
    <?php include('includes/nav.php'); ?>
    <div id="partners">
        <?php
        echo "<a href='/partenaires'>Nos partenaires</a>";
        $requeteSQL = "SELECT * FROM Sponsors WHERE afficherFooter>='1' ORDER BY afficherFooter";
        $recordset= mysql_query($requeteSQL);
        while ($record = mysql_fetch_array($recordset)) {
            echo "<a href='".$record["lienWeb"]."' target='_blank'>";
            echo "<img class='logoSponsorsMenu' src='" . PATH_TO_ROOT . "".$record["lienLogoSidebar"]."' />";
            echo "</a>";
        }
        ?>
    </div>
    <div class="hashtag">#tchouksuisse</div>
<?php
echo '</div>';
// This closing div tag is printed with PHP in order to avoid having any character after.
// This would alter seriously the layout of the website as the sidebar and the main content
// are two inline-block elements.
