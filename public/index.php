<?php
session_start();
//phpinfo();
?>
<html lang="hr">
    <head>
        <title>Početna - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <?php 
        include_once("navigacija.php");
        ?>
        <section id="main">
            <h2 style="text-align:center;">Dobrodošli na početnu stranicu Hrvatske Planine!</h2>
            <div class="pocetna-sadrzaj" 
                <?php
                if (!isset($_SESSION["tip_korisnika"])) {echo "style='grid-template-columns: 1fr; justify-items: center'>";}
                else if (isset($_SESSION["tip_korisnika"]) && ($_SESSION["tip_korisnika"] == 2)) {
                    echo "style='grid-template-columns: auto; justify-items: center'>";
                } 
                else if (isset($_SESSION["tip_korisnika"]) && $_SESSION["tip_korisnika"] == 1) {echo "style='grid-template-columns: 1fr 1fr'>";} 
                
                else {
                    echo ">";
                }?>
                <div class="pocetna-sadrzaj-stavka"  <?php if ( isset($_SESSION["tip_korisnika"]) && $_SESSION["tip_korisnika"] == 2 || isset($_SESSION["tip_korisnika"]) &&$_SESSION["tip_korisnika"] == 1) echo "style='margin: 0 auto'";?>>
                    <a href="galerija.php">
                        <img src="slike/galerija-ikona.png" width="100px" class="pocetna-slika">
                        <br>
                        <button class="gumb-pocetna">Pregledaj galeriju javnih slika</button>
                    </a>
                </div>
                <?php 

                    if (isset($_SESSION["tip_korisnika"]) && $_SESSION["tip_korisnika"] == 1) {
                        echo "
                        <div class='pocetna-sadrzaj-stavka'>
                            <a href='popis_planina_moderatora.php'>
                                <img src='slike/planine-ikona.png' width='100px' class='pocetna-slika'>
                                <br>
                                <button class='gumb-pocetna'>Pregledaj moderirane planine</button>
                            </a>
                        </div>";
                    } else if (isset($_SESSION["tip_korisnika"]) && $_SESSION["tip_korisnika"] == 0) {
                        echo "
                        <div class='pocetna-sadrzaj-stavka'>
                            <a href='popis_planina.php'>
                                <img src='slike/planine-ikona.png' width='100px' class='pocetna-slika'>
                                <br>
                                <button class='gumb-pocetna'>Pregledaj planine</button>
                            </a>
                        </div>
                        <div class='pocetna-sadrzaj-stavka'>
                            <a href='popis_korisnika.php'>
                                <img src='slike/korisnici-ikona.png' width='100px' class='pocetna-slika'>
                                <br>
                                <button class='gumb-pocetna'>Pregledaj korisnike</button>
                            </a>
                        </div>";
                    }
                ?> 
            </div>
        </section>
        <?php
        include_once("podnozje.php");
        ?>
    </body>
</html>

