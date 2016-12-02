<!-- <p>Mise à jour des scores en temps réel.</p> -->
<div class="banniereSiteCoupeSuisse"><a href="http://www.coupesuisse.com"><img
            src="pictures/banniere-coupesuisse.com.png" alt="Allez sur CoupeSuisse.com"/></a></div>
<iframe
    src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fcoupesuisse&width=360&colorscheme=light&show_faces=false&stream=false&header=false&height=77"
    scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:360px; height:77px;"
    allowTransparency="true"></iframe>

<div class="selectionResultatsCoupeCH">
    <?php
    $thisYear = date('Y');
    if (isset($_POST['annee']) && $_POST['annee'] <= $thisYear && $_POST['annee'] >= '2008') {
        $selectedYear = $_POST['annee'];
    } else {
        $selectedYear = $thisYear;
    }
    ?>
    <form name="resultatsCoupeCH" action="" method="post">
        <div class="etiquette"><?php echo VAR_LANG_EDITION . " :&nbsp;"; ?></div>
        <select name="annee" id="select" onChange="resultatsCoupeCH.submit();">
            <?php
            for ($year = 2008; $year <= $thisYear; $year++) {
                if ($selectedYear == $year) {
                    echo "<option selected='selected' value='" . $year . "'>" . $year . "</option>";
                } else {
                    echo "<option value='" . $year . "'>" . $year . "</option>";
                }
            }
            ?>
        </select>
    </form>
</div>
<?php // Javascript pour afficher la box d'information sur un match.
?>
<script language="JavaScript" type="text/javascript">
    var last = '1';
    var idOpened = '0';

    function afficherInfoMatch(num) {
        var ind;

        if (document.getElementById("infomatch" + num).style.display == "none") {
            document.getElementById("infomatch" + num).style.display = "";
            idSelected = num;
        }


    }
    function masquerInfoMatch(num) {
        var ind;

        if (document.getElementById("infomatch" + num).style.display == "") {
            document.getElementById("infomatch" + num).style.display = "none";
            idSelected = num;
        }


    }
</script>

<h4>Survolez les tableaux pour avoir plus d'informations</h4>
<h3>Coupe Suisse <?php echo $selectedYear; ?></h3>

<?php
$resultsFile = 'pages/coupech.resultats.' . $selectedYear . '.inc.php';
if (is_file($resultsFile)) {
    include($resultsFile);
} else {
    echo "<p>Résultats indisponibles</p>";
}
?>
