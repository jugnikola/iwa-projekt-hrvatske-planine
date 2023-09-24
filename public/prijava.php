<?php
session_start();
include_once("baza.php");

$veza = spojiSeNaBazu();
$greska = "";

if (isset($_GET["odjava"])) {
    unset($_SESSION);
    session_destroy();
    
}
if (isset($_POST["prijava_submit"])){
    $kor_ime = $_POST["kor_ime"];
    $lozinka = $_POST["lozinka"];

    if ( (isset($kor_ime)) && (!empty($kor_ime)) && (isset($lozinka)) && (!empty($lozinka)) )   {
        $upit = "SELECT * FROM korisnik WHERE korisnicko_ime = '{$kor_ime}' AND lozinka = '{$lozinka}'";
        $prijavaUspjesna = false;
        $rezultat = izvrsiUpit($veza, $upit);
        while ($red = mysqli_fetch_array($rezultat)) {
            $_SESSION["id_korisnik"] = $red["korisnik_id"];
            $_SESSION["kor_ime"] = $red["korisnicko_ime"];
            $_SESSION["tip_korisnika"] = $red["tip_korisnika_id"];
            $_SESSION["ime_korisnika"] = $red["ime"] . " " . $red["prezime"];
            $prijavaUspjesna = true;
        }

        if ($prijavaUspjesna) {
            header("Location: index.php");
            exit();
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