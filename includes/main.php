<main>
    <?php

    if (isset($menuselection) && isset($smenuselection) && isset($typemenu)) {
        $requete = "SELECT id, lien, nom" . $_SESSION['__langue__'] . " AS nom, showTitle
                    FROM " . $typemenu . "
                    WHERE sousMenuDeId=" . $menuselection . "
                    AND ordre=" . $smenuselection . "
                    LIMIT 1";
        if ($result = mysql_query($requete)) {
            $page = mysql_fetch_assoc($result);
            $idPage = $page['id'];
            $contenupage = $page['lien'];
            $titrepagebis = $page['nom'];
            $showTitle = $page['showTitle'] == 1;
        }
    }
    echo '<!-- ID de la page : ' . $idPage . ' -->';

    // Affichage du titre de la page
    if (isset($_GET['login'])) {
        echo "<h1>Login administration</h1>";
    } elseif (isset($menuselection) && $showTitle) {
        if ($typemenu == 'menu' && ($menuselection == '4' || $menuselection == '10' || $menuselection == '12' || $menuselection == '13')) {
            // N'ont pas de sous-menus.
            echo "<h1>" . $titrepage . "</h1>";
        } else {
            if (isset($smenuselection)) {
                echo "<h1>" . $titrepagebis . "</h1>";
            } else {
                echo "<h1>" . $titrepage . "</h1>";
            }
        }
    }
    // Affichage du contenu de la page

    if (($ETAT_EN_MAINTENANCE_TOTALE && $_SESSION["__userLevel__"] != 0) || ($admin && $ETAT_ADMIN_EN_MAINTENANCE && $_SESSION["__userLevel__"] != 0)) {
        printMessage('Maintenance en cours. Merci de votre compréhension.');
    } else {
        if (isset($_GET['login'])) {
            include $_SERVER["DOCUMENT_ROOT"] . "/pages/login.inc.php";
        } elseif (isset($_GET['contact'])) {
            include $_SERVER["DOCUMENT_ROOT"] . "/pages/contact.inc.php";
        } elseif (!empty($contenupage) && is_file($contenupage)) {
            if ($admin && $_SESSION["__userLevel__"] >= 100) {
                printErrorMessage('Accès refusé');
            } else {
                include $contenupage;
            }
        } else {
            printMessage(VAR_LANG_EN_CONSTRUCTION);
        }
    }
    ?>
</main>
