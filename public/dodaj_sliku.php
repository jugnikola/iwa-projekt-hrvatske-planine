<?php
include_once("baza.php");
session_start();

$id_korisnik = $_SESSION['id_korisnik'];
if (isset($_SESSION['tip_korisnika']) === false) header("Location: index.php");

$veza = spojiSeNaBazu();
$upit = "SELECT blokiran FROM korisnik WHERE korisnik_id = '{$id_korisnik}'";
$blokiran_upit = izvrsiUpit($veza, $upit);
zatvoriVezuNaBazu($veza);

$poruka="";
$blokiran = false;

while ($red = mysqli_fetch_array($blokiran_upit)) {
    if ($red['blokiran'] == 1) {
        $blokiran = true;
        $poruka = "Korisnik je blokiran";
    }
}

if (isset($_POST["dodaj-sliku-submit"])) {
    $planina_id = $_POST["planina-id"];
    $url = $_POST["url"];
    $dat_vrijeme = date('Y-m-d H:i:s', strtotime($_POST["dat-vrijeme"]));
    $naziv = $_POST["naziv-slike"];
    $opis = $_POST["opis-slike"];
    $status = $_POST["status"];
    $korisnik_id = $_SESSION["id_korisnik"];

    $veza = spojiSeNaBazu();
    $upit = "INSERT INTO `slika`(`planina_id`, `korisnik_id`, `naziv`, `url`, `opis`, `datum_vrijeme_slikanja`, `status`) VALUES ('{$planina_id}', '{$korisnik_id}', '{$naziv}', '{$url}', '{$opis}', '{$dat_vrijeme}', '{$status}')";
    
    $rezultat = izvrsiUpit($veza, $upit);

    if ($rezultat) {
        $poruka = "Planina uspješno unesena.";
        $id_nove_planine = mysqli_insert_id($veza);
        header("Location: slika.php?id={$id_nove_planine}");
    } else {
        $poruka = "Zapis nije uspio.";
    }
    zatvoriVezuNaBazu($veza);
}
?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Dodavanje slike - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <?php 
        include_once("navigacija.php");
        ?>
        <section id="main">
            <h1>Dodavanje slike</h1>
            <table class="tablica-dodaj">
                <tbody>
            <form id=dodaj-sliku name=dodaj-sliku method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
                <tr>
                    <td><label for="">Planina:</label></td>
                    <td><select name="planina-id" class="galerija-filtracija dodaj-azuriraj" autofocus required>
                    <?php 
                    $veza = spojiSeNaBazu();
                    $upit = "SELECT planina_id, naziv FROM planina";
                    $rezultat = izvrsiUpit($veza, $upit);
                    while ($red = mysqli_fetch_array($rezultat)) {
                        $planina_id = $red['planina_id'];
                        $naziv = $red['naziv'];
                        echo "<option value='{$planina_id}'>{$naziv}</option>";
                    }
                    zatvoriVezuNaBazu($veza);
                ?>
                    </select></td>
                </tr>
                <tr>
                    <td><label for="url">URL slike planine:</label></td>
                    <td><input type="url" name="url" class="galerija-filtracija dodaj-azuriraj" required></td>
                </tr>
                    <tr>
                    <td><label for="dat-vrijeme">Datum i vrijeme slikanja:</label></td>
                    <td><input type="text" name="dat-vrijeme" class="galerija-filtracija dodaj-azuriraj" value="01.06.2021 00:00:00" required></td>
                </tr>
                    <tr>
                    <td><label for="naziv-slike">Naziv slike:</label></td>
                    <td><input type="text" name="naziv-slike" class="galerija-filtracija dodaj-azuriraj" required></td>
                </tr>
                    <tr>
                    <td><label for="opis-slike">Opis slike:</label></td>
                    <td><textarea name="opis-slike" rows="10" cols="50" class="galerija-filtracija dodaj-azuriraj"  required></textarea></td>
                </tr>
                    <tr>
                    <td><label for="status">Status slike:</label></td>
                    <td><input type="radio" name="status" value=1 checked style="margin-left: 10em">Javna<input type="radio" name="status" value=0 style="margin-left: 2em">Privatna</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="dodaj-sliku-submit" id="dodaj-sliku-submit" class="gumb" value="Dodaj sliku" style="margin-left: 10em;margin-top: 1em;" <?php if ($blokiran) echo 'disabled';?>></td>
                    </tr>
                </tbody>
            </form>
                </table>
            <p class="greska"><?=$poruka?></p>
        </section>
        <?php include_once("podnozje.php");?>
    </body>
</html>