<?php
require_once "DBAccess.php";
use DB\DBAccess;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

setLocale(LC_ALL, 'it_IT');

$paginaHTML = file_get_contents("aggiungiTracciaTemplate.html");
$listaAlbum = "";
$album = "";
$titolo = "";
$durata = "";
$esplicito = "";
$dataRadio = "";
$urlVideo = "";
$note = "";
$messaggiPerForm = "<ul>";
$tracciaInserita = 0;

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();

if ($connectionOk) {
    $resultListaAlbum = $connection -> getListaAlbum();
    foreach ($resultListaAlbum as $album) {
        if ((isset($_POST["submit"]) && isset($_POST["album"]))
                    && isset($_POST["album"]) == $album["ID"]) {
            $listaAlbum .= "<option value=\"" . $album["ID"] ."\" selected>"
                . $album["Titolo"] . "</option>";
        }
        else {
            $listaAlbum .= "<option value=\"" . $album["ID"] ."\">"
                . $album["Titolo"] . "</option>";
        }
    }

    if (isset($_POST["submit"])) {
        $errore = false;
        $album = (filter_var($_POST["album"], FILTER_VALIDATE_INT)) ? $_POST["album"] : "";
        $titolo = pulisciInput($_POST["titolo"]);
        $durata = (filter_var($_POST["durata"], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/\d{2}:\d{2}/")))) ? $_POST["durata"] : "00:00";
        $esplicito = isset($_POST["esplicito"]) ? pulisciInput($_POST["esplicito"]) : "";
        $dataRadio = (filter_var($_POST["dataRadio"], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/\d{4}-\d{2}-\d{2}/")))) ? $_POST["dataRadio"] : "gg/mm/aaaa";
        $urlVideo = (filter_var($_POST["urlVideo"], FILTER_VALIDATE_URL)) ? $_POST["urlVideo"] : "";
        $note = pulisciInput($_POST["note"]);
        if ($album == "" || $album <= 0) {
            $messaggiPerForm .= "<li class=\"errori\">Dev'essere selezionato un album valido.</li>";
            $errore = true;
        }
        if ($titolo == "") {
            $messaggiPerForm .= "<li class=\"errori\">Il titolo non può essere vuoto.</li>";
            $errore = true;
        }
        if ($durata == "00:00") {
            $messaggiPerForm .= "<li class=\"errori\">Il brano deve avere una durata maggiore di 0.</li>";
            $errore = true;
        }
        if (($esplicito != "Yes") && ($esplicito != "No")) {
            $messaggiPerForm .= "<li class=\"errori\">Dev'essere indicato se il brano è esplicito o meno.</li>";
            $errore = true;
        }
        if (!$errore) {
            $esplicito_val = ($esplicito == "Yes") ? 1 : 0;
            $tracciaInserita = $connection -> insertNewTrack(
                                                    $album,
                                                    $titolo,
                                                    $durata,
                                                    ($esplicito_val),
                                                    $dataRadio,
                                                    $urlVideo,
                                                    $note);
        }
        if ($tracciaInserita) {
            $messaggiPerForm .= "<li class=\"ok\">Traccia aggiunta correttamente.</li>";
        }
    }
}
else {
    $messaggiPerForm .= "<li class=\"errori\">I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</li>";
}

$connection -> closeDBConnection();

function pulisciInput($value) {
    // elimina gli spazi
    $value = trim($value);
    // rimuove tag html
    $value = strip_tags($value);
    // converte i caratteri speciali in entita html
    $value = htmlentities($value);
    return $value;
}

$messaggiPerForm .= "</ul>";

$paginaHTML = str_replace("{listaAlbum}", $listaAlbum, $paginaHTML);
$paginaHTML = str_replace("{messaggiForm}", $messaggiPerForm, $paginaHTML);
$paginaHTML = str_replace("{valoreTitolo}", $titolo, $paginaHTML);
if ($durata == 0) {
    $paginaHTML = str_replace("{valoreDurata}", "00:00", $paginaHTML);
}
else {
    $paginaHTML = str_replace("{valoreDurata}", $durata, $paginaHTML);
}
if ($esplicito == "Yes") {
    $paginaHTML = str_replace("{checkedYes}", " checked", $paginaHTML);
    $paginaHTML = str_replace("{checkedNo}", "", $paginaHTML);
}
elseif ($esplicito == "No") {
    $paginaHTML = str_replace("{checkedNo}", " checked", $paginaHTML);
    $paginaHTML = str_replace("{checkedYes}", "", $paginaHTML);
}
$paginaHTML = str_replace("{valoreData}", $dataRadio, $paginaHTML);
$paginaHTML = str_replace("{valoreUrlVideo}", $urlVideo, $paginaHTML);
$paginaHTML = str_replace("{valoreNote}", $note, $paginaHTML);

echo $paginaHTML;
?>