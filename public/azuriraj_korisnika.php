<?php
include_once("baza.php");
session_start();

$id_korisnik = $_SESSION['id_korisnik'];

if ((isset($_SESSION['id_korisnik']) == false) || ($_SESSION['tip_korisnika'] > 0)) header("Location: index.php");

$poruka="";

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
            <table class="tablica-dodaj">
                <tbody>
                    <tr>
                        <td></td>
                        <td><img src="<?=$url?>" class="slika-azuriraj" alt="Slika korisnika"/></td>
                    </tr>
                        <form id=azuriraj-korisnika name=azuriraj-korisnika method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
                    <tr>
                        <td><label for="korisnik-tip">Tip korisnika:</label></td>
                        <td><select name="korisnik-tip" autofocus required class="galerija-filtracija dodaj-azuriraj">
                    <?php 
                        while ($red = mysqli_fetch_array($tipovi_korisnika)) {
                            $tip_korisnika_baza = $red['tip_korisnika_id'];
                            $naziv = $red['naziv'];
                            echo "<option value='{$tip_korisnika_baza}'";
                            if ($tip_korisnika_baza == $tip_korisnika) echo " selected";
                            echo ">{$naziv}</option>";
                        }
                    ?>
                    </select></td>
                    </tr>
                    <tr>
                        <td><label for="kor-ime">Korisničko ime:</label></td>
                        <td><input type="text" name="kor-ime" required class="galerija-filtracija dodaj-azuriraj" value="<?=$kor_ime?>"></td>
                    </tr>
                    <tr>
                        <td><label for="lozinka">Lozinka:</label></td>
                        <td><input type="password" name="lozinka" class="galerija-filtracija dodaj-azuriraj" required value="<?=$lozinka?>"></td>
                    </tr>
                    <tr>
                        <td><label for="ime">Ime:</label></td>
                        <td><input type="text" name="ime" class="galerija-filtracija dodaj-azuriraj" value="<?=$ime?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="prezime">Prezime:</label></td>
                        <td><input type="text" name="prezime" class="galerija-filtracija dodaj-azuriraj" required value="<?=$prezime?>"></td>
                    </tr>
                    <tr>
                        <td><label for="email">e-mail:</label></td>
                        <td><input type="text" name="email" class="galerija-filtracija dodaj-azuriraj" required value="<?=$email?>"></td>
                    </tr>
                    <tr>
                        <td><label for="slika">Putanja slike:</label></td>
                        <td><input type="text" name="slika" class="galerija-filtracija dodaj-azuriraj" required value="<?=$slika?>"></td>
                    </tr>
                    <tr> 
                        <td><label for="status">Status korisnika:</label></td>
                        <td><input type="checkbox" name="status" style="margin-left: 10em" value=1 <?php if ($blokiran == 1) echo 'checked'; ?> >Blokiran<input type="hidden" name="id-korisnik" value="<?=$korisnik_id?>"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="azuriraj-korisnika-submit" style="margin-left: 10em" id="azuriraj-korisnika-submit" class="gumb" value="Ažuriraj korisnika"></td>
                    </tr>
                        </form>
                </tbody>
            </table>
            <p class="greska"><?=$poruka?></p>
        </section>
        <?php include_once("podnozje.php");?>
    </body>
</html>