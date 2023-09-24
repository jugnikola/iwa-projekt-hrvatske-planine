<?php
session_start();
include_once("baza.php");

if ((isset($_SESSION['id_korisnik']) == false) || ($_SESSION['tip_korisnika'] > 1)) header("Location: index.php");

$ime_korisnika = $_SESSION["ime_korisnika"];
$id_moderatora = $_SESSION["id_korisnik"];

$poruka = "";

if (isset($_GET['id_korisnik'])) {
    $id_korisnik = $_GET['id_korisnik'];
    $id_planine = $_GET['planina'];

    $veza = spojiSeNaBazu();
    $upit = "UPDATE `korisnik` SET `blokiran` = 1 WHERE `korisnik_id` = '{$id_korisnik}'";
    $rezultat_blokiran = izvrsiUpit($veza, $upit);

    if ($rezultat_blokiran) $blokiran = true;

    $upit = "UPDATE `slika` SET `status` = 0 WHERE `korisnik_id` = '{$id_korisnik}'";
    $rezultat_blokiran = izvrsiUpit($veza, $upit);

    if ($rezultat_blokiran) $privatno = true;

    if ($blokiran && $privatno) {
        $poruka = "Korisnik blokiran, status svih slika postavljen na 'privatno'";
    }
    zatvoriVezuNaBazu($veza);
}

if (isset($_GET['planina'])) {
    $id_planine = $_GET['planina'];

    $veza = spojiSeNaBazu();
    $id_korisnik = $_SESSION["id_korisnik"];
               
    $upit = "SELECT slika.url, slika.korisnik_id, korisnik.ime, korisnik.prezime, planina.naziv FROM slika INNER JOIN korisnik ON slika.korisnik_id = korisnik.korisnik_id
    INNER JOIN planina ON slika.planina_id = planina.planina_id
    WHERE slika.status=1 AND slika.planina_id = '{$id_planine}'";
                
    $rezultat = izvrsiUpit($veza, $upit);
    $upit_naziv_planine = "SELECT naziv FROM planina WHERE planina_id = '{$id_planine}'";
    $rezultat_naziv_planine = izvrsiUpit($veza, $upit_naziv_planine);
    zatvoriVezuNaBazu($veza);
}
?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Popis slika planine - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <?php 
        include_once("navigacija.php");        

        $naziv_red = mysqli_fetch_array($rezultat_naziv_planine);
        $naziv_planine = $naziv_red['naziv'];
        ?>
        <section id="main">
            <h1>Popis slika planine <?=$naziv_planine?></h1>
            <?php
            if ($rezultat->num_rows != 0) {
                echo "
                <table id='tablica-popis-slika-korisnika'>
                    <thead>
                        <th>Slika</th>
                        <th>Korisnik</th>                      
                    </thead>
                    <tbody>";
            } else {
                echo "<p class='greska'>Za odabranu planinu nema slika.</p>";
            }

            while ($red = mysqli_fetch_array($rezultat)){
            $url = $red['url'];
            $ime = $red['ime'];
            $prezime = $red['prezime'];
            $id_korisnika_slika = $red['korisnik_id'];
            $ime_skripte = $_SERVER['PHP_SELF'];
            echo "<tr>\n";
            echo "<td><img src='{$url}' class='slika-korisnika'></td>\n";
            echo "<td>{$ime} <a href='galerija.php?korisnik={$id_korisnika_slika}'>{$prezime}</a></td>\n";
            echo "<td><form name='blokiraj' method='get' action='{$ime_skripte}'><input type='submit' class='gumb' value='Blokiraj korisnika'><input type='hidden' name='id_korisnik' value='{$id_korisnika_slika}'><input type='hidden' name='planina' value='{$id_planine}'></form></td>\n";
            echo "</tr>\n";
            }
            ?>

                </tbody>
            </table>
            <p class="uspjeh"><?=$poruka?></p>            
        </section>
        <?php include_once("podnozje.php");?>
    </body>
</html>