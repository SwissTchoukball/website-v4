<nav id="main-nav">
    <?php
    $navQuery = "SELECT sm.id, m.nom" . $_SESSION['__langue__'] . " AS menu, sm.nomFr AS sousmenu, m.id AS sousMenuDeId,
                    sm.ordre, m.userLevel AS parentUserLevel, sm.userLevel,
                    sm.urlRewriting AS link, m.urlRewriting AS parentLink, sm.lienExterneSite AS isExternalLink
             FROM " . $typemenu . " m
             LEFT OUTER JOIN " . $typemenu . " sm ON sm.sousMenuDeId = m.id
             WHERE m.sousMenuDeId = -1
             ORDER BY m.ordre, sm.ordre";
    if (!$navResult = mysql_query($navQuery)) {
        printErrorMessage('Erreur lors du chargement du menu');
    } else {
        $previousNavParentItemID = 0;
        while ($nav = mysql_fetch_assoc($navResult)) {
            $navParentItemID = $nav['sousMenuDeId'];
            $navItemOrder = $nav['ordre'];
            $navParentItemName = $nav['menu'];
            $navItemName = $nav['sousmenu'];
            $navParentUserLevel = $nav['parentUserLevel'];
            $navUserLevel = $nav['userLevel'];
            if ($nav['link'] != '') {
                $navLink = $nav['link'];
            } else {
                $srcFile = $admin ? 'admin.php' : 'index.php';
                $navLink = $srcFile . '?menuselection=' . $navParentItemID . '&smenuselection=' . $navItemOrder;
            }
            if (!$nav['isExternalLink']) {
                $navLink = '/' . $navLink;
            }
            $navParentLink = $nav['parentLink'];


            if ($navParentUserLevel >= $_SESSION['__userLevel__']) {
                if ($navParentItemID != $previousNavParentItemID) {
                    if ($previousNavParentItemID != 0) {
                        echo '</ul>';
                    }

                    echo $menuselection == $navParentItemID ? '<h1 class="open">' : '<h1>';

                    if (is_null($navItemOrder) && $navParentLink != '') {
                        echo '<a href="/' . $navParentLink . '">' . $navParentItemName . '</a>';
                    } else {
                        echo $navParentItemName;
                    }

                    echo '</h1>';
                    echo $menuselection == $navParentItemID ? '<ul style="display: block;">' : '<ul>';

                }

                if ($navUserLevel >= $_SESSION['__userLevel__']) {
                    $navItemClass = ($menuselection == $navParentItemID && $smenuselection == $navItemOrder) ? 'open' : '';
                    echo '<li><a href="' . $navLink . '" class="' . $navItemClass . '">' . $navItemName . '</a></li>';
                }
            }

            $previousNavParentItemID = $navParentItemID;
        }
    }
    ?>
</nav>
