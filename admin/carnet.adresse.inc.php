<?php
statInsererPageSurf(__FILE__);
$motsRecherches = mysql_real_escape_string($_POST['motsRecherches']);
?>
<form action="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>" method="post"><br>
    <p align='center'>
        <input type="text" title="Search" name="motsRecherches" size="35" value='<?php echo $motsRecherches; ?>'>
        <input type="submit" class="button button--primary" value="Rechercher">
    </p>
</form>


<script language="JavaScript">
    function validerSuppression() {
        return confirm("Etes-vous sur de vouloir supprimer ce contact ?");
    }
</script>

<table class="st-table">
    <?php
    try {
        $users = UserService::getUserList($_POST['motsRecherches']);
    }
    catch(Exception $exception) {
        die($exception->getMessage());
    }

    echo "<tr>";
    echo "<th>Personne</th>";
    echo "<th>Club</th>";
    echo "<th>Email</th>";
    if ($_SESSION["__userLevel__"] <= 5) {
        echo "<th>Fonctions avancées</th>";
    }
    echo "</tr>";
    foreach ($users as $user) {
        echo "<tr>";

        echo "<td>" . stripslashes($user["nom"]) . "&nbsp;" . stripslashes($user["prenom"]) . "</td>";

        echo "<td>" . $user["club"] . "</td>";

        echo "<td>";
        if ($user["email"] != "") {
            echo email($user["email"]);
        } else {
            echo "&nbsp;";
        }
        echo "</td>";

        if ($_SESSION["__userLevel__"] <= 5) {
            echo "<td>";
            if ($user['userLevel'] > 0) {
                echo "<a href='?" . VAR_HREF_LIEN_MENU . "=2&modificationId=" . $user["idPersonne"] . "'>modifier</a>";
            }
            if ($user['userLevel'] > 9) {
                echo "<br />";
                echo "<a href='?" . VAR_HREF_LIEN_MENU . "=2&suppressionId=" . $user["idPersonne"] . "' onClick='return validerSuppression();'>supprimer</a>";
            }
            echo "</td>";
        }
        echo "</tr>";
    }
    ?>
</table>
