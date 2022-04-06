<?php include_once('includes/init.php'); ?>
<!DOCTYPE html>
<html lang="<?php echo strtolower($_SESSION["__langue__"]); ?>">
<head prefix="og: http://ogp.me/ns#">
    <title><?php echo $title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <meta name="keywords"
          content="Tchouk, Tchoukball, Tchouk-ball, Fair-play, Sport, sport pour tous, prix thulin, site officiel, official web site, Swiss Tchoukball, FSTB, F�d�ration Suisse de Tchoukball, Schweizerischer Tchoukball, Swiss Tchoukball Federation, Federazione Svizzera di Tchoukball"/>
    <meta name="description"
          content="Tchoukball : Sport pour tous. venez decouvrir ce nouveau sport qui est le sport de demain. Site officiel de <?php echo VAR_LANG_ASSOCIATION_NAME_ARTICLE; ?>. En fran�ais. Auf Deutsch. In English. In italiano."/>
    <meta name="Revisit-after" content="14 days">
    <meta http-equiv="Content-Language" content="<?php echo strtolower($_SESSION["__langue__"]); ?>">
    <meta name="Identifier-url" content="https://tchoukball.ch">
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
    <!-- Open graph metatags -->
    <meta property="og:title" content="<?php echo VAR_LANG_ASSOCIATION_NAME . $titre; ?>"/>
    <meta property="og:image" content="https://tchoukball.ch/pictures/Logo-SwissTchoukball_500.png"/>
    <meta property="og:type" content="<?php echo $open_graph_type; ?>"/>
    <meta property="og:url" content="<?php echo "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]; ?>"/>
    <meta property="og:site_name" content="<?php echo VAR_LANG_ASSOCIATION_NAME; ?>"/>
    <meta property="og:description" content="<?php echo $description; ?>"/>
    <!-- /Open graph metatags -->
    <link rel="stylesheet" type="text/css" href="/build/master.css?v=<?php echo $ST_WEBSITE_VERSION; ?>">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="alternate" type="application/rss+xml"
          href="https://tchoukball.ch/rss<?php echo $_SESSION["__langue__"]; ?>.php"/>
</head>
<body>

<!-- TODO: concatenate the libraries that need to be at the beginning of the body tag -->
<script src="/vendor/bower/jquery/dist/jquery.min.js"></script>
<script src="/vendor/bower/jquery-ui/jquery-ui.min.js"></script>
<script src="/scripts/after-body-opening.js?v=<?php echo $ST_WEBSITE_VERSION; ?>"></script>
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
?>

<script src="/build/lib.min.js?v=<?php echo $ST_WEBSITE_VERSION; ?>" charset="utf-8"></script>
<script src="/build/app.min.js?v=<?php echo $ST_WEBSITE_VERSION; ?>" charset="utf-8"></script>
<script src="/scripts/before-body-closing.js?v=<?php echo $ST_WEBSITE_VERSION; ?>"></script>
</body>
</html>
