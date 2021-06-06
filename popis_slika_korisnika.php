<?php

include_once("baza.php");
// spajamo se na bazu

/* pokrećemo sesiju funkciom session_start() */
session_start();

// Preusmjeravanje ako korisnik nije moderator ili admin
if ((isset($_SESSION['id_korisnik']) == false) || ($_SESSION['tip_korisnika'] > 1)) header("Location: index.php");

$ime_korisnika = $_SESSION["ime_korisnika"];
$id_moderatora = $_SESSION["id_korisnik"];

$poruka = "";

/*

POPIS SLIKA KORISNIKA GETOM DOBIVA 'planina'


Odabirom planine može vidjeti popis svih javnih slika koje su dodane za tu planinu sa imenom i prezimenom osobe koja je tu sliku postavila. 

} else if (isset($_GET['planina'])){
                $naziv = $_GET['planina'];
                if (isset($_GET['filter'])) {
                    $vrijeme_od = date('Y-m-d H:i:s', strtotime($_GET['vrijeme_od_sort']));
                    // $vrijeme_od = "01.10.2020 10:00:00";
                    $vrijeme_do = date('Y-m-d H:i:s', strtotime($_GET['vrijeme_do_sort']));
    
                    // echo date('Y-m-d H:i:s', strtotime($vrijeme_od));
    
                    $upit="SELECT slika.url, slika.slika_id FROM slika 
                    INNER JOIN planina ON planina.planina_id = slika.planina_id
                    WHERE status=1 AND planina.naziv LIKE '%{$naziv}%' AND slika.datum_vrijeme_slikanja BETWEEN '{$vrijeme_od}' AND '{$vrijeme_do}'";
                    $rezultat = izvrsiUpit($veza, $upit);
                    




Klikom na prezime dobiva galeriju javnih slika odabranog korisnika. 
Ako želi može blokirati korisnika (blokiran=1) čime sve njegove slike postaju privatne i korisnik ne može dodavati nove slike dok ga administrator
 ne od blokira (blokiran=0).



TIP KORISNIKA
0 - admin
1 - moderator
2 - reg korisnik

SELECT moderator.korisnik_id, moderator.planina_id 
INNER JOIN korisnik ON moderator.korisnik_id = korisnik.korisnik_id 
WHERE korisnik.tip_korisnika_id = 1 

"SELECT korisnik_id, planina_id FROM moderator WHERE korisnik_id = '{$id_korisnik}'";

*/

// blok koji se izvršava ako je gumb za blokiranje pritisnut

if (isset($_GET['id_korisnik'])) {
    $id_korisnik = $_GET['id_korisnik'];
    $id_planine = $_GET['planina'];

    // spoji se na bazu i blokiraj korisnika

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
               
    $upit = "SELECT slika.url, slika.korisnik_id, korisnik.ime, korisnik.prezime FROM slika INNER JOIN korisnik ON slika.korisnik_id = korisnik.korisnik_id
    WHERE slika.status=1 AND slika.planina_id = '{$id_planine}'";
                
    $rezultat = izvrsiUpit($veza, $upit);

    zatvoriVezuNaBazu($veza);

}



?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Popis planina - <?=$ime_korisnika?> - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>

        <?php 
        include_once("navigacija.php");
        ?>

        <section id="main">
            <h1>Popis slika planine <?=$ime_korisnika?></h1>

                
                <?php
                // var_dump($rezultat);
                if ($rezultat->num_rows != 0) {
                    echo "
                    <table>
                        <thead>
                            <th>Slika</th>
                            <th>Korisnik</th>                      
                        </thead>
                        <caption>Popis slika korisnika {$ime_korisnika}</caption>
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
                echo "<td><img src='{$url}' class='slika-popis'></td>\n";
                echo "<td>{$ime} <a href='galerija.php?korisnik={$id_korisnika_slika}'>{$prezime}</a></td>\n";
                echo "<td><form name='blokiraj' method='get' action='{$ime_skripte}'><input type='submit' class='gumb' value='Blokiraj korisnika'><input type='hidden' name='id_korisnik' value='{$id_korisnika_slika}'><input type='hidden' name='planina' value='{$id_planine}'></form></td>\n";
                echo "</tr>\n";
                }
                   

                ?>

                </tbody>
            </table>

            <p class="greska"><?=$poruka?></p>            

        </section>

        <?php include_once("podnozje.php");?>

    </body>
</html>