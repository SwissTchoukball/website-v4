<div class="traductionDivers">
    <?php statInsererPageSurf(__FILE__); ?>

    <?php
    if (isset($_POST["action"]) && $_POST["action"] == "afficherTableChamp") {
        include "translate.generique.inc.php";
    } else {

        // nom a afficher,table,champ a traduire,id
        $TABLEAU_TRADUCTION = array(
            array("Divers"),
            array("Noms des commissions", "Commission_Nom", "nomCom", "id"),
            array(
                "Noms des types d&acute;informations pour classifiés les données",
                "TypeInformation",
                "description",
                "id"
            ),

            array("Championnat"),
            array("Noms des groupes du championnat", "ChampionnatGroupe", "nomGroupe", "idGroupe"),
            array("Noms des finales", "Final_Nomination_Match", "nomFinale", "id"),

            array("Tournoi internationaux"),
            array("Noms des catégories des tournois internationaux", "International_Categorie", "nomCat", "id"),
            array("Noms des tournois internationaux", "International_Event", "nomEvent", "id"),
            array("Noms des pariticpants des tournois internationaux", "International_Participant", "nomPart", "id"),

            array("Liens et téléchargement"),
            array("Noms des liens", "Liens", "nom", "id"),
            array("Noms des des groupes de liens", "LiensGroupe", "nomGroupe", "id"),
            array("Noms des downloads", "Download", "titre", "id"),
            array("Noms des types de downloads", "TypeDownload", "description", "id"),

            array("Menus"),
            array("Noms des menus ", "Menu", "nom", "id"),
            array("Noms des menus de la partie admin ", "MenuAdmin", "nom", "id"),

            array("Sponsors"),
            array("Noms des sponsors", "Sponsors", "description", "idUniqueSponsor"),
            array("Noms des sponsors", "TypeSponsors", "nomType", "id"),

            array("Vidéos"),
            array("Noms des vidéos", "Videos", "nom", "id"),
            array("Noms des descriptions pour les vidéoes", "TypeVideos", "nomType", "id"),
            array("Noms des aides pour les vidéos", "TypeVideos", "infoMini", "id"),

            array("Annuaire FSTB - certain champs sont vides, c'est des valeurs par défaut.  Merci de ne pas les remplir."),
            array("Noms des titres des arbitres (DBD)", "DBDArbitre", "descriptionArbitre", "idArbitre"),
            array("Noms des autres fonctions (DBD)", "DBDAutreFonction", "descriptionAutreFonction", "idAutreFonction"),
            array("Noms des satuts pour le CHTB (DBD)", "DBDCHTB", "descriptionCHTB", "idCHTB"),
            array("Noms des civilités (DBD)", "DBDCivilite", "descriptionCivilite", "idCivilite"),
            array("Noms des titres des formations (DBD)", "DBDFormation", "descriptionFormation", "idFormation"),
            array("Noms des langues (DBD)", "DBDLangue", "descriptionLangue", "idLangue"),
            array("Noms des régions des médias (DBD)", "DBDMediaCanton", "descriptionMediaCanton", "idMediaCanton"),
            array("Noms des types de médias (DBD)", "DBDMediaType", "descriptionMediaType", "idMediaType"),
            array(
                "Noms des origines des contacts (DBD)",
                "DBDOrigineAdresse",
                "descriptionOrigineAdresse",
                "idOrigineAdresse"
            ),
            array("Noms des pays (DBD)", "DBDPays", "descriptionPays", "idPays"),
            array("Noms des raisons sociales (DBD)", "DBDRaisonSociale", "descriptionRaisonSociale", "idRaisonSociale"),
            array("Noms des status des personnes enregistrées (DBD)", "DBDStatus", "descriptionStatus", "idStatus")
        );
        echo "<table align='center'><tr><td>";
        for ($i = 0; $i < count($TABLEAU_TRADUCTION); $i++) {
            if (count($TABLEAU_TRADUCTION[$i]) == 1) {
                echo "<h4>" . $TABLEAU_TRADUCTION[$i][0] . "</h4>";
            } else {
                ?>
                <form name='translateType' action='' method='post'>
                    <table>
                        <tr>
                            <td>
                                <input type='submit' class="button button--primary" value='Traduire les champs'>
                                <input type='hidden' name='titre' value='<?php echo $TABLEAU_TRADUCTION[$i][0]; ?>'/>
                                <input type='hidden' name='table' value='<?php echo $TABLEAU_TRADUCTION[$i][1]; ?>'/>
                                <input type='hidden' name='champ' value='<?php echo $TABLEAU_TRADUCTION[$i][2]; ?>'/>
                                <input type='hidden' name='identifier' value='<?php echo $TABLEAU_TRADUCTION[$i][3]; ?>'/>
                                <input type='hidden' name='action' value='afficherTableChamp'/>
                            </td>
                            <td>
                                <p><?php echo $TABLEAU_TRADUCTION[$i][0]; ?></p>
                            </td>\
                        </tr>
                    </table>
                </form>
                <?php
            }
        }
        echo "</td></tr></table>";
    }
    ?>
</div>
