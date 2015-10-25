<?php
$orderRefereesByLevel = true;
$referees = getReferees($orderRefereesByLevel);

if (date('m') >= 8) {
    $currentSeason = date('Y');
} else {
    $currentSeason = date('Y') - 1;
}

if (isset($_GET['ajouter'])) {
    $action = 'add';
    $titleAction = 'Ajout';
    $submitAction = 'Ajouter';
    if (isset($_GET['id']) && isValidID($_GET['id'])) {
        $refereeID = $_GET['id'];
    } else {
        $refereeID = null;
    }
    if (isset($_GET['saison']) && isValidID($_GET['saison'])) {
        $seasonID = $_GET['saison'];
    } else {
        $seasonID = $currentSeason;
    }
    if (isset($_GET['montant']) && isValidID($_GET['montant'])) {
        $amountPaid = $_GET['montant'];
    } else {
        $amountPaid = null;
    }
    // Getting the next weekday
    $timestamp = strtotime('1 weekday');
    $year = date('Y', $timestamp);
    $month = date('m', $timestamp);
    $day = date('d', $timestamp);
} elseif (isset($_GET['modifier']) && is_numeric($_GET['modifier'])) {
    $action = 'edit';
    $titleAction = 'Édition';
    $submitAction = 'Modifier';
    $paymentID = $_GET['modifier'];

    $queryPayment = "SELECT av.idArbitre, av.saison, av.montantPaye, av.datePaiement,
                            p.nom AS nomUtilisateur, p.prenom AS prenomUtilisateur
                     FROM Arbitres_Versements av
                     LEFT OUTER JOIN Personne p ON p.id = av.userID
                     WHERE av.id = " . $paymentID;
    if ($dataPayment = mysql_query($queryPayment)) {
        $p = mysql_fetch_assoc($dataPayment);
        if (mysql_num_rows($dataPayment) < 1) {
            die(printErrorMessage("Données introuvables"));
        }
        $refereeID = $p['idArbitre'];
        $seasonID = $p['saison'];
        $amountPaid = $p['montantPaye'];
        $paymentDate = $p['datePaiement'];
        $year = annee($paymentDate);
        $month = mois($paymentDate);
        $day = jour($paymentDate);
        $userName = $p['prenomUtilisateur'] . ' ' .$p['nomUtilisateur'];
    } else {
        die(printErrorMessage("Problème lors de la récupération des données"));
    }
} else {
    die(printErrorMessage("Action indéfinie"));
}
?>

<script>
$(function() {
    $.datepicker.setDefaults($.datepicker.regional[ "<?php echo strtolower($_SESSION['__langue__']); ?>" ]);
    $("#datepicker").datepicker({
        dateFormat: 'dd.mm.yy',
        onClose: function(dateText, inst) {
            $('#description').focus();
        }
    });
    $("#datepicker").datepicker('setDate', '<?php echo $day . '.' . $month . '.' . $year; ?>');
});
</script>

<?php
echo '<h2>' . $titleAction . ' d\'un versement</h2>';

echo '<form name="editPayment" action="?menuselection=' . $_GET['menuselection'] . '&smenuselection=' . $_GET['smenuselection'] . '&gerer" method="post" class="adminForm">';

echo '<label for="refereeID">' . ucfirst(VAR_LANG_ARBITRE) . '</label>';
echo '<select name="refereeID" id="refereeID">';
printRefereesOptionsList($referees, $refereeID);
echo '</select>';

echo '<label for="seasonID">' . VAR_LANG_SAISON . '</label>';
echo '<select name="seasonID" id="seasonID">';
printSeasonsOptionsForSelect(2015, date('Y'), $seasonID);
echo '</select>';

echo '<label for="amountPaid">Montant (CHF)</label>';
echo '<input type="number" name="amountPaid" id="amountPaid" min="1" value="' . $amountPaid . '" />';

echo '<label for="datepicker">' . VAR_LANG_DATE . '</label>';
echo '<input type="text" name="paymentDate" id="datepicker" value="' . $paymentDate . '" />';

echo '<input type="hidden" name="paymentID" value="' . $paymentID . '" />';
echo '<input type="submit" name="' . $action . '" value="' . $submitAction . '" />';
echo '</form>';


if ($action == 'edit') {
    echo '<a href="?menuselection=' . $_GET['menuselection'] . '&smenuselection=' . $_GET['smenuselection'] . '&delete=' . $paymentID . '"  onclick="return confirm(\'Voulez-vous vraiment supprimer ce versement ?\')">Supprimer ce versement</a>';
    echo '<p>Dernière modification par ' . $userName . '</p>';
}
?>
