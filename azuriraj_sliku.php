<?php

include_once("baza.php");
// spajamo se na bazu
/* pokrećemo sesiju funkciom session_start() */
session_start();

$id_korisnik = $_SESSION['id_korisnik'];

// provjera ako je korisnik prijavljen, ako nije redirecta ga se na početnu stranicu
// DODATI AKO JE BLOKIRAN DA SE ISPIŠE PORUKA
if (isset($_SESSION['tip_korisnika']) === false) header("Location: index.php");

// PROVJERA AKO JE KORISNIK BLOKIRAN DA MU SE ISPIŠE PORUKA DA JE BLOKIRAN
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

/*

Korisnik može ažurirati podatke o slici pri čemu može promijeniti status slike (0 - privatna) ili (1 – javna).

*/


global $planina_id_unesena;

if (isset($_GET['id'])) {
    $id_slike = $_GET['id'];

    $veza = spojiSeNaBazu();

    // upit koji uzima postojeće podatke iz baze na temelju id-a slike, onda ih zapisujemo u varijable i kasnije insertamo kao value u formu

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

// Ako je poslana forma za ažuriranje, izvršava se upit za unos podataka isti kao i u dodaj_sliku.php

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

            <img src="<?=$url?>" class="slika-azuriraj" />
            <form id=azuriraj-planinu name=azuriraj-planinu method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
                <label for="">Planina:</label><br>
                
                <select name="planina-id" autofocus required>
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
                
                </select>
                
                <br>
                <label for="url">URL slike planine:</label><br>
                <input type="url" name="url" required value="<?=$url?>"><br>
                <label for="dat-vrijeme">Datum i vrijeme slikanja:</label><br>
                <input type="text" name="dat-vrijeme" value="<?=$dat_vrijeme_prikaz?>" required><br>
                <label for="naziv-slike">Naziv slike:</label><br>
                <input type="text" name="naziv-slike" required value="<?=$naziv_slike?>"><br>
                <label for="opis-slike">Opis slike:</label><br>
                <textarea name="opis-slike" rows="10" cols="50" required><?=$opis?></textarea><br>
                <label for="status">Status slike:</label>
                <input type="radio" name="status" value=1 <?php if ($status == 1) echo 'checked'; ?> >Javna
                <input type="radio" name="status" value=0 <?php if ($status == 0) echo 'checked'; ?> >Privatna<br>
                <input type="hidden" name="id-slike" value="<?=$id_slike?>">

                <input type="submit" name="azuriraj-planinu-submit" id="azuriraj-planinu-submit" value="Ažuriraj sliku" <?php if ($blokiran) echo 'disabled';?>>
                
            </form>
            
            <p class="greska"><?=$poruka?></p>

        </section>

        <?php include_once("podnozje.php");?>

    </body>
</html>