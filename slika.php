<?php

session_start();

include_once("baza.php");

$id = $_GET['id'];

$veza = spojiSeNaBazu();

// TRIPLE JOIN - slika, planina i korisnik

/*
opis - alt
naziv - title 

ime i prezime korisnika koji je postavio sliku

*/

/*

Array ( [0] => 7 
[slika_id] => 7 
[1] => 3 
[planina_id] => 3 
[2] => 3 
[korisnik_id] => 3 
[3] => Pero 
[ime] => Pero 
[4] => Kos 
[prezime] => Kos 
[5] => Medvednica 
[naziv] => Medvednica 
[6] => Medvednica ili Zagrebačka gora je planina sjeverno od Zagreba. Sljeme, njezin najviši vrh (1033 m), je popularno izletničko mjesto do kojeg se može doći cestom ili pješice, planinareći.Od 1963. do 2007. do Sljemena je vozila turistička žičara.[2] Bilo Medvednice dugo je 42 km, a proteže se u smjeru sjeveroistok - jugozapad. Površina planine je pošumljena. Godine 1981. zapadni dio Medvednice proglašen je parkom prirode. 
[opis] => Medvednica ili Zagrebačka gora je planina sjeverno od Zagreba. Sljeme, njezin najviši vrh (1033 m), je popularno izletničko mjesto do kojeg se može doći cestom ili pješice, planinareći.Od 1963. do 2007. do Sljemena je vozila turistička žičara.[2] Bilo Medvednice dugo je 42 km, a proteže se u smjeru sjeveroistok - jugozapad. Površina planine je pošumljena. Godine 1981. zapadni dio Medvednice proglašen je parkom prirode. 
[7] => Zagreb 
[lokacija] => Zagreb 
[8] => 45.91073831 
[geografska_sirina] => 45.91073831 
[9] => 15.94433172 
[geografska_duzina] => 15.94433172 )

*/


$upit = "SELECT slika.slika_id, slika.planina_id, slika.korisnik_id, slika.url, slika.opis AS opis_slike, korisnik.ime, korisnik.prezime, planina.naziv, planina.opis, planina.lokacija, planina.geografska_sirina, planina.geografska_duzina FROM slika
INNER JOIN planina ON planina.planina_id = slika.planina_id INNER JOIN korisnik ON korisnik.korisnik_id = slika.korisnik_id
WHERE slika.slika_id = '{$id}'

";

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

<?php 

?>
        <section id="main">
            <h1 class="naslov-planina"><?php echo $naziv_planine ?></h1>
        

            <img class="slika-planine" src="<?=$url?>" alt="<?=$opis_slike?>" title="<?=$opis_slike?>">


        <p>
        <!-- 
            echo $id . "<br>";
                        echo $naziv_planine . "<br>";
            echo $opis_planine . "<br>";
            echo $lokacija . "<br>";
            echo $sirina . "<br>";
            echo $duzina . "<br>";
            echo $opis_slike . "<br>";

            -->

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