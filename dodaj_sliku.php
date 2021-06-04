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

Korisnik može dodavati nove slike planina. 
Prilikom dodavanja bira planinu, unosi url do slike na webu, 
definira datum i vrijeme slikanja, daje naziv i opis slici, 
automatski se status slike postavlja na 1 (javna). 

*/

if (isset($_POST["dodaj-sliku-submit"])) {

    $planina_id = $_POST["planina-id"];
    $url = $_POST["url"];
    
    $dat_vrijeme = date('Y-m-d h:i:s', strtotime($_POST["dat-vrijeme"]));

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
    /*while ($red = mysqli_fetch_array($rezultat)) {
        
    }*/

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

            <form id=dodaj-sliku name=dodaj-sliku method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
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
                        echo "<option value='{$planina_id}'>{$naziv}</option>";
                    }
                    zatvoriVezuNaBazu($veza);
                ?>
                
                </select>
                
                <br>
                <label for="url">URL slike planine:</label><br>
                <input type="url" name="url" required><br>
                <label for="dat-vrijeme">Datum i vrijeme slikanja:</label><br>
                <input type="text" name="dat-vrijeme" value="01.06.2021 00:00:00" required><br>
                <label for="naziv-slike">Naziv slike:</label><br>
                <input type="text" name="naziv-slike" required><br>
                <label for="opis-slike">Opis slike:</label><br>
                <textarea name="opis-slike" rows="10" cols="50" required></textarea><br>
                <label for="status">Status slike:</label>
                <input type="radio" name="status" value=1 checked>Javna
                <input type="radio" name="status" value=0>Privatna<br>

                <input type="submit" name="dodaj-sliku-submit" id="dodaj-sliku-submit" value="Dodaj sliku" <?php if ($blokiran) echo 'disabled';?>>
                
            </form>
            
            <p class="greska"><?=$poruka?></p>

            </p>

        </section>

        <?php include_once("podnozje.php");?>

    </body>
</html>