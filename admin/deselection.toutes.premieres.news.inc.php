<div class="optionPremiereNews">
    <?php
    statInsererPageSurf(__FILE__);

    $requeteSQL = "UPDATE News SET premiereNews='0'";
    mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");
    echo "<h3>Opération effectuée avec succès</h3>";

    ?>
</div>
