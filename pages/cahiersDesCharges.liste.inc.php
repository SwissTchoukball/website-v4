<h1>Cahiers des charges</h1>
<?php
$query = "SELECT c.id, c.title
		  FROM CahierDesCharges c
		  ORDER BY c.title";
?>
<ul>
    <?php

    $data = mysql_query($query);
    while ($spec = mysql_fetch_assoc($data)) {
        $idSpec = $spec['id'];
        $title = $spec['title'];
        ?>
        <li>
            <a href="/cahiers-des-charges/<?php echo $idSpec; ?>">
                <?php echo $title; ?>
            </a>
        </li>
        <?php
    }
    ?>
</ul>