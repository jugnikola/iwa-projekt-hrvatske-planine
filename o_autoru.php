<html lang="hr">
    <head>
        <title>Hrvatske planine - O autoru</title>
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
            <h1>O autoru</h1>
        <img id="img-autor" src="slike/autor.jpg" width="300px" height="400px" alt="Slika autora projekta">
        <p>
        <!-- ime, prezime, broj indeksa, mail (obavezno FOI mail), centar, godina (akademska godina prvog upisa kolegija IWA) -->
        <table id="tab-autor">
        <caption>Podaci o autoru</caption>
        <tr>
            <td>Ime:</td>
            <td>Nikola</td>
        </tr>
        <tr>
            <td>Prezime:</td>
            <td>Jug</td>
        </tr>
        <tr>
            <td>Broj indeksa (JMBAG):</td>
            <td>0016139883</td>
        </tr>
        <tr>
            <td>E-mail:</td>
            <td><a href="mailto:njug@foi.hr">njug@foi.hr</a></td>
        </tr>
        <tr>
            <td>Centar:</td>
            <td>Varaždin</td>
        </tr>
        <tr>
            <td>Godina (ak. godina prvog upisa):</td>
            <td>2020./2021.</td>
        </tr>
        </table>
        </p>
        </section>

        <?php include_once("podnozje.php");?>

    </body>
</html>