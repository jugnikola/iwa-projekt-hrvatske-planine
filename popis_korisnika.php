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

ADMIN - popis korisnika


Unosi, ažurira i pregledava korisnike sustava te definira i ažurira njihove tipove. 

TIP KORISNIKA
0 - admin
1 - moderator
2 - reg korisnik

*/

$veza = spojiSeNaBazu();

$upit_korisnici = "SELECT korisnik.*, tip_korisnika.naziv FROM korisnik INNER JOIN tip_korisnika ON tip_korisnika.tip_korisnika_id = korisnik.tip_korisnika_id ORDER BY korisnicko_ime";
$rezultat_korisnici = izvrsiUpit($veza, $upit_korisnici);
zatvoriVezuNaBazu($veza);

?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Popis korisnika - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>

        <?php 
        include_once("navigacija.php");
        ?>

        <section id="main">
            <h1>Popis korisnika</h1>

<!--    MAKNUTI KADA SE VERIFICIRAJU ZAHTJEVI PROJEKTA
            <form action="statistika_korisnika.php" method="post" style="float: left; margin-right: 20px;">
                <input type="submit" class="gumb" name="statistika-korisnika" value="Statistika korisnika">
            </form>
            <form action="popis_blokiranih_korisnika.php" method="post" style="float: left; margin-right: 20px;">
                <input type="submit" class="gumb" name="popis-blokiranih" value="Blokirani korisnici">
            </form>
            
            <form action="dodaj_korisnika.php" method="post" >
                <input type="submit" class="gumb" name="dodaj-korisnika" value="Dodaj novog korisnika">
            </form>
-->
            <div style="text-align: center">
                <a href="statistika_korisnika.php"><button class="gumb popis-korisnika-gumb" >Statistika korisnika</button></a>
                <a href="popis_blokiranih_korisnika.php"><button class="gumb popis-korisnika-gumb" style="margin-right: 1em;">Popis blokiranih korisnika</button></a>
                <a href="dodaj_korisnika.php"><button class="gumb popis-korisnika-gumb">Dodaj novog korisnika</button></a>
            </div>
            <table style="margin-left: 20%">
                <thead>
                    <th>Korisničko ime</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>e-mail</th>
                    <th>Tip korisnika</th>
                </thead>
                                
                <tbody>
                
                <?php
                                
                while ($red = mysqli_fetch_array($rezultat_korisnici)){
                    $id_korisnik = $red['korisnik_id'];
                    $kor_ime = $red['korisnicko_ime'];
                    $ime = $red['ime'];
                    $prezime = $red['prezime'];
                    $email = $red['email'];
                    $tip_korisnika = $red['tip_korisnika_id'];
                    $naziv_tipa = $red['naziv'];

                    echo "<tr>\n";
                    echo "<td>{$kor_ime}</td>\n";
                    echo "<td>{$ime}</td>\n";
                    echo "<td>{$prezime}</td>\n";
                    echo "<td>{$email}</td>\n";
                    echo "<td>{$naziv_tipa}</td>\n";
                    echo "<td><a href='azuriraj_korisnika.php?id={$id_korisnik}'><button class='gumb'>Ažuriraj</button></a>";
                    echo "</tr>\n";
                }
                
               

                ?>

                </tbody>
            </table>
            
        </section>
        <?php include_once("podnozje.php");?>
    </body>
</html>