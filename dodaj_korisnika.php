<?php

// DODAJ KORISNIKA

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

// Ako je poslana forma za dodavanje, izvršava se upit za unos podataka isti kao i u dodaj_sliku.php

if (isset($_POST['unos-korisnika-submit'])) {
    $tip_korisnika_unos = $_POST["korisnik-tip"];
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
    $upit = "INSERT INTO `korisnik`(`tip_korisnika_id`, `korisnicko_ime`, `lozinka`, `ime`, `prezime`, `email`, `blokiran`, `slika`) VALUES ('{$tip_korisnika_unos}','{$kor_ime_unos}','{$lozinka_unos}','{$ime_unos}','{$prezime_unos}','{$email_unos}','{$status_unos}','{$slika_unos}')";
    
    $rezultat_unos = izvrsiUpit($veza, $upit);

    if ($rezultat_unos) {
        $poruka = "Korisnik uspješno unesen.";
        $korisnik_id_unos = mysqli_insert_id($veza);
        header("Location: azuriraj_korisnika.php?id={$korisnik_id_unos}?");
    } else {
        $poruka = "Unos nije uspio.";
    }

    zatvoriVezuNaBazu($veza);

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
        <title>Dodavanje korisnika - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>

        <?php 
        include_once("navigacija.php");
        ?>

        <section id="main">
            <h1>Dodavanje novog korisnika</h1>

            <table class="tablica-dodaj">
            <tbody>
            <form id=dodaj-korisnika name=dodaj-korisnika method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
            <tr>    
            <td><label for="korisnik-tip">Tip korisnika:</label></td>
                
                <td><select name="korisnik-tip" autofocus required class="galerija-filtracija dodaj-azuriraj">
                <?php 
                    // treba napraviti upit kojim će se ispisati sve dostupne tipove korisnika u bazi

                    while ($red = mysqli_fetch_array($tipovi_korisnika)) {
                        $tip_korisnika_baza = $red['tip_korisnika_id'];
                        $naziv = $red['naziv'];
                        echo "<option value='{$tip_korisnika_baza}'";
                        if ($tip_korisnika_baza == 2) echo " selected";
                        echo ">{$naziv}</option>";
                    }
                    
                ?>
                
                </select></td>
                </tr>
                <tr>
                <td><label for="kor-ime">Korisničko ime:</label></td>
                <td><input type="text" name="kor-ime" required value="" class="galerija-filtracija dodaj-azuriraj"></td>
                </tr>
                <tr>
                <td><label for="lozinka">Lozinka:</label></td>
                <td><input type="password" name="lozinka" required value="" class="galerija-filtracija dodaj-azuriraj"></td>
                </tr>
                <tr>
                <td><label for="ime">Ime:</label></td>
                <td><input type="text" name="ime" value="" required class="galerija-filtracija dodaj-azuriraj"></td>
                </tr>
                <tr>
                <td><label for="prezime">Prezime:</label></td>
                <td><input type="text" name="prezime" required value="" class="galerija-filtracija dodaj-azuriraj"></td>
                </tr>
                <tr>
                <td><label for="email">e-mail:</label></td>
                <td><input type="text" name="email" required value="" class="galerija-filtracija dodaj-azuriraj"></td>
                </tr>
                <tr>
                <td><label for="slika">Putanja slike:</label></td>
                <td><input type="text" name="slika" required value="" class="galerija-filtracija dodaj-azuriraj"></td>
                </tr>
                <tr>
                <td><label for="status">Status korisnika:</label></td>
                <td><input type="checkbox" name="status" value=1 style="margin-left: 10em;">Blokiran</td>
                </tr>
                
                <tr>
                <td></td>
                <td><input type="submit" name="unos-korisnika-submit" id="unos-korisnika-submit" value="Unesi korisnika" class="gumb" style="margin-left: 10em;margin-top: 1em;"></td>
                </tr>
                
            </form>
            </tbody>
        </table>
            
            <p class="greska"><?=$poruka?></p>

        </section>

        <?php include_once("podnozje.php");?>

    </body>
</html>