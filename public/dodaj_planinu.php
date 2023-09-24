<?php
include_once("baza.php");
session_start();

$id_korisnik = $_SESSION['id_korisnik'];
if ((isset($_SESSION['id_korisnik']) == false) || ($_SESSION['tip_korisnika'] > 0)) header("Location: index.php");

$poruka="";

$veza = spojiSeNaBazu();

if (isset($_POST['dodaj-planinu-submit'])) {
    
    $naziv_unos = $_POST['naziv-planine'];
    $lokacija_unos = $_POST['lokacija'];
    $opis_unos = $_POST['opis'];
    $geo_sirina_unos = $_POST['geo-sirina'];
    $geo_duzina_unos = $_POST['geo-duzina'];
    
    $upit = "INSERT INTO `planina`(`naziv`, `opis`, `lokacija`, `geografska_sirina`, `geografska_duzina`) VALUES ('{$naziv_unos}','{$opis_unos}','{$lokacija_unos}','{$geo_sirina_unos}','{$geo_duzina_unos}')";
    $rezultat_unos = izvrsiUpit($veza, $upit);

    $id_nove_planine = mysqli_insert_id($veza);

    foreach($_POST['moderatori'] as $id_moderatora){
        $upit_dodaj_moderatore = "INSERT INTO `moderator`(`korisnik_id`, `planina_id`) VALUES ({$id_moderatora}, {$id_nove_planine})";
        izvrsiUpit($veza, $upit_dodaj_moderatore);
    }

    if ($rezultat_unos) {
        $poruka = "Planina uspješno unesena.";
        header("Location: azuriraj_planinu.php?id={$id_nove_planine}");
    } else {
        $poruka = "Ažuriranje nije uspjelo.";
    }
    zatvoriVezuNaBazu($veza);
} 

$upit_svi_moderatori = "SELECT DISTINCT korisnik_id, ime, prezime FROM `korisnik` 
WHERE tip_korisnika_id = 1";

$svi_moderatori = izvrsiUpit($veza, $upit_svi_moderatori);
zatvoriVezuNaBazu($veza);
?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Dodaj planinu - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <?php 
        include_once("navigacija.php");
        ?>
        <section id="main">
            <h1>Dodavanje planine</h1>
            <table class="tablica-dodaj">
                <tbody>
                <form id=dodaj-planinu name=dodaj-planinu method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
                    <tr>
                        <td><label for="naziv-planine">Naziv planine:</label></td>
                        <td><input type="text" name="naziv-planine" class="galerija-filtracija dodaj-azuriraj"></td>
                    </tr>
                    <tr>
                        <td><label for="opis">Opis:</label></td>
                        <td><textarea name="opis" cols=60 rows=10 required class="galerija-filtracija dodaj-azuriraj"></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="lokacija">Lokacija:</label></td>
                        <td><input type="text" name="lokacija" required class="galerija-filtracija dodaj-azuriraj"></td>
                    </tr>
                    <tr>
                        <td><label for="geo-sirina">Geografska širina:</label></td>
                        <td><input type="text" name="geo-sirina" required placeholder="45.00000" class="galerija-filtracija dodaj-azuriraj"></td>
                    </tr>
                    <tr>
                        <td><label for="geo-duzina">Geografska dužina:</label></td>
                        <td><input type="text" name="geo-duzina" placeholder="15.00000" required class="galerija-filtracija dodaj-azuriraj" ></td>
                    </tr>
                    <tr>
                        <td><label for="moderatori[]">Moderatori planine:</label></td>
                        <td>
                        <?php
                        while ($red = mysqli_fetch_array($svi_moderatori)) {
                            $moderator_id = $red['korisnik_id'];
                            $ime_prezime = $red['ime'] . " " . $red['prezime'];
                            
                            echo "<input type='checkbox' name='moderatori[]' style='margin-left: 10em' value='{$moderator_id}'>{$ime_prezime}<br>";
                        }
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="dodaj-planinu-submit" id="dodaj-planinu-submit" value="Dodaj planinu" class="gumb" style="margin-left: 10em; margin-top: 1em;"></td>
                    </tr>
                </form>
                </tbody>
            </table>
            <p class="greska"><?=$poruka?></p>
        </section>
        <?php include_once("podnozje.php");?>

    </body>
</html>