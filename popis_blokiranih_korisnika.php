<?php

include_once("baza.php");
// spajamo se na bazu

/* pokrećemo sesiju funkciom session_start() */
session_start();

// Preusmjeravanje ako korisnik nije moderator ili admin
if ((isset($_SESSION['id_korisnik']) == false) || ($_SESSION['tip_korisnika'] > 0)) header("Location: index.php");

$ime_korisnika = $_SESSION["ime_korisnika"];
$id_admina = $_SESSION["id_korisnik"];

/*

ADMIN - popis blokiranih korisnika

Administrator vidi popis blokiranih korisnika (blokiran=1) te ih može od blokirati (blokiran=0). 

TIP KORISNIKA
0 - admin
1 - moderator
2 - reg korisnik

*/

$poruka = "";

$veza = spojiSeNaBazu();

$upit_korisnici = "SELECT korisnik.*, tip_korisnika.naziv FROM korisnik INNER JOIN tip_korisnika ON tip_korisnika.tip_korisnika_id = korisnik.tip_korisnika_id WHERE korisnik.blokiran = 1 ORDER BY korisnicko_ime";
$rezultat_korisnici = izvrsiUpit($veza, $upit_korisnici);
zatvoriVezuNaBazu($veza);

if (isset($_POST["odblokiraj-korisnika"])) {
    $id_korisnik_odblokiraj = $_POST["id_korisnik"];
    $veza = spojiSeNaBazu();
    $upit_odblokiraj = "UPDATE `korisnik` SET `blokiran`=0 WHERE `korisnik_id`='{$id_korisnik_odblokiraj}'";
    $rezultat_odblokiraj = izvrsiUpit($veza, $upit_odblokiraj);
    zatvoriVezuNaBazu($veza);

    if ($rezultat_odblokiraj) {
        $poruka = "Korisnik odblokiran.";
    }

}

?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Popis blokiranih korisnika - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>

        <?php 
        include_once("navigacija.php");
        ?>

        <section id="main">
            <h1>Popis blokiranih korisnika</h1>

           <!--<form action="popis_blokiranih_korisnika.php" method="post" style="float: left; margin-right: 20px;">
                <input type="submit" class="gumb" name="popis-blokiranih" value="Blokirani korisnici">
            </form>
            <form action="dodaj_korisnika.php" method="post" >
                <input type="submit" class="gumb" name="dodaj-korisnika" value="Dodaj novog korisnika">
            </form> -->

            <?php
            if ($rezultat_korisnici->num_rows != 0) {
                    echo "
                    <table style='margin-left: 20%'>
                <thead>
                    <th>Korisničko ime</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>e-mail</th>
                    <th>Tip korisnika</th>
                </thead>
                                
                <tbody>";
                } else {
                    echo "<p class='greska'>Nema blokiranih korisnika.</p>";
                }

                while ($red = mysqli_fetch_array($rezultat_korisnici)){
                    $id_korisnik = $red['korisnik_id'];
                    $kor_ime = $red['korisnicko_ime'];
                    $ime = $red['ime'];
                    $prezime = $red['prezime'];
                    $email = $red['email'];
                    $tip_korisnika = $red['tip_korisnika_id'];
                    $naziv_tipa = $red['naziv'];
                    $ime_skripte = $_SERVER['PHP_SELF'];

                    echo "<tr>\n";
                    echo "<td>{$kor_ime}</td>\n";
                    echo "<td>{$ime}</td>\n";
                    echo "<td>{$prezime}</td>\n";
                    echo "<td>{$email}</td>\n";
                    echo "<td>{$naziv_tipa}</td>\n";
                    echo "<td><form method='post' action='{$ime_skripte}'><input type='submit' name='odblokiraj-korisnika' class='gumb' value='Odblokiraj korisnika'><input type='hidden' name='id_korisnik' value='{$id_korisnik}'></form></td>\n";
                    echo "</tr>\n";
                }

                ?>

                </tbody>
            </table>
            <?= "<p class='uspjeh'>{$poruka}</p>"?>
        </section>
        <?php include_once("podnozje.php");?>
    </body>
</html>