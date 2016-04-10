<?php include_once('includes/init.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="keywords" content="Tchouk, Tchoukball, Tchouk-ball, Fair-play, Sport, sport pour tous, prix thulin, site officiel, official web site, Swiss Tchoukball, FSTB, Fédération Suisse de Tchoukball, Schweizerischer Tchoukball, Swiss Tchoukball Federation, Federazione Svizzera di Tchoukball" />
        <meta name="description" content="Tchoukball : Sport pour tous. venez decouvrir ce nouveau sport qui est le sport de demain. Site officiel de <?php echo VAR_LANG_ASSOCIATION_NAME_ARTICLE; ?>. En français. Auf Deutsch. In English. In italiano." />
        <meta name="Revisit-after" content="14 days">
        <meta http-equiv="Content-Language" content="<?php echo strtolower($_SESSION["__langue__"]);?>">
        <meta name="Identifier-url" content="http://www.tchoukball.ch">
        <!-- Icon for Pinned Tabs (Safari 9) -->
        <link rel="mask-icon" href="website_icon.svg" color="#e2001a">
        <!-- Favicons -->
        <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="/favicon-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/mstile-144x144.png">
        <!-- /Favicons -->
        <!-- Facebook metatags -->
        <meta property="og:title" content="<?php echo VAR_LANG_ASSOCIATION_NAME.$titre; ?>"/>
        <meta property="og:image" content="http://www.tchoukball.ch/pictures/Logo-SwissTchoukball_500.png"/>
        <meta property="og:type" content="<?php echo $facebook_type; ?>" />
        <meta property="og:url" content="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" />
        <meta property="og:site_name" content="<?php echo VAR_LANG_ASSOCIATION_NAME; ?>" />
        <meta property="og:description" content="<?php echo $description; ?>"/>
        <meta property="fb:app_id" content="119853652572"/>
        <meta property="fb:admins" content="817753010"/>
        <!-- /Facebook metatags -->
        <link rel="stylesheet" type="text/css" href="<?php echo PATH_TO_ROOT; ?>/styles/main.css">
        <link rel="stylesheet" type="text/css" href="<?php echo PATH_TO_ROOT; ?>/styles/livescores.css"> <!-- For Tchoukball World live scores -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="alternate" type="application/rss+xml" href="http://www.tchoukball.ch/rss<?php echo $_SESSION["__langue__"]; ?>.php" />
    </head>
    <body>
        <!--<script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '119853652572',
                    xfbml      : true,
                    version    : 'v2.1'
                });
            };

            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/<?php echo $locale_code; ?>/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>-->

        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/<?php echo $locale_code; ?>/sdk.js#xfbml=1&version=v2.4&appId=119853652572";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

        <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <?php
        if ($_SESSION['__langue__'] != 'En') {
            // Datepicker i18n
            switch ($_SESSION['__langue__']) {
                case 'Fr':
                    $datepickerLocale = 'fr-CH';
                    break;
                case 'De':
                    $datepickerLocale = 'de';
                    break;
                case 'It':
                    $datepickerLocale = 'it-CH';
                    break;
            }
            echo '<script type="text/javascript" src="https://rawgit.com/jquery/jquery-ui/1.11.4/ui/i18n/datepicker-' . $datepickerLocale . '.js"></script>';
        }
        ?>


        <div id="site">
            <?php
                include('includes/header.php');
                include('includes/sidebar.php');
                include('includes/main.php');
            ?>
        </div>

        <?php
        include('includes/footer.php');

        if ($_SESSION["__userLevel__"] < 100) {
            echo "<div id='adminPopUp'>";
            if ($admin) {
                echo "<a href='".VAR_HREF_PAGE_PRINCIPALE."'>".VAR_LANG_ACCUEIL."</a>";
            } else {
                echo "<a href='" . PATH_TO_ROOT . "/admin'>".VAR_LANG_ADMINISTRATION."</a>";
            }
            echo "<br /><a href='http://comite.tchoukball.ch' title='Accéder au Forum \"Comité et Commissions " . VAR_LANG_ASSOCIATION_NAME . "\"'>Forum</a>";
            echo "<br /><a href='" . PATH_TO_ROOT . "/logout'>".VAR_LANG_DECONNEXION."</a>";
            echo "</div>";
        }
        ?>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/retina.js/1.3.0/retina.min.js"></script> <!-- Pour afficher les images @2x si elles existent -->
        <script src="<?php echo PATH_TO_ROOT; ?>/scripts/before-body-closing.js"></script>

        <?php
        if ($affichage_twitter) {
            ?>
            <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
            <script type="text/javascript" src="http://twitter.com/statuses/user_timeline/tchouksuisse.json?callback=twitterCallback2&amp;count=5"></script>
            <?php
        }
        ?>
    </body>
</html>
