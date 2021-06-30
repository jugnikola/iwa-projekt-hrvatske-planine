<?php
session_start();
include_once("baza.php");

if ((isset($_SESSION['id_korisnik']) == false) || ($_SESSION['tip_korisnika'] > 1)) header("Location: index.php");

$ime_korisnika = $_SESSION["ime_korisnika"];
$id_moderatora = $_SESSION["id_korisnik"];

?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Popis planina - <?=$ime_korisnika?> - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <?php 
        include_once("navigacija.php");
        ?>
        <section id="main">
            <h1>Popis planina moderatora <?=$ime_korisnika?></h1>
            <?php
            $veza = spojiSeNaBazu();
                
            $upit = "SELECT moderator.*, planina.naziv FROM moderator INNER JOIN planina ON planina.planina_id = moderator.planina_id WHERE moderator.korisnik_id = '{$id_moderatora}'";
                
            $rezultat = izvrsiUpit($veza, $upit);    
            if ($rezultat->num_rows != 0) {
                    echo "
                    <table id='tablica-planine-moderatora'>
                        <thead>
                        </thead>               
                        <tbody>";
                } else {
                    echo "<p class='greska'>Moderator ne moderira niti jednu planinu.</p>";
                }          
                while ($red = mysqli_fetch_array($rezultat)){
                    $id_planine = $red['planina_id'];
                    $naziv_planine = $red['naziv'];
                    echo "<tr>\n";
                    echo "<td><a href='popis_slika_korisnika.php?planina={$id_planine}'>{$naziv_planine}</a></td>\n";
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