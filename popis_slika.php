<?php

include_once("baza.php");
// spajamo se na bazu


/* pokrećemo sesiju funkciom session_start() */
session_start();

$ime_korisnika = $_SESSION["ime_korisnika"];






/*

Korisnik vidi popis svih svojih slika sa informacijom o statusu.

*/




?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Popis slika - <?=$ime_korisnika?> - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>

        <?php 
        include_once("navigacija.php");
        ?>

        <section id="main">
            <h1>Popis slika korisnika <?=$ime_korisnika?></h1>

            <table>
                <thead>
                    <th>Slika</th>
                    <th>Naziv</th>
                    <!-- <th>URL</th> -->
                    <th>Opis</th>
                    <th style="width:15%;">Datum i vrijeme slikanja</th>
                    <th style="text-align:center">Status</th>
                    <th>Ažuriraj sliku</th>
                </thead>
                
                <caption>Popis slika korisnika <?=$ime_korisnika?></caption>
                
                <tbody>
                
                <?php
                $veza = spojiSeNaBazu();

                $id_korisnik = $_SESSION["id_korisnik"];
                
                $upit = "SELECT * FROM slika WHERE korisnik_id = '{$id_korisnik}'";
                
                $rezultat = izvrsiUpit($veza, $upit);
                
                while ($red = mysqli_fetch_array($rezultat)){
                    $url = $red['url'];
                    $naziv = $red['naziv'];
                    $opis = $red['opis'];
                    $dat_vrijeme = date('d.m.Y h:i:s', strtotime($red['datum_vrijeme_slikanja']));
                    $slika_id = $red['slika_id'];

                    if ($red['status'] == 1) {
                        $status = "Javna";
                    } else {
                        $status = "Privatna";
                    }

                    echo "<tr>\n";
                    echo "<td><a href='slika.php?id={$slika_id}'><img src='{$url}' class='slika-popis'></a></td>\n";
                    echo "<td>{$naziv}</td>\n";
                    // echo "<td>{$url}</td>\n";
                    echo "<td>{$opis}</td>\n";
                    echo "<td>{$dat_vrijeme}</td>\n";
                    echo "<td>{$status}</td>\n";
                    echo "<td><a href='azuriraj_sliku.php?id={$slika_id}'>Ažuriraj</a></td>";
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