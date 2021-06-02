<?php
include_once("baza.php");

$veza = spojiSeNaBazu();



?>

<html lang="hr">
    <head>
        <title>Hrvatske planine - Galerija slika</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <header>
            
        </header>
        <?php 
        include_once("navigacija.php");
        ?>

        <section id="main">
            <h1>Galerija</h1>
<!-- 
    Može filtrirati podatke na temelju naziva planine i/ili vremenskog razdoblja slikanja.
    Vremensko razdoblje se definira datumom i vremenom od i do. 
    
Klikom na sliku dobivaju se detaljne informacije o slici sa informacijama o planini u koju slika pripada te imenom i prezimenom korisnika koji je postavio sliku. 

Korisnik može kliknuti na naziv planine te se vraća na galeriju slika i odmah vidi samo slike te planine. 

Korisnik može kliknuti na prezime korisnika i dobiva galeriju javnih slika tog korisnika te opet može kliknuti na sliku i doći do detaljnih informacija slike.

Svi datumi moraju se unositi od strane korisnika i prikazati korisniku u formatu „d.m.Y“, a vrijeme (00:00:00 – 23:59:59) u obliku „H:i:s“ 
(ne koristiti date i time HTML tip za input element). Format „d.m.Y” predstavlja kod PHP date funkciji i preslikava se na hrvatski format „dd.mm.gggg”. 
Format „H:i:s” predstavlja kod PHP date funkciji i preslikava se na hrvatski format „hh.mm.ss”. 

-->
            <form id="galerija-filtracija" method="GET" action="<?php echo $_SERVER['PHP_SELF']?>">
            <input type="text" name="planina">
            <input type="text" name="vrijeme_od_sort" value="01.10.2020 00:00:00">
            <input type="text" name="vrijeme_do_sort" value="01.11.2021 00:00:00">
            <input type="submit" name="filter" value="Filtriraj">
            </form>
        
        <div class="container">
        
           <?php
            
            if (isset($_GET['korisnik'])){
                $id_korisnik = $_GET['korisnik'];
                $upit="SELECT slika.url, slika.slika_id FROM slika 
                INNER JOIN planina ON planina.planina_id = slika.planina_id
                WHERE status=1 AND slika.korisnik_id = '{$id_korisnik}'";
                $rezultat = izvrsiUpit($veza, $upit);
            
            } else if (isset($_GET['planina'])){
                $naziv = $_GET['planina'];
                if (isset($_GET['filter'])) {
                    $vrijeme_od = date('Y-m-d h:i:s', strtotime($_GET['vrijeme_od_sort']));
                    // $vrijeme_od = "01.10.2020 10:00:00";
                    $vrijeme_do = date('Y-m-d h:i:s', strtotime($_GET['vrijeme_do_sort']));
    
                    // echo date('Y-m-d h:i:s', strtotime($vrijeme_od));
    
                    $upit="SELECT slika.url, slika.slika_id FROM slika 
                    INNER JOIN planina ON planina.planina_id = slika.planina_id
                    WHERE status=1 AND planina.naziv LIKE '%{$naziv}%' AND slika.datum_vrijeme_slikanja BETWEEN '{$vrijeme_od}' AND '{$vrijeme_do}'";
                    $rezultat = izvrsiUpit($veza, $upit);
                    
                } else {
                    $upit="SELECT slika.url, slika.slika_id FROM slika 
                    INNER JOIN planina ON planina.planina_id = slika.planina_id
                    WHERE status=1 AND planina.planina_id = '{$naziv}'";

                    $rezultat = izvrsiUpit($veza, $upit);
                              
                }
            } else {
                $upit="SELECT url, slika_id FROM slika WHERE status=1 ORDER BY datum_vrijeme_slikanja DESC";
                $rezultat = izvrsiUpit($veza, $upit);
            }
            


            while ($red = mysqli_fetch_array($rezultat)) {
                $lokacija = $red['url'];
                $id = $red['slika_id'];
                echo "<div><a href='planina.php?id={$id}'><img class='slika-planine-grid' src='{$lokacija}'></a></div>";
            }
            zatvoriVezuNaBazu($veza);

           ?>
           


        </div>        
        </section>
        <?php include_once("podnozje.php");?>
    </body>
</html>