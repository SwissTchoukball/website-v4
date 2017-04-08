<footer>
    <div id="adminLinks">
        <?php
        if ($_SESSION["__userLevel__"] < 100) {
            echo "<a href='/logout'>" . VAR_LANG_DECONNEXION . "</a>";

            if ($admin) {
                echo " &loz; <a href='" . VAR_HREF_PAGE_PRINCIPALE . "'>" . VAR_LANG_ACCUEIL . "</a>";
            } else {
                echo " &loz; <a href='/admin'>" . VAR_LANG_ADMINISTRATION . "</a>";
            }
        } else {
            echo "<a href='/login'>" . VAR_LANG_SE_LOGUER . "</a>";
        }
        ?>
    </div>

    <div id="footerLinks">
        <a href="/contact">Contact</a>
        &loz; <a href="/impressum">Impressum</a>
        &loz; <a href="/rss.php?lang=<?php echo $_SESSION['__langue__']; ?>">RSS</a>
        &loz; <?php echo VAR_LANG_ASSOCIATION_NAME; ?>, 1000 Lausanne
    </div>
    <div id="copyright">
        Copyright &copy; <?php echo date('Y'); ?> <?php echo VAR_LANG_ASSOCIATION_NAME; ?>, tous droits r�serv�s
    </div>
</footer>
