<main>
    <?php
    $currentMenuItem = $navigation->getCurrentMenuItem();

    echo '<!-- ID de la page : ' . $currentMenuItem['id'] . ' -->';

    // Affichage du titre de la page
    if (isset($_GET['login'])) {
        echo "<h1>Login administration</h1>";
    } elseif ($currentMenuItem['showTitle']) {
        echo "<h1>" . $currentMenuItem['title'] . "</h1>";
    }
    // Affichage du contenu de la page

    if (($ETAT_EN_MAINTENANCE_TOTALE && $_SESSION["__userLevel__"] != 0) || ($admin && $ETAT_ADMIN_EN_MAINTENANCE && $_SESSION["__userLevel__"] != 0)) {
        printMessage('Maintenance en cours. Merci de votre compréhension.');
    } else {
        if (isset($_GET['login'])) {
            include $_SERVER["DOCUMENT_ROOT"] . "/pages/login.inc.php";
        } elseif (isset($_GET['contact'])) {
            include $_SERVER["DOCUMENT_ROOT"] . "/pages/contact.inc.php";
        } elseif (!empty($currentMenuItem['filePath']) && is_file($currentMenuItem['filePath'])) {
            if ($admin && $_SESSION["__userLevel__"] >= 100) {
                printErrorMessage('Accès refusé');
            } else {
                include $currentMenuItem['filePath'];
            }
        } else {
            printMessage(VAR_LANG_EN_CONSTRUCTION);
        }
    }
    ?>
</main>
