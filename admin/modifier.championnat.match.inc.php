<div class="modifierMatch">
    <?php
        statInsererPageSurf(__FILE__);
    if(isset($_GET['saison']) AND isset($_GET['idCat']) AND isset($_GET['idTour']) AND isset($_GET['idGroupe'])){
        include('modifier.championnat.match.etape2.inc.php');
    }
    elseif(isset($_GET['idMatch'])){
        include('modifier.championnat.match.etape3.inc.php');
    }
    elseif(isset($_POST['action']) && $_POST['action']=='modifierMatch3'){
        include('modifier.championnat.match.etape4.inc.php');
    }
    else{
		include('modifier.championnat.match.etape1.inc.php');
	}
	?>
</div>
