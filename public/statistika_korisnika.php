<?php
session_start();
include_once("baza.php");

if ((isset($_SESSION['id_korisnik']) == false) || ($_SESSION['tip_korisnika'] > 0)) header("Location: index.php");

$ime_korisnika = $_SESSION["ime_korisnika"];
$id_admina = $_SESSION["id_korisnik"];

$veza = spojiSeNaBazu();
$upit_korisnici = "SELECT korisnik.*, tip_korisnika.naziv FROM korisnik INNER JOIN tip_korisnika ON tip_korisnika.tip_korisnika_id = korisnik.tip_korisnika_id ORDER BY prezime";
$rezultat_korisnici = izvrsiUpit($veza, $upit_korisnici);
?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Statistika korisnika - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <?php 
        include_once("navigacija.php");
        ?>
        <section id="main">
            <h1>Statistika korisnika</h1>
            <table style="margin-left: 12%">
                <thead>
                    <th>Korisničko ime</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>e-mail</th>
                    <th>Tip korisnika</th>
                    <th>Broj javnih slika</th>
                    <th>Broj privatnih slika</th>
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
                    
                    $upit_br_javnih = "SELECT COUNT(status) as broj_javnih FROM slika WHERE status=1 and korisnik_id='{$id_korisnik}'";

                    $rezultat_javne = izvrsiUpit($veza, $upit_br_javnih);
                    $br_javnih_slika = mysqli_fetch_array($rezultat_javne);

                    echo "<td>{$br_javnih_slika['broj_javnih']}</td>\n";

                    $upit_br_privatne = "SELECT COUNT(status) as broj_privatnih FROM slika WHERE status=0 and korisnik_id='{$id_korisnik}'"; 

                    $rezultat_privatne = izvrsiUpit($veza, $upit_br_privatne);
                    $br_privatnih_slika = mysqli_fetch_array($rezultat_privatne);
                    echo "<td>{$br_privatnih_slika['broj_privatnih']}</td>\n";
                    echo "</tr>\n";
                }
                zatvoriVezuNaBazu($veza);
                ?>
                </tbody>
            </table>
        </section>
        <?php include_once("podnozje.php");?>
    </body>
</html>