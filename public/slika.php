<?php
session_start();
include_once("baza.php");

$id = $_GET['id'];

$veza = spojiSeNaBazu();
$upit = "SELECT slika.slika_id, slika.planina_id, slika.korisnik_id, slika.url, slika.opis AS opis_slike, korisnik.ime, korisnik.prezime, planina.naziv, planina.opis, planina.lokacija, planina.geografska_sirina, planina.geografska_duzina FROM slika
INNER JOIN planina ON planina.planina_id = slika.planina_id INNER JOIN korisnik ON korisnik.korisnik_id = slika.korisnik_id
WHERE slika.slika_id = '{$id}'";

$rezultat = izvrsiUpit($veza, $upit);
while ($red = mysqli_fetch_array($rezultat)) {
    $id=$red['slika_id'];
    $id_planine = $red['planina_id'];
    $id_korisnik = $red['korisnik_id'];
    $ime = $red['ime'];
    $prezime = $red['prezime'];
    $naziv_planine = $red['naziv'];
    $opis_planine = $red['opis'];
    $lokacija = $red['lokacija'];
    $sirina = $red['geografska_sirina'];
    $duzina = $red['geografska_duzina'];
    $url = $red['url'];
    $opis_slike = $red['opis_slike'];
}
zatvoriVezuNaBazu($veza);
?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title><?php echo $naziv_planine ?> - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <?php 
        include_once("navigacija.php");
        ?>
        <section id="main">
            <h1 class="naslov-planina"><?php echo $naziv_planine ?></h1>
            <img class="slika-planine" src="<?=$url?>" alt="<?=$opis_slike?>" title="<?=$opis_slike?>">
        <p>
        <table class="tablica-opis-slike">
            <tbody>
            <tr>
                <td style="width: 25%" >Postavio korisnik:</td>
                <td><?=$ime . " " . "<a href='galerija.php?korisnik={$id_korisnik}'>" . $prezime . "</a>"?></td>
            </tr>
            <tr>    
                <td>Planina:</td>
                <td><a href="galerija.php?planina=<?=$id_planine?>"><?=$naziv_planine?></a></td>
            </tr>
            <tr>
                <td>Opis slike:</td>
                <td><?=$opis_slike?></td>
            </tr>
            <tr>
                <td id="opis-planine" >Opis planine:</td>
                <td><?=$opis_planine?></td>
            </tr>
            <tr>
                <td>Lokacija:</td>
                <td><?=$lokacija?></td>
            </tr>
            <tr>
                <td>Geografska širina i dužina:</td>
                <td><?=$sirina . ", " . $duzina?></td>
            </tr>
            </tbody>
        </table>
        </p>
        </section>
        <?php include_once("podnozje.php");?>
    </body>
</html>