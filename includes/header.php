<header>
    <nav id="language-switcher">
        <ul>
            <?php
            $lien = '';
            if (isset($_GET['lien']) && is_numeric($_GET['lien'])) {
                $lien = $_GET['lien'];
            }

            for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
                if ($_SESSION["__langue__"] == $VAR_TABLEAU_DES_LANGUES[$i][0]) {
                    echo '<li>' . $VAR_TABLEAU_DES_LANGUES[$i][1] . '</li>';
                } else {
                    echo '<li><a class="menu" href="/switchlang/' . $VAR_TABLEAU_DES_LANGUES[$i][0] . '/' . $lien . '">' . $VAR_TABLEAU_DES_LANGUES[$i][1] . '</a></li>';
                }
            }
            ?>
        </ul>
    </nav>
    <a href="/" id="banner"></a>
</header>
