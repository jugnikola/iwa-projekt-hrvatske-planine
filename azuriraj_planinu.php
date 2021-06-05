<?php

// AŽURIRAJ KORISNIKA

// TODO : DODATI DA SE AŽURIRA I MODERATOR TABLICA

include_once("baza.php");
// spajamo se na bazu
/* pokrećemo sesiju funkciom session_start() */
session_start();
$veza = spojiSeNaBazu();

$id_korisnik = $_SESSION['id_korisnik'];

// Preusmjeravanje ako korisnik nije moderator ili admin
if ((isset($_SESSION['id_korisnik']) == false) || ($_SESSION['tip_korisnika'] > 0)) header("Location: index.php");

$poruka="";

if (isset($_GET['id'])) {
    $id_planine = $_GET['id'];
} else {
    $id_planine = $_POST['id-planina'];
}


if (isset($_POST['azuriraj-planinu-submit'])) {
    
    $naziv_unos = $_POST['naziv-planine'];
    $lokacija_unos = $_POST['lokacija'];
    $opis_unos = $_POST['opis'];
    $geo_sirina_unos = $_POST['geo-sirina'];
    $geo_duzina_unos = $_POST['geo-duzina'];

   
// izbrisati zapi su tablici moderator pod tim ID-om
$upit_brisi_moderatore = "DELETE FROM moderator WHERE planina_id = {$id_planine}";
izvrsiUpit($veza, $upit_brisi_moderatore);


// za svakog moderatora iz arraya moderatori
    foreach($_POST['moderatori'] as $id_moderatora){
        
        // i zatim dodati zapis za tog moderatora i tu planinu
        $upit_dodaj_moderatore = "INSERT INTO `moderator`(`korisnik_id`, `planina_id`) VALUES ({$id_moderatora}, {$id_planine})";
        izvrsiUpit($veza, $upit_dodaj_moderatore);

    }


 

    $upit_azuriraj = "UPDATE `planina` SET `naziv`='{$naziv_unos}',`opis`='{$opis_unos}',`lokacija`='{$lokacija_unos}',`geografska_sirina`='{$geo_sirina_unos}',`geografska_duzina`='{$geo_duzina_unos}' WHERE planina_id = {$id_planine}";

    $rezultat_azuriraj_planinu = izvrsiUpit($veza, $upit_azuriraj);

    if ($rezultat_azuriraj_planinu) {
        $poruka = "Planina uspješno ažurirana.";
        header("Location: azuriraj_planinu.php?id={$_POST['id-planina']}");
    } else {
        $poruka = "Ažuriranje nije uspjelo.";
    }
}


if (isset($_GET['id'])) {    

    $upit = "SELECT * FROM planina WHERE planina_id = '{$id_planine}'";
    $rezultat = izvrsiUpit($veza, $upit);


    while ($red = mysqli_fetch_array($rezultat)){
        $planina_id = $red['planina_id'];
        $naziv = $red['naziv'];
        $opis = $red['opis'];
        $lokacija = $red['lokacija'];
        $geo_sirina = $red['geografska_sirina'];
        $geo_duzina = $red['geografska_duzina'];

    }
}

// dohvaćanje moderatora za checkboxeve

$upit_svi_moderatori = "SELECT DISTINCT korisnik_id, ime, prezime FROM `korisnik` 
WHERE tip_korisnika_id = 1";

// // izbrisati zapi su tablici moderator pod tim ID-om
// $upit_brisi_moderatore = "DELETE FROM moderator WHERE korisnik_id = {$id_moderatora}";
// izvrsiUpit($veza, $upit_brisi_moderatore);

// WHERE moderator.planina_id = '{$planina_id}'";

$svi_moderatori = izvrsiUpit($veza, $upit_svi_moderatori);

// upit koji dohvaća sve redove di je ta planina neki id




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
            <h1>Ažuriranje planine</h1>

            <!-- <img src="<?=$url?>" class="slika-azuriraj" /> -->
            <form id=azuriraj-planinu name=azuriraj-planinu method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
                <label for="naziv-planine">Naziv planine:</label><br>
                <input type="text" name="naziv-planine" value="<?=$naziv?>"></br>
                <label for="opis">Opis:</label><br>
                <textarea name="opis" cols=60 rows=10 required><?=$opis?></textarea><br>
                <label for="lokacija">Lokacija:</label><br>
                <input type="text" name="lokacija" required value="<?=$lokacija?>"><br>
                <label for="geo-sirina">Geografska širina:</label><br>
                <input type="text" name="geo-sirina" value="<?=$geo_sirina?>" required><br>
                <label for="geo-duzina">Geografska dužina:</label><br>
                <input type="text" name="geo-duzina" required value="<?=$geo_duzina?>"><br>
                <label for="moderatori[]">Moderatori planine:</label><br>
                <?php
                while ($red = mysqli_fetch_array($svi_moderatori)) {
                    $moderator_id = $red['korisnik_id'];
                    $ime_prezime = $red['ime'] . " " . $red['prezime'];
                    
                    $upit_moderatori_planine = "SELECT planina_id FROM moderator WHERE korisnik_id = '{$moderator_id}'";
                    $rezultat_moderator_planine = izvrsiUpit($veza, $upit_moderatori_planine);
                    
                        //var_dump($moderatori_planine);

                    echo "<input type='checkbox' name='moderatori[]' value='{$moderator_id}'";
                    while ($moderatori_planine = mysqli_fetch_array($rezultat_moderator_planine)) {
                            if ($planina_id == $moderatori_planine['planina_id']) {
                                echo "checked";
                            }
                        }
                    echo ">{$ime_prezime}<br>";
                     
                        
                    }
                ?>
                
                <br><input type="hidden" name="id-planina" value="<?=$id_planine?>">

                <input type="submit" name="azuriraj-planinu-submit" id="azuriraj-planinu-submit" value="Ažuriraj planinu">
                
            </form>
            
            <p class="greska"><?=$poruka?></p>

        </section>

        <?php include_once("podnozje.php");
        zatvoriVezuNaBazu($veza);
        ?>


    </body>
</html>