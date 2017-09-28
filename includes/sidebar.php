<div id="sidebar" class="sidebar">
    <div class="sidebar__username">
        <?php echo VAR_LANG_USER . ': ' . $_SESSION["__username__"] ?>
    </div>
    <?php include($_SERVER["DOCUMENT_ROOT"] . '/includes/nav.php'); ?>
    <div id="partners">
        <?php
        echo "Nos partenaires";
        $requeteSQL = "SELECT * FROM Sponsors WHERE afficherFooter>='1' ORDER BY afficherFooter";
        $recordset = mysql_query($requeteSQL);
        while ($record = mysql_fetch_array($recordset)) {
            echo "<a href='" . $record["lienWeb"] . "' target='_blank'>";
            echo "<img class='sidebar__sponsor-logo' src='/" . $record["lienLogoSidebar"] . "' />";
            echo "</a>";
        }
        ?>
    </div>
    <div class="sidebar__hashtag">#tchouksuisse</div>
    <div class="sidebar__social">
        <a href="https://facebook.com/swisstchoukball" target="_blank" class="sidebar__social__facebook">
            <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/images/ui/facebook.svg"); ?>
        </a>
        <a href="https://twitter.com/swisstchoukball" target="_blank" class="sidebar__social__twitter">
            <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/images/ui/twitter.svg"); ?>
        </a>
        <a href="https://instagram.com/swisstchoukball" target="_blank" class="sidebar__social__instagram">
            <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/images/ui/instagram.svg"); ?>
        </a>
    </div>
<?php
echo '</div>';
// This closing div tag is printed with PHP in order to avoid having any character after.
// This would alter seriously the layout of the website as the sidebar and the main content
// are two inline-block elements.
