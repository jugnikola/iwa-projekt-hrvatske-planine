<?php
/*
TODO

*/
/*  MEHANIZAM PRIJAVE */
/* uključujemo skriptu za rad s bazom */
include_once("baza.php");
// spajamo se na bazu
$veza = spojiSeNaBazu();

/* pokrećemo sesiju funkciom session_start() */
session_start();

$greska = "";

/* odjava i brisanje sesije - sesija se briše ako je postavljena varijabla odjava */

if (isset($_GET["odjava"])) {
    /* poništavamo cijeli niz sesije sa UNSET */
    unset($_SESSION);
    session_destroy();
    
}

/* Logika provjere ako je forma za prijavu poslana */

if (isset($_POST["prijava_submit"])){
    
    $kor_ime = $_POST["kor_ime"];
    $lozinka = $_POST["lozinka"];
    
    
    // ukoliko su korisnicko ime i lozinka upisani i nisu prazni
    // probati ako radi samo sa praznim

    if ( (isset($kor_ime)) && (!empty($kor_ime)) && (isset($lozinka)) && (!empty($lozinka)) )   {
        
        $upit = "SELECT * FROM korisnik WHERE korisnicko_ime = '{$kor_ime}' AND lozinka = '{$lozinka}'";

        $prijavaUspjesna = false;

        $rezultat = izvrsiUpit($veza, $upit);

        while ($red = mysqli_fetch_array($rezultat)) {
            /* korisnik je pronađen u bazi, zapisuju se vrijednosti u sesiju */

            $_SESSION["id_korisnik"] = $red["korisnik_id"];
            $_SESSION["kor_ime"] = $red["korisnicko_ime"];
            $_SESSION["tip_korisnika"] = $red["tip_korisnika_id"];
            $_SESSION["ime_korisnika"] = $red["ime"] . " " . $red["prezime"];
            $prijavaUspjesna = true;

        }

        if ($prijavaUspjesna) {
            /* prijava je uspjesna */
            // redirekt na stranicu 
            header("Location: index.php");
            // korisnik je ulogiran, prekida se rad skripte nakon preusmjeravanja
            exit();

            /* optimizirati sljedeća dva else-a, malo promisliti o logici tak da se koristi samo jedan else*/
        } else {
            $greska = "Korisničko ime ili lozinka nisu ispravni!";
        }

    } else {
        $greska = "Korisničko ime ili lozinka nisu ispravni!";
    }
}

zatvoriVezuNaBazu($veza);

?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Prijava - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>

        <?php 
        include_once("navigacija.php");
        ?>

        <section id="main">
            <h1>Prijava korisnika</h1>

            <form id=prijava name=prijava method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
               
            <label for="kor_ime">Korisničko ime:</label><br>
                <input type="text" name="kor_ime" class="unos" autofocus><br>
                <label for="lozinka">Lozinka:</label><br>
                <input type="password" name="lozinka" class="unos"><br>
                <input type="submit" name="prijava_submit" class="prijava_submit gumb" value="Prijavi se">
            </div>
            
            <p class="greska"><?= $greska;?>
            </p>

        </section>

        <?php include_once("podnozje.php");?>

    </body>
</html>