<header>
    <nav id="language-switcher">
        <ul>
            <?php
            $lien = '';
            if (isset($_GET['lien']) && is_numeric($_GET['lien'])) {
                $lien = $_GET['lien'];
            }

            // Admin link
            if ($_SESSION["__userLevel__"] < 100) {
                echo "<li>";
                if ($admin) {
                    echo "<a href='" . VAR_HREF_PAGE_PRINCIPALE . "'>" . VAR_LANG_ACCUEIL . "</a>";
                } else {
                    echo "<a href='/admin'>" . VAR_LANG_ADMINISTRATION . "</a>";
                }
                echo "</li>";
                ?>

                <script type="text/javascript">
                    var stAuthdata = '<?php echo $_SESSION['__authdata__']; ?>';
                </script>

                <?php
            }

            // Language switcher
            for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
                if ($_SESSION["__langue__"] == $VAR_TABLEAU_DES_LANGUES[$i][0]) {
                    echo '<li>' . $VAR_TABLEAU_DES_LANGUES[$i][0] . '</li>';
                } else {
                    echo '<li><a href="/switchlang/' . $VAR_TABLEAU_DES_LANGUES[$i][0] . '/' . $lien . '">' . $VAR_TABLEAU_DES_LANGUES[$i][0] . '</a></li>';
                }
            }
            ?>
        </ul>
    </nav>
    <a href="/" id="banner"></a>
</header>
