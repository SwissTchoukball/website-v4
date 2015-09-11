<? ?>
<h3>
<? echo VAR_LANG_ETAPE_1; ?>
</h3>
<form method="post" action="">
    <fieldset>
        <legend><? echo VAR_LANG_ETAPE_1; ?></legend>
        <label for="annee">Année : </label>
        <select name="annee" id="annee">
            <?
            for($y=date('Y');$y<=date('Y')+10;$y++){
                ?>
                <option value="<? echo $y; ?>"><? echo $y; ?></option>
                <?
            }
            ?>
        </select><br /><br />
        <label for="categorie">Catégorie : </label>
        <select name="categorie" id="categorie">
            <?
            $requeteCat="SELECT * FROM CoupeCH_Categories ORDER BY idCategorie";
            $retourCat=mysql_query($requeteCat);
            while($donneesCat=mysql_fetch_array($retourCat)){
                ?>
                <option value="<? echo $donneesCat['idCategorie']; ?>"><? echo $donneesCat['nom'.$_SESSION['__langue__']]; ?></option>
                <?
            }
            ?>
        </select><br /><br />
        <label for="nbEquipes">Nombre d'équipes : </label>
        <select name="nbEquipes" id="nbEquipes">
            <option>2</option>
            <option>4</option>
            <option>8</option>
            <option>16</option>
            <option>32</option>
        </select><br /><br />
        Les matchs se joueront-ils en sets ?<br />
        <input type="radio" name="sets" value="oui" id="oui" />
        <label for="oui">Oui</label><br />
        <input type="radio" name="sets" value="non" id="non" />
        <label for="non">Non</label><br />
    </fieldset>
    
    <p>
        <input type="hidden" name="prochaineEtape" value="etape2" />
        <input type="submit" value="<? echo VAR_LANG_ETAPE_2; ?>" />
    </p>
</form>