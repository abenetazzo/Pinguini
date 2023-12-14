<?php
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("albumTemplate.html");

$stringaAlbum = "";
$titoloAlbum = "";
$id = $_GET["id"];
$idAlbum = (filter_var($id, FILTER_VALIDATE_INT)) ? $id : 0;

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();

if ($connectionOk) {
    [$id,$titoloAlbum,$copertina,$dataPubblicazione,$durataAlbum] = $connection -> getAlbum($idAlbum);
    $tracceAlbum = $connection -> getTracceAlbum($idAlbum);
    if ($titoloAlbum != null) {
        $stringaAlbum .= "<img src=\"$copertina\" id=\"albumCover\">"
            . "<dl id=\"albumInfo\"><div><dt>Durata: </dt><dd><time datetime=\"$durataAlbum\"><span>$durataAlbum</span></dd></div>"
            . "<div><dt>Data di Uscita: </dt><dd><time datetime=\"$dataPubblicazione\"><span>$dataPubblicazione</span></dd></div>"
            . "<div><dt lang=\"en\">Tracklist: </dt><dd><dl id=\"tracklist\">";
        foreach ($tracceAlbum as $traccia) {
            $stringaAlbum .= "<div><dd>" . $traccia["ID"] . " - </dd><dt>"
                . $traccia["Titolo"] . " </dt><dd>"
                . $traccia["Durata"] . "</dd></div>";
        }
        $stringaAlbum .= "</dl></dd></div></dl>";
    }
    else {
        $stringaAlbum = "<p>Questo album non esiste o l'id non è corretto.</p>";
    }
}
else {
    $stringaAlbum = "<p>Non sono presenti album.</p>";
}

$paginaHTML = str_replace("{album}", $stringaAlbum, $paginaHTML);
$paginaHTML = str_replace("{titolo}", $titoloAlbum, $paginaHTML);
echo $paginaHTML;
?>