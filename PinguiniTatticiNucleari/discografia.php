<?php
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("discografiaTemplate.html");

$stringaAlbum = "";
$listaAlbum = "";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();

if ($connectionOk) {
    $listaAlbum = $connection -> getListaAlbum();
    if($listaAlbum != null) {
        foreach ($listaAlbum as $album) {
            $stringaAlbum .= "<li><a href=\"album.php?id="
                . $album["ID"] . "\" id=\""
                . $album["idCss"] ."\"><img src=\""
                . $album["Copertina"] ."\" alt=\""
                . $album["Titolo"] ."\"></a></li>";
        }
    }
    else {
        $stringaAlbum = "<p>Non sono presenti album.</p>";
    }
}
else {
    $stringaAlbum = "<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</p>";
}

$paginaHTML = str_replace("{album}",$stringaAlbum, $paginaHTML);
echo $paginaHTML;
?>