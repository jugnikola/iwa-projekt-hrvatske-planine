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

global $planina_id_unesena;

if (isset($_GET['id'])) {
    $id_slike = $_GET['id'];

    $veza = spojiSeNaBazu();

    $upit = "SELECT * FROM slika WHERE slika_id = '{$id_slike}'";    

    $rezultat = izvrsiUpit($veza, $upit);

    while ($red = mysqli_fetch_array($rezultat)) {
        $id_slike = $red['slika_id'];
        $url = $red['url'];
        $dat_vrijeme_prikaz = date('d.m.Y H:i:s', strtotime($red['datum_vrijeme_slikanja']));
        $naziv_slike = $red['naziv'];
        $opis = $red['opis'];
        $status = $red['status'];
        
        
        $planina_id_unesena = $red['planina_id'];

    }
    zatvoriVezuNaBazu($veza);
}

if (isset($_POST['azuriraj-planinu-submit'])) {
    $planina_id = $_POST["planina-id"];
    $id_slike = $_POST["id-slike"];
    $url = $_POST["url"];
    $dat_vrijeme = date('Y-m-d H:i:s', strtotime($_POST["dat-vrijeme"]));
    $naziv = $_POST["naziv-slike"];
    $opis = $_POST["opis-slike"];
    $status = $_POST["status"];
    $korisnik_id = $_SESSION["id_korisnik"];

    $veza = spojiSeNaBazu();
    $upit = "UPDATE `slika` SET `planina_id`='{$planina_id}',`naziv`='{$naziv}',`url`='{$url}',`opis`='{$opis}',`datum_vrijeme_slikanja`='{$dat_vrijeme}',`status`='{$status}' WHERE `slika_id` = '{$id_slike}'";
    
    $rezultat = izvrsiUpit($veza, $upit);

    if ($rezultat) {
        $poruka = "Planina uspješno ažurirana.";
        header("Location: slika.php?id={$id_slike}");
    } else {
        $poruka = "Ažuriranje nije uspjelo.";
    }
    zatvoriVezuNaBazu($veza);
}
?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Ažuriranje slike - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <?php 
        include_once("navigacija.php");
        ?>
        <section id="main">
            <h1>Ažuriranje slike</h1>
            <table class="tablica-dodaj">
                <tbody>
                <form id=azuriraj-planinu name=azuriraj-planinu method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
                <tr>
                    <td></td>
                    <td><img src="<?=$url?>" class="slika-azuriraj" /></td>
                </tr>
                <tr>
                    <td><label for="">Planina:</label></td>
                    <td><select name="planina-id" class="galerija-filtracija dodaj-azuriraj" autofocus required>
                    <?php 
                        // treba napraviti upit kojim će se ispisati sve dostupne planine u bazi
                        $veza = spojiSeNaBazu();
                        $upit = "SELECT planina_id, naziv FROM planina";

                        $rezultat = izvrsiUpit($veza, $upit);

                        while ($red = mysqli_fetch_array($rezultat)) {
                            $planina_id = $red['planina_id'];
                            $naziv = $red['naziv'];
                            echo "<option value='{$planina_id}'";
                            if ($planina_id == $planina_id_unesena) echo " selected ";
                            echo ">{$naziv}</option>";
                        }
                        zatvoriVezuNaBazu($veza);
                    ?>
                    </select></td>
                    </tr>
                    <tr>
                        <td><label for="url">URL slike planine:</label></td>
                        <td><input type="url" name="url" required class="galerija-filtracija dodaj-azuriraj" value="<?=$url?>"></td>
                    <tr>
                        <td><label for="dat-vrijeme">Datum i vrijeme slikanja:</label></td>
                        <td><input type="text" name="dat-vrijeme" class="galerija-filtracija dodaj-azuriraj" value="<?=$dat_vrijeme_prikaz?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="naziv-slike">Naziv slike:</label></td>
                        <td><input type="text" name="naziv-slike" required class="galerija-filtracija dodaj-azuriraj" value="<?=$naziv_slike?>"></td>
                    </tr>
                    <tr>
                        <td><label for="opis-slike">Opis slike:</label></td>
                    <td><textarea name="opis-slike" rows="10" cols="50" required class="galerija-filtracija dodaj-azuriraj"><?=$opis?></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="status">Status slike:</label></td>
                        <td><input type="radio" name="status" style="margin-left: 10em;"value=1 <?php if ($status == 1) echo 'checked'; ?> >Javna
                        <input type="radio" name="status"  value=0 <?php if ($status == 0) echo 'checked'; ?> >Privatna<br>
                        <input type="hidden" name="id-slike" value="<?=$id_slike?>">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="azuriraj-planinu-submit" style="margin-left: 10em;"id="azuriraj-planinu-submit" value="Ažuriraj sliku" class="gumb" <?php if ($blokiran) echo 'disabled';?>></td>
                    </tr>
                    </form>
                </tbody>
                </table>
            <p class="greska"><?=$poruka?></p>
        </section>
        <?php include_once("podnozje.php");?>
    </body>
</html>