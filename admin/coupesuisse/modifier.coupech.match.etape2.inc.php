<?php ?>
<h3>
<?php echo VAR_LANG_ETAPE_2; ?>
</h3>
<div class="modifierMatch">
    <p>S�l�ctionnez le match dont vous souhaitez modifier le score.</p>
    <table class="tableauMatchsModifierMatchCoupeCH">
    <?php
    echo "<tr>";
        echo "<th>".VAR_LANG_MATCH."</th>";
        echo "<th>".VAR_LANG_TOUR."</th>";
    echo "</tr>";

    $annee=$_GET['modAnnee'];
    $idCategorie=$_GET['modCat'];

    //D�termination des journ�es concern�es.
    $requeteJournee="SELECT idJournee FROM CoupeCH_Journees WHERE annee=".$annee." AND idCategorie=".$idCategorie."";
    $retourJournee=mysql_query($requeteJournee);
    while($donneesJournee=mysql_fetch_array($retourJournee)){

        $requete="SELECT idMatch, equipeA, equipeB, idTypeMatch FROM CoupeCH_Matchs WHERE idJournee=".$donneesJournee['idJournee']." ORDER BY idTypeMatch DESC, ordre";
        //echo $requete;
        $retour=mysql_query($requete);
        while($donnees=mysql_fetch_array($retour)){

            // D�termination du type de match
            $requeteTypeMatch="SELECT nom".$_SESSION['__langue__']." FROM CoupeCH_Type_Matchs WHERE idTypeMatch=".$donnees['idTypeMatch']."";
            $retourTypeMatch=mysql_query($requeteTypeMatch);
            $donneesTypeMatch=mysql_fetch_array($retourTypeMatch);
            $typeMatch=$donneesTypeMatch['nom'.$_SESSION['__langue__']];

            // D�termination du nom des �quipes
                if($donnees['equipeA']==0){
                    if($donnees['forfait']==3){
                        $equipeA="-";
                    }
                    else{
                        $equipeA=VAR_LANG_INCONNU;
                    }
                }
                else{
                    $requeteEquipeA="SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=".$donnees['equipeA']."";
                    $retourEquipeA=mysql_query($requeteEquipeA);
                    $donneesEquipeA=mysql_fetch_array($retourEquipeA);
                    $equipeA = $donneesEquipeA['nomEquipe'];
                }
                if($donnees['equipeB']==0){
                    if($donnees['forfait']==3){
                        $equipeB="-";
                    }
                    else{
                        $equipeB=VAR_LANG_INCONNU;
                    }
                }
                else{
                    $requeteEquipeB="SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=".$donnees['equipeB']."";
                    $retourEquipeB=mysql_query($requeteEquipeB);
                    $donneesEquipeB=mysql_fetch_array($retourEquipeB);
                    $equipeB = $donneesEquipeB['nomEquipe'];
                }

            echo "<tr>";
                echo "<td><a href=?menuselection=".$menuselection."&smenuselection=".$smenuselection."&modMatch=".$donnees['idMatch'].">".$equipeA." - ".$equipeB."</a></td>";
                echo "<td>".$typeMatch."</td>";
            echo "</tr>";
        }
    } //fin boucle while pour chaque journee
    ?>
    </table>
</div>
