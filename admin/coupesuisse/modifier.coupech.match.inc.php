<?php
if(isset($_GET['modAnnee'])){
    include('modifier.coupech.match.etape2.inc.php');
}
elseif(isset($_GET['modMatch'])){
    include('modifier.coupech.match.etape3.inc.php');
}
elseif(isset($_POST['action']) AND $_POST['action']=="modificationMatch"){
    include('modifier.coupech.match.etape4.inc.php');
}
else{
    include('modifier.coupech.match.etape1.inc.php');
}
?>
