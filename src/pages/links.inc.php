<?
    statInsererPageSurf(__FILE__);
?>
<div class="links">
<?
    $retourcount = mysql_query("SELECT COUNT(*) AS nbrecategories FROM LiensGroupe");
    $donneescount = mysql_fetch_array($retourcount);
    $nbrecategories = $donneescount["nbrecategories"];
    if($nbrecategories%2 == 0) {
        $nbreCategoriesColonne1 = $nbrecategories/2;
        $nbreCategoriesColonne2 = $nbrecategories/2;
    }
    else {
        $nbreCategoriesColonne1 = ($nbrecategories/2)-0.5;
        $nbreCategoriesColonne2 = ($nbrecategories/2)+0.5;
    }
echo "<table>";
    echo "<tr>";
        echo "<td class='colonneLiens'>";
            $retour = mysql_query("SELECT * FROM LiensGroupe ORDER BY ordre LIMIT 0,".$nbreCategoriesColonne1."");
            while($donnees = mysql_fetch_array($retour)) {
                echo "<h4>".$donnees["nomGroupe".$_SESSION["__langue__"]]."</h4>";
                echo "<ul>";
                            
                $retourbis = mysql_query("SELECT * FROM Liens WHERE idLiensGroupe = '".$donnees["id"]."' ORDER BY nom".$_SESSION["__langue__"]);
                while($donneesbis = mysql_fetch_array($retourbis)) {
                    echo "<li><a href='".$donneesbis["source"]."' target='_blank'>".$donneesbis["nom".$_SESSION["__langue__"]]."</a></li>";
                }
                echo "</ul>";
                
            }
        echo "</td>";
        echo "<td class='colonneLiens'>";
            $retour = mysql_query("SELECT * FROM LiensGroupe ORDER BY ordre LIMIT ".$nbreCategoriesColonne1.",".$nbreCategoriesColonne2."");
            while($donnees = mysql_fetch_array($retour)) {
                echo "<h4>".$donnees["nomGroupe".$_SESSION["__langue__"]]."</h4>";
                echo "<ul>";
                            
                $retourbis = mysql_query("SELECT * FROM Liens WHERE idLiensGroupe = '".$donnees["id"]."' ORDER BY nom".$_SESSION["__langue__"]);
                while($donneesbis = mysql_fetch_array($retourbis)) {
                    echo "<li><a href='".$donneesbis["source"]."' target='_blank'>".$donneesbis["nom".$_SESSION["__langue__"]]."</a></li>";
                }
                echo "</ul>";
                
            }
        echo "</td>";
    echo "</tr>";
echo "</table>";
?>

</div>
