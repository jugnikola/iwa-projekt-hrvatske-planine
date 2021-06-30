<?php
session_start();
include_once("baza.php");

if (isset($_POST['blokiraj-gumb'])) {
    $id_korisnik = $_POST['id_korisnik'];
    $id_planine = $_POST['id_planine'];

    $veza = spojiSeNaBazu();
    $upit = "UPDATE `korisnik` SET `blokiran` = 1 WHERE `korisnik_id` = '{$id_korisnik}'";
    $rezultat = izvrsiUpit($veza, $upit);

    if ($rezultat) {
        header("Location: popis_slika_korisnika.php?planina={$id_planine}");
        echo "Korisnik je blokiran";
    }
    zatvoriVezuNaBazu($veza);
}

?>