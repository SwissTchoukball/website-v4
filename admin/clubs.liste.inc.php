<table class="adminTable">
	<thead>
		<tr>
			<th>Adresse</th>
			<th>Coordonnées</th>
			<th>Édition</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$queryClubs = "SELECT c.id, c.nomComplet, c.adresse, c.npa, c.ville, c.email, c.telephone, c.actif, c.url FROM ClubsFstb c WHERE c.id!=0 ORDER BY c.actif DESC, c.nomPourTri";
		$dataClubs = mysql_query($queryClubs);
		while ($club = mysql_fetch_assoc($dataClubs)) {
			$clubID = $club['id'];
			$clubName = $club['nomComplet'];
			$clubAdress = $club['adresse'];
			$clubNPA = $club['npa'];
			$clubCity = $club['ville'];
			$clubEmail = $club['email'];
			$clubPhone = $club['telephone'];
			$clubURL = $club['url'];
			$clubIsActiv = $club['actif'] == 1;

			if ($clubIsActiv) {
				echo '<tr>';
			} else {
				echo '<tr class="inactif">';
			}
			?>
				<td>
					<?php
					echo $clubName."<br />";
					echo $clubAdress != "" ? $clubAdress."<br />" : "";
					if ($clubNPA != "" || $clubCity != "") {
					echo $clubNPA." ".$clubCity."<br />";
					}
					?>
				</td>
				<td>
					<?php
					echo $clubPhone != "" ? "<img src='admin/images/telPrive.png' alt='Numéro de téléphone'/> ".$clubPhone."<br />" : "";
					echo $clubEmail != "" ? "<img src='admin/images/email.png' alt='Adresse e-mail'/> <a href='mailto:".$clubEmail."'>".$clubEmail."</a><br />" : "";
					echo $clubURL != "" ? "<img src='admin/images/globe.png' alt='Adresse e-mail'/> <a href='".addhttp($clubURL)."'>".$clubURL."</a><br />" : "";
					?>
				</td>
				<td class="action">
					<a href="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>&edit=<?php echo $clubID; ?>"><img src="admin/images/modifier.png" alt="Modifier un club"/></a>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>