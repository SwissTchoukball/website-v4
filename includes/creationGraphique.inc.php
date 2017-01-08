<?php
include("lib/jpgraph/src/jpgraph.php");
include("lib/jpgraph/src/jpgraph_bar.php");

$nbLangue = count($VAR_TABLEAU_DES_LANGUES);
$tailleGrapheY = 300;
$tailleGrapheX = 500;

// $Tab_Mois : tableau des mois dans la bonne langue
// $annee : l'annee du mois courrant
function getMoisAxeX($Tab_Mois, $annee)
{
    $moisActuel = date("m") - 1;
    $mois = $moisActuel;
    $nbMois = 12;
    for ($i = 0; $i < $nbMois; $i++) {
        $mois = ($mois + 1) % $nbMois;
        if ($moisActuel >= $mois) {
            $text_annee = substr($annee, 2, 2);
        } else {
            $text_annee = substr($annee - 1, 2, 2);
        }
        $axeX[$i] = nomsNormauxSansCodageHTML($Tab_Mois[$mois]) . " " . $text_annee;
    }
    return $axeX;
}

function getNbSurfLangueAxeY($VAR_TABLEAU_DES_LANGUES)
{

    $requeteSQL = "SELECT * FROM `StatistiqueLangue` ORDER BY `annee` DESC, `mois` DESC LIMIT 12";
    $recordset = mysql_query($requeteSQL);

    global $nbLangue;

    $mois = intval(date("m"));
    $nbMois = 12;

    // prend le premier enregistrement
    $record = mysql_fetch_array($recordset);

    // prendre les douzes mois
    for ($i = 0; $i < $nbMois; $i++) {

        //echo "test AA : ".$record['mois']."==$mois<br>";
        for ($j = 0; $j < $nbLangue; $j++) {
            if ($record !== false && $record["mois"] == $mois) {
                $datayLang[$j][$i] = $record[$VAR_TABLEAU_DES_LANGUES[$j][0]];
            } else {
                $datayLang[$j][$i] = 0;
            }
        }
        // prendre le suivant seulement si le mois correspondait... ceci pour garantir la validité des graphes avec des
        // trous dans la BD
        if ($record["mois"] == $mois) {
            $record = mysql_fetch_array($recordset);
        }
        $mois = ($mois - 1) % $nbMois;
        $axeX[$i] = $Tab_Mois[$mois];
    }
    // inverser l'ordre chronologique des tableaux
    for ($j = 0; $j < $nbLangue; $j++) {
        $datayLang[$j] = array_reverse($datayLang[$j]);
    }
    return $datayLang;
}

// valeurs des axes
$datax = getMoisAxeX($VAR_G_MOIS, date("Y"));
$datayLang = getNbSurfLangueAxeY($VAR_TABLEAU_DES_LANGUES);

// Create the graph. These two calls are always required
$graph = new Graph($tailleGrapheX, $tailleGrapheY, "auto");
$graph->SetScale("textlin");
//SetColor(array(65,100,176))
//$graph->setFrame(true,SetColor(array(189,217,255)));
$graph->setFrame(true, "blue");
$graph->SetMarginColor("#97BCE7");
$graph->SetColor("white");
$graph->SetFrameBevel(2, true, 'black');

// Adjust the margin a bit to make more room for titles
$graph->img->SetMargin(50, 80, 20, 80);

$couleurBox = array(
    "lightblue",
    "darkblue",
    "yellow",
    "orange",
    "lightred",
    "red",
    "red",
    "orange",
    "gray",
    "lightgray"
);

for ($i = 0; $i < $nbLangue; $i++) {
    // nouvelles bares
    $bplot[$i] = new BarPlot($datayLang[$i]);
    // Adjust fill color
    //$bplot[$i]->SetFillColor($couleurBox[$i]);
    $bplot[$i]->SetFillGradient($couleurBox[$i * 2], $couleurBox[$i * 2 + 1], GRAD_HOR);
    $bplot[$i]->SetLegend($VAR_TABLEAU_DES_LANGUES[$i][0]);
}

// regrouper les boxplots
$gbplot = new GroupBarPlot($bplot);

// ajouter le regroupement au graphe
$graph->Add($gbplot);

// legende
$graph->legend->SetAbsPos(5, $tailleGrapheY / 2, 'right', 'center');
$graph->legend->SetShadow(false);

// Setup the titles
$graph->title->Set("Répartition des langues");
//$graph->xaxis->title->Set("X-title");
$graph->yaxis->title->Set("Nombre de visites");

$graph->title->SetFont(FF_FONT1, FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1, FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1, FS_BOLD);

// legende de l'axe x en vertical
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->SetTickLabels($datax);

// Display the graph
$graph->Stroke(FICHIER_GRAPHE_STAT_LANGUE);

?>