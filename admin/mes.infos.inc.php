<div><?php
    statInsererPageSurf(__FILE__);

    $requeteSQL = "SELECT *, c.club FROM `Personne` p,`ClubsFstb` c WHERE p.`nom`='" . addslashes($_SESSION["__nom__"]) . "' AND p.`prenom`='" . addslashes($_SESSION["__prenom__"]) . "' AND p.`idClub`=c.`id`";

    $recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

    $record = mysql_fetch_array($recordset);

    echo "<h4>" . $record["nom"] . "&nbsp;" . $record["prenom"] . "</h4>";
    echo "<div>";
    echo VAR_LANG_USERNAME . " : " . $record["username"] . "<br />";
    echo "Email : ";
    echo email($record["email"]);
    echo "<br />";
    echo "Club : " . $record["club"] . "<br />";
    echo "</div>";

    // personne avec experience
    if ($record["experience"] != "") {
        echo "<h4>Exéprience</h4>";
        echo "<div class='mesInfos'>";
        echo nl2br($record["experience"]) . "<br />";
        echo "</div>";
    }
    ?>
</div>
