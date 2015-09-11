<?php

//Requiert les variables $showDetails, $clubToShowId (can be null) et $nbMembersPerPage (can be null)

//Handling search
if (isset($_POST['keywords'])) {
	$keywords = mysql_escape_string($_POST['keywords']);

	// spliting into tokens
	$tok = strtok($keywords," ");
	$querySearch = "";
	$firstLoop = true;


	while ($tok) {
		if(!$firstLoop){
			$querySearch .= " AND ";
		}
		$querySearch .= "(p.`nom` LIKE '%".$tok."%' OR
						  p.`prenom` LIKE '%".$tok."%' OR
						  p.`raisonSociale` LIKE '%".$tok."%' OR
						  p.`ville` LIKE '%".$tok."%' OR
						  p.`email` LIKE '%".$tok."%' OR
						  p.`adresse` LIKE '%".$tok."%' OR
						  c.`club` LIKE '%".$tok."%' OR
						  p.`remarque` LIKE '%".$tok."%' OR
						  p.`npa` LIKE '%".$tok."%')";
		$tok = strtok(" ");

		$firstLoop = false;
	}
}

//Handling pre-selection
if (isset($_POST['preselectionID'])) {
	$preselectionID = mysql_escape_string($_POST['preselectionID']);

	$preselectionQuery = "SELECT where_clause FROM DBDRequetesPreselection WHERE id=".$preselectionID." LIMIT 1";
	$preselectionData = mysql_query($preselectionQuery);
	if (mysql_num_rows($preselectionData) > 0) {
		$preselection = mysql_fetch_assoc($preselectionData);

		$querySearch = $preselection['where_clause'];

		$nbMembersPerPage = null; //Pour pas qu'il y ait de pagination, afin que le liens des e-mails contienne tout le monde.
	}
}

if ($nbMembersPerPage == null) {
	$showPagination = false;
} else {
	$showPagination = true;
}

if ($showPagination) {
	//Information for pagination
	$href = '?menuselection='.$menuselection.'&smenuselection='.$smenuselection.'&details';

	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}

	$offset = ($page-1) * $nbMembersPerPage;
}

//Building the queries
if ($clubToShowId != null) {
	$queryClubPart = "p.idClub=".$clubToShowId." AND";
} else {
	$queryClubPart = "";
}

$querySelectToList = "SELECT p.idDbdPersonne, p.raisonSociale, p.nom, p.prenom, p.adresse, p.cp, p.npa, p.ville, p.suspendu, pa.idPays, pa.descriptionPays".$_SESSION["__langue__"]." AS pays, sx.idSexe, sx.descriptionSexe".$_SESSION['__langue__']." AS genre, sx.imageFilename AS imageGenre, p.telPrive, p.telProf, p.portable, p.email, c.nbIdClub AS idClub, c.club AS nomClub, st.descriptionStatus".$_SESSION["__langue__"]." AS statut, p.idLangue, p.dateNaissance, tup.descriptionCHTB".$_SESSION["__langue__"]." AS tchoukup, p.idCHTB AS idTchoukup";
$querySelectToCount = "SELECT COUNT(*) AS totalNbMembers";
$queryFrom = "FROM DBDPersonne p, DBDStatus st, DBDPays pa, DBDCHTB tup, DBDSexe sx, ClubsFstb c";
$queryWhere = "WHERE ".$queryClubPart." p.idStatus=st.idStatus AND p.idPays=pa.idPays AND p.idCHTB=tup.idCHTB AND p.idClub=c.nbIdClub AND p.idSexe=sx.idSexe";
if ($querySearch != "") {
	$queryWhere .= " AND (".$querySearch.")";
}
$queryOrder = "ORDER BY p.nom, p.prenom, p.raisonSociale";
if ($showPagination) {
	$queryLimit = "LIMIT ".$offset.", ".$nbMembersPerPage;
} else {
	$queryLimit = "";
}

if ($showPagination) {
	// Pagination
	$queryToCount = $querySelectToCount.' '.$queryFrom.' '.$queryWhere.' '.$queryOrder;
	if ($totalNbMembersResource = mysql_query($queryToCount)) {
		$totalNbMembersData = mysql_fetch_assoc($totalNbMembersResource);
		$totalNbMembers = $totalNbMembersData['totalNbMembers'];
	} else {
		echo "<p class='error'>Erreur dans le d�compte du nombre total de membres.</p>";
		//echo "<p class='info'>".$queryToCount."</p>";
		$totalNbMembers = 0;
	}
	$totalNumberOfPages = ceil($totalNbMembers / $nbMembersPerPage);
}

//Search form
?>
<form class="members-search" method="post" action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>&details">
	<input type="search" name="keywords" placeholder="Recherche" />
	<input type="submit" value="Rechercher" />
</form>
<?php

//Pre-selection
$preselectionListQuery = "SELECT * FROM DBDRequetesPreselection ORDER BY nom";
if ($preselectionListData = mysql_query($preselectionListQuery)) {
	?>
	<form class="members-search" method="post" action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>">
		<select name="preselectionID" onchange="submit();">
			<option value="0">-</option>
			<?php
				while($preselection = mysql_fetch_assoc($preselectionListData)) {
					if (isset($_POST['preselectionID']) && $_POST['preselectionID'] == $preselection['id']) {
						$selected = ' selected';
					} else {
						$selected = '';
					}
					echo '<option value="'.$preselection['id'].'"'.$selected.'>'.$preselection['nom'].'</option>';
				}
			?>
		</select>
	</form>
	<?php
} else {
	echo "<p class='error'>Erreur lors de la r�cup�ration des pr�selections.</p>";
}

if ($showPagination) {
	showPagination($page, $totalNumberOfPages, $href);
}

echo $showDetails ? '<table class="adminTable detailed">' : '<table class="adminTable">';
?>
	<thead>
		<tr>
			<th></th>
			<?php echo $showDetails ? '<th>Adresse</th><th>Coordonn�es</th><th>Informations</th>' : '<th>Raison sociale</th><th>Nom et pr�nom</th><th>Statut</th>'; ?>
			<th>�dition</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$adressesEmail = array();

		$queryToList = $querySelectToList.' '.$queryFrom.' '.$queryWhere.' '.$queryOrder.' '.$queryLimit;
		//echo $queryToList;
		$data = mysql_query($queryToList);
		while ($member = mysql_fetch_assoc($data)) {
			$idMembre = $member['idDbdPersonne'];
			$idSexe = $member['idSexe'];
			$genre = $member['genre'];
			$imageGenre = $member['imageGenre'];
			$raisonSociale = $member['raisonSociale'];
			$nom = $member['nom'];
			$prenom = $member['prenom'];
			$adresse1 = $member['adresse'];
			$adresse2 = $member['cp'];
			$npa = $member['npa'];
			$ville = $member['ville'];
			$idPays = $member['idPays'];
			$pays = $member['pays'];
			$telPrive = $member['telPrive'];
			$telProf = $member['telProf'];
			$telMobile = $member['portable'];
			$email = $member['email'];
			$idClub = $member['idClub'];
			$nomClub = $member['nomClub'];
			$statut = $member['statut'];
			$dateNaissance = $member['dateNaissance'] != null ? date_sql2date($member['dateNaissance']) : null;
			$idLangue = $member['idLangue'];
			$tchoukup = $member['tchoukup'];
			$idTchoukup = $member['idTchoukup'];
			if ($idTchoukup == 3 || $idTchoukup == 4) { // Non ou Refus�
				$tchoukup = "<span class='wrong'>".$tchoukup."</span>";
			}
			$isSuspended = $member['suspendu'] == 1;

			if ($email != '') {
				array_push($adressesEmail, $email);
			}

			if ($isSuspended) {
				echo '<tr class="suspendu">';
			} else {
				echo "<tr>";
			}

			if ($showDetails) {
				?>
				<td class="gender">
					<?php echo ($nom == "" && $prenom == "" && $idSexe == 1) ? '' : '<img src="admin/images/'.$imageGenre.'" alt="'.$genre.'" />'; ?>
					<img src="admin/images/langues/<?php echo $idLangue; ?>.png" alt="langue" />
				</td>
				<td>
					<?php
					echo $raisonSociale != "" ? $raisonSociale."<br />" : "";
					echo ($nom != "" && $prenom != "") ? "<strong>".$nom."</strong> ".$prenom."<br />" : "";
					echo $adresse1 != "" ? $adresse1."<br />" : "";
					echo $adresse2 != "" ? $adresse2."<br />" : "";
					echo $npa." ".$ville."<br />";
					if ($idPays != 42) { // Not Switzerland
						echo $pays;
					}
					?>
				</td>
				<td>
					<?php
					echo $telPrive != "" ? "<img src='admin/images/telPrive.png' alt='Num�ro de t�l�phone priv�'/> ".$telPrive."<br />" : "";
					echo $telProf != "" ? "<img src='admin/images/telProf.png' alt='Num�ro de t�l�phone professionnel'/> ".$telProf."<br />" : "";
					echo $telMobile != "" ? "<img src='admin/images/telMobile.png' alt='Num�ro de t�l�phone mobile'/> ".$telMobile."<br />" : "";
					echo $email != "" ? "<img src='admin/images/email.png' alt='Adresse e-mail'/> ".$email : "";
					?>
				</td>
				<td>
					<?php
					//If we don't list the members of a specific club, then show for each member in which club she is.
					if ($clubToShowId == null && $idClub != 15) {
						echo $nomClub."<br />";
					}
					echo $statut;
					if ($isSuspended) {
						echo ' <span class="wrong">SUSPENDU</span>';
					}
					echo "<br />";
					echo $dateNaissance != null ? "Date de naissance : ".$dateNaissance."<br />" : "";
					echo "tchouk<sup>up</sup> : ".$tchoukup;
					?>
				</td>
				<?php
			} else {
				?>
				<td class="gender">
					<?php echo ($nom == "" && $prenom == "" && $idSexe == 1) ? '' : '<img src="admin/images/'.$imageGenre.'" alt="'.$genre.'" />'; ?>
				</td>
				<td><?php echo $raisonSociale; ?></td>
				<td><strong><?php echo $nom; ?></strong> <?php echo $prenom; ?></td>
				<td><?php echo $statut; ?></td>
				<?php
			}
			?>

			<td class="action">
				<a href="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>&edit=<?php echo $idMembre; ?>"><img src="admin/images/modifier.png" alt="Modifier un membre"/></a>
				<!--<a href="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>&delete=<?php echo $membersData['idDbdPersonne']; ?>" onclick='return confirm("Voulez-vous vraiment supprimer<?php echo $membersData['prenom']; ?><?php echo $membersData['nom']; ?> ?");'><img src="admin/images/supprimer.png" alt="Supprimer un membre"/></a>-->
			</td>
			<?php
			echo "</tr>";
		}
		?>
	</tbody>
</table>
<?php
if ($showPagination) {
	showPagination($page, $totalNumberOfPages, $href);
}
?>
<a href="mailto:<? echo implode(',', $adressesEmail); ?>">Envoyer un mail aux personnes affich�es et ayant une adresse.</a>