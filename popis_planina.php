<?php
session_start();
include_once("baza.php");

if ((isset($_SESSION['id_korisnik']) == false) || ($_SESSION['tip_korisnika'] > 0)) header("Location: index.php");

$ime_korisnika = $_SESSION["ime_korisnika"];
$id_admina = $_SESSION["id_korisnik"];

$veza = spojiSeNaBazu();

$upit_planine = "SELECT * FROM planina ORDER BY naziv ASC";
$rezultat_planine= izvrsiUpit($veza, $upit_planine);
zatvoriVezuNaBazu($veza);

?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Popis planina - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>

        <?php 
        include_once("navigacija.php");
        ?>

        <section id="main">
            <h1>Popis planina</h1>
            </form>
            <a href="dodaj_planinu.php"><button class="gumb" style="margin-bottom: 2em; margin-left: 45%;">Dodaj planinu</button></a>
            <table id="tablica-popis-planina">
                <thead>
                    <th>Naziv planine</th>
                    <th>Lokacija</th>
                </thead>       
                <tbody>
                <?php        
                while ($red = mysqli_fetch_array($rezultat_planine)){
                    $id_planine = $red['planina_id'];
                    $naziv = $red['naziv'];
                    $lokacija = $red['lokacija'];

                    echo "<tr>\n";
                    echo "<td><a href='popis_slika_korisnika.php?planina={$id_planine}'>{$naziv}</a></td>\n";
                    echo "<td>{$lokacija}</td>\n";
                    echo "<td><a href='azuriraj_planinu.php?id={$id_planine}'><button class='gumb'>Ažuriraj</button></a>";
                    echo "</tr>\n";
                }
                ?>
                </tbody>
            </table>
        </section>
        <?php include_once("podnozje.php");?>
    </body>
</html>