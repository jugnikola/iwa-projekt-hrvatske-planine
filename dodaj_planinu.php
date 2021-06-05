<?php

// AŽURIRAJ KORISNIKA

include_once("baza.php");
$veza = spojiSeNaBazu();
// spajamo se na bazu
/* pokrećemo sesiju funkciom session_start() */
session_start();

$id_korisnik = $_SESSION['id_korisnik'];

// Preusmjeravanje ako korisnik nije moderator ili admin
if ((isset($_SESSION['id_korisnik']) == false) || ($_SESSION['tip_korisnika'] > 0)) header("Location: index.php");

$poruka="";


/*

Administrator uz svoje funkcionalnosti ima i sve funkcionalnosti kao i moderator. 
Unosi, ažurira i pregledava korisnike sustava te definira i ažurira njihove tipove.

*/

// Ako je poslana forma za ažuriranje, izvršava se upit za unos podataka isti kao i u dodaj_sliku.php

if (isset($_POST['dodaj-planinu-submit'])) {
    
    $naziv_unos = $_POST['naziv-planine'];
    $lokacija_unos = $_POST['lokacija'];
    $opis_unos = $_POST['opis'];
    $geo_sirina_unos = $_POST['geo-sirina'];
    $geo_duzina_unos = $_POST['geo-duzina'];

    // UNOS PLANINE
    
    $upit = "INSERT INTO `planina`(`naziv`, `opis`, `lokacija`, `geografska_sirina`, `geografska_duzina`) VALUES ('{$naziv_unos}','{$opis_unos}','{$lokacija_unos}','{$geo_sirina_unos}','{$geo_duzina_unos}')";
    $rezultat_unos = izvrsiUpit($veza, $upit);

    $id_nove_planine = mysqli_insert_id($veza);



// za svakog moderatora iz arraya moderatori
    foreach($_POST['moderatori'] as $id_moderatora){
            
        // dodati zapis za tog moderatora i tu planinu
        $upit_dodaj_moderatore = "INSERT INTO `moderator`(`korisnik_id`, `planina_id`) VALUES ({$id_moderatora}, {$id_nove_planine})";
        izvrsiUpit($veza, $upit_dodaj_moderatore);

    }

    if ($rezultat_unos) {
        $poruka = "Planina uspješno unesena.";
        header("Location: azuriraj_planinu.php?id={$id_nove_planine}");
    } else {
        $poruka = "Ažuriranje nije uspjelo.";
    }

    zatvoriVezuNaBazu($veza);

} 


// dohvaćanje moderatora za checkboxeve
$upit_svi_moderatori = "SELECT DISTINCT korisnik_id, ime, prezime FROM `korisnik` 
WHERE tip_korisnika_id = 1";

$svi_moderatori = izvrsiUpit($veza, $upit_svi_moderatori);
zatvoriVezuNaBazu($veza);

?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Dodaj planinu - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>

        <?php 
        include_once("navigacija.php");
        ?>

        <section id="main">
            <h1>Dodavanje planine</h1>

            <!-- <img src="<?=$url?>" class="slika-azuriraj" /> -->
            <form id=dodaj-planinu name=dodaj-planinu method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
                <label for="naziv-planine">Naziv planine:</label><br>
                <input type="text" name="naziv-planine"></br>
                <label for="opis">Opis:</label><br>
                <textarea name="opis" cols=60 rows=10 required></textarea><br>
                <label for="lokacija">Lokacija:</label><br>
                <input type="text" name="lokacija" required><br>
                <label for="geo-sirina">Geografska širina:</label><br>
                <input type="text" name="geo-sirina" required><br>
                <label for="geo-duzina">Geografska dužina:</label><br>
                <input type="text" name="geo-duzina" required ><br>
                <label for="moderatori[]">Moderatori planine:</label><br>
                <?php
                while ($red = mysqli_fetch_array($svi_moderatori)) {
                    $moderator_id = $red['korisnik_id'];
                    $ime_prezime = $red['ime'] . " " . $red['prezime'];
                    
                    echo "<input type='checkbox' name='moderatori[]' value='{$moderator_id}'>{$ime_prezime}<br>";
                }
                ?>
                <br>

                <input type="submit" name="dodaj-planinu-submit" id="dodaj-planinu-submit" value="Dodaj planinu">
                
            </form>
            
            <p class="greska"><?=$poruka?></p>

        </section>

        <?php include_once("podnozje.php");?>

    </body>
</html>