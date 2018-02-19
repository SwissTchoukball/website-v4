<h2>Envois postaux</h2>
<p>
    <a href="admin/tchoukup.export.tsv.php?query=envois-individuels">
        <img src="/admin/images/document_excel.png" alt="Fichier Excel"/>
        Envois individuels (standard)
    </a><br/>
    <a href="admin/tchoukup.export.tsv.php?query=individuels-juniors">
        <img src="/admin/images/document_excel.png" alt="Fichier Excel"/>
        Envois individuels juniors
    </a><br/>
    <a href="admin/tchoukup.export.tsv.php?query=all-individuels">
        <img src="/admin/images/document_excel.png" alt="Fichier Excel"/>
        Envois individuels (étendu pour TGI)
    </a><br/>
    <a href="admin/tchoukup.export.tsv.php?query=nb-tchoukup-colis-par-club">
        <img src="/admin/images/document_excel.png" alt="Fichier Excel"/>
        Nombre de Tchouk<sup>up</sup> par colis par club
    </a>
</p>
<h2>Envois par e-mail</h2>
<?php
$emailDispatchQuery =
    "SELECT DISTINCT
      IF(LENGTH(p.`emailFederation`)>0, p.`emailFederation`, p.`email`) AS 'email',
      IF(
        LENGTH(p.`raisonSociale`)>0,
        IF(
          LENGTH(p.`nom`)>0 AND LENGTH(p.`prenom`)>0,
          CONCAT(p.`raisonSociale`, ' - ', p.`prenom`, ' ', p.`nom`),
          p.`raisonSociale`
        ),
        CONCAT(p.`prenom`, ' ', p.`nom`)
      ) AS 'name'
    FROM `DBDPersonne` p, `DBDCivilite` c
    WHERE (p.`idCHTB` = 2 OR p.`idCHTB` = 5)
    AND p.`idCivilite` = c.`idCivilite`
    AND (
      (p.`email` != '' AND p.`email` IS NOT NULL)
      OR (p.`emailFederation` != '' AND p.`emailFederation` IS NOT NULL)
    )
    ORDER BY p.`idClub`, p.`nom`, p.`prenom`";

$emailDispatchResult = mysql_query($emailDispatchQuery);

$emailsList = '';
while ($entry = mysql_fetch_assoc($emailDispatchResult)) {
    $emailsList .= $entry['email'] . ',' . $entry['name'] . "\n";
}
?>
<textarea title="Adresses pour la mailing-list" class="big-field" readonly><?php echo $emailsList ?></textarea>
