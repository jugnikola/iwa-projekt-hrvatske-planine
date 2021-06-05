<?php

// AŽURIRAJ KORISNIKA

include_once("baza.php");
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

if (isset($_POST['azuriraj-korisnika-submit'])) {
    $tip_korisnika_unos = $_POST["korisnik-tip"];
    $korisnik_id_unos = $_POST["id-korisnik"];
    $kor_ime_unos = $_POST["kor-ime"];
    $lozinka_unos = $_POST["lozinka"];
    $ime_unos = $_POST["ime"];
    $prezime_unos = $_POST["prezime"];
    $email_unos = $_POST["email"];
    $slika_unos = $_POST["slika"];
    if (isset($_POST["status"])) {
        $status_unos = 1;
    } else {
        $status_unos = 0;
    }

    $veza = spojiSeNaBazu();
    $upit = "UPDATE `korisnik` SET `tip_korisnika_id`='{$tip_korisnika_unos}',`korisnicko_ime`='{$kor_ime_unos}',`lozinka`='{$lozinka_unos}',`ime`='{$ime_unos}',`prezime`='{$prezime_unos}',`email`='{$email_unos}',`blokiran`='{$status_unos}',`slika`='{$slika_unos}' WHERE `korisnik_id` = '{$korisnik_id_unos}'";
    
    $rezultat_unos = izvrsiUpit($veza, $upit);

    if ($rezultat_unos) {
        $poruka = "Korisnik uspješno ažuriran.";
        header("Location: azuriraj_korisnika.php?id={$korisnik_id_unos}?");
    } else {
        $poruka = "Ažuriranje nije uspjelo.";
    }

    zatvoriVezuNaBazu($veza);

} 

if (isset($_GET['id'])) {    
    $veza = spojiSeNaBazu();
    $upit = "SELECT * FROM korisnik WHERE korisnik_id = '{$_GET['id']}'";
    $rezultat = izvrsiUpit($veza, $upit);
    zatvoriVezuNaBazu($veza);

    while ($red = mysqli_fetch_array($rezultat)){
        $korisnik_id = $red['korisnik_id'];
        $tip_korisnika = $red['tip_korisnika_id'];
        $kor_ime = $red['korisnicko_ime'];
        $lozinka = $red['lozinka'];
        $ime = $red['ime'];
        $prezime = $red['prezime'];
        $email = $red['email'];
        $blokiran = $red['blokiran'];
        $slika = $red['slika'];

    }
}

// dohvaćanje tipova korisnika za punjenje selecta
$veza = spojiSeNaBazu();
$upit_tip_korisnika = "SELECT * FROM `tip_korisnika`";
$tipovi_korisnika = izvrsiUpit($veza, $upit_tip_korisnika);
zatvoriVezuNaBazu($veza);


?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Ažuriranje korisnika - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>

        <?php 
        include_once("navigacija.php");
        ?>

        <section id="main">
            <h1>Ažuriranje korisnika</h1>

            <img src="<?=$url?>" class="slika-azuriraj" />
            <form id=azuriraj-korisnika name=azuriraj-korisnika method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
                <label for="korisnik-tip">Tip korisnika:</label><br>
                
                <select name="korisnik-tip" autofocus required>
                <?php 
                    // treba napraviti upit kojim će se ispisati sve dostupne tipove korisnika u bazi

                    while ($red = mysqli_fetch_array($tipovi_korisnika)) {
                        $tip_korisnika_baza = $red['tip_korisnika_id'];
                        $naziv = $red['naziv'];
                        echo "<option value='{$tip_korisnika_baza}'";
                        if ($tip_korisnika_baza == $tip_korisnika) echo " selected";
                        echo ">{$naziv}</option>";
                    }
                    
                ?>
                
                </select>
                
                <br>
                <label for="kor-ime">Korisničko ime:</label><br>
                <input type="text" name="kor-ime" required value="<?=$kor_ime?>"><br>
                <label for="lozinka">Lozinka:</label><br>
                <input type="password" name="lozinka" required value="<?=$lozinka?>"><br>
                <label for="ime">Ime:</label><br>
                <input type="text" name="ime" value="<?=$ime?>" required><br>
                <label for="prezime">Prezime:</label><br>
                <input type="text" name="prezime" required value="<?=$prezime?>"><br>
                <label for="email">e-mail:</label><br>
                <input type="text" name="email" required value="<?=$email?>"><br>
                <label for="slika">Putanja slike:</label><br>
                <input type="text" name="slika" required value="<?=$slika?>"><br>
                <label for="status">Status korisnika:</label>
                <input type="checkbox" name="status" value=1 <?php if ($blokiran == 1) echo 'checked'; ?> >Blokiran
                <br><input type="hidden" name="id-korisnik" value="<?=$korisnik_id?>">

                <input type="submit" name="azuriraj-korisnika-submit" id="azuriraj-korisnika-submit" value="Ažuriraj korisnika">
                
            </form>
            
            <p class="greska"><?=$poruka?></p>

        </section>

        <?php include_once("podnozje.php");?>

    </body>
</html>