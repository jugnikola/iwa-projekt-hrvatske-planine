<?php
include_once("baza.php");
session_start();
$veza = spojiSeNaBazu();
?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Galerija slika - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <?php 
        include_once("navigacija.php");
        ?>
        <section id="main">
            <h1>Galerija slika</h1>
            <table style="margin: 0 auto">
                <tbody>
                    <tr>
                        <td><span >Filtriraj slike</span></td>
                        <td><span>Od:</span></td>
                        <td><span >Do:</span></td>
                    </tr>
                    <tr id="filter-tablica">
                        <form  method="GET" action="<?php echo $_SERVER['PHP_SELF']?>"></td>
                        <td><input class="galerija-filtracija" type="text" name="planina" placeholder="Naziv planine"></td>
                        <td><input class="galerija-filtracija" type="text" name="vrijeme_od_sort" value="01.10.2020 00:00:00"></td>
                        <td><input class="galerija-filtracija" type="text" name="vrijeme_do_sort" value="<?= date("d.m.Y H:i:s");?>"></td>
                        <td><input type="submit" name="filter" class="gumb" value="Filtriraj"></td>
                        </form>
                    </tr>
                </tbody>
            </table>
            <br>
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
                        $vrijeme_od = date('Y-m-d H:i:s', strtotime($_GET['vrijeme_od_sort']));
                        $vrijeme_do = date('Y-m-d H:i:s', strtotime($_GET['vrijeme_do_sort']));

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
                    echo "<div><a href='slika.php?id={$id}'><img class='slika-planine-grid' src='{$lokacija}'></a></div>";
                }
                zatvoriVezuNaBazu($veza);
            ?>
            </div>
        </section>
        <?php include_once("podnozje.php");?>
    </body>
</html>