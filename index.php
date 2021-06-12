<?php

/* 

TODO 

Dodati neki sadržaj na početnu - index.php

DONE POPIS SLIKA KORISNIKA TESTIRATI, nije dobro baca error
kdunst velebit



footer da je na dnu ekrana ili na dnu sadržaja!

DIZAJN DONE
popis planina moderatora
popis planina (admin)
popis slika korisnika



DONE dodati value na filter galerije "Do" do sada (timestamp - now)




PRIKAZ GREŠKE UMJESTO PRAZNE TABLICE
sređeno na:
popis slika
popis slika korisnika
popis planina moderatora


popis blokiranih korisnika


DORADITI DIZAJN

dodavanje korisnika, 
dodavanje slika done

treba srediti i:

ažuriranje korisnika


-- planina --



-----


Hrvatske planine
Uloge: administrator, moderator, registrirani korisnik i anonimni/neregistrirani korisnici.

Sustav omogućuje kreiranje javne i privatne galerije slika hrvatskih planina. 
Sustav mora imati mogućnost prijave i odjave korisnika sa sustava. 
U sustavu postoji jedan ugrađeni administrator (korisničko ime: admin, lozinka: foi). 
Administrator je prijavljeni korisnik koji ima vrstu jednaku jedan. 
Sustav obavezno sadrži stranicu o_autoru.html (poveznica na stranicu mora biti u zaglavlju svake stranice) u kojoj se nalaze osobni podaci autora (svi podaci su obavezni): ime, prezime, broj indeksa, mail (obavezno FOI mail), centar, godina (akademska godina prvog upisa kolegija IWA) i slika JPG formata veličine 300x400px (npr. kao na osobnoj iskaznici ili indeksu).

Anonimni/neregistrirani korisnik može vidjeti galeriju javnih slika hrvatskih planina sortirano silazno po datumu i vremenu slikanja. 
Može filtrirati podatke na temelju naziva planine i/ili vremenskog razdoblja slikanja.
Vremensko razdoblje se definira datumom i vremenom od i do. 

Klikom na sliku dobivaju se detaljne informacije o slici sa informacijama o planini u koju slika pripada te imenom i prezimenom korisnika koji je postavio sliku. 

Korisnik može kliknuti na naziv planine te se vraća na galeriju slika i odmah vidi samo slike te planine. 
Korisnik može kliknuti na prezime korisnika i dobiva galeriju javnih slika tog korisnika te opet može kliknuti na sliku i doći do detaljnih informacija slike.

Registrirani korisnik uz svoje funkcionalnosti ima i sve funkcionalnosti kao i neprijavljeni korisnik. 
Korisnik može dodavati nove slike planina. Prilikom dodavanja bira planinu, unosi url do slike na webu, definira datum i vrijeme slikanja, daje naziv i opis slici, automatski se status slike postavlja na 1 (javna). 

Korisnik vidi popis svih svojih slika sa informacijom o statusu.
Korisnik može ažurirati podatke o slici pri čemu može promijeniti status slike (0 - privatna) ili (1 – javna).

Moderator uz svoje funkcionalnosti ima i sve funkcionalnosti kao i registrirani korisnik te dodano vidi popis svih planina za koje je zadužen. 
Odabirom planine može vidjeti popis svih javnih slika koje su dodane za tu planinu sa imenom i prezimenom osobe koja je tu sliku postavila. 
Klikom na prezime dobiva galeriju javnih slika odabranog korisnika. Ako želi može blokirati korisnika (blokiran=1) čime sve njegove slike postaju privatne i korisnik ne može dodavati nove slike dok ga administrator ne od blokira (blokiran=0).

Administrator uz svoje funkcionalnosti ima i sve funkcionalnosti kao i moderator. 
Unosi, ažurira i pregledava korisnike sustava te definira i ažurira njihove tipove. 

Unosi, pregledava i ažurira planine (npr. Dinara, Velebit, ...) te dodjeljuje moderatore za planinu. 

Jedna planina može imati jednog ili više moderatora, jedan moderator može biti zadužen za više planina. 

Administrator vidi popis blokiranih korisnika (blokiran=1) te ih može od blokirati (blokiran=0). 
Administrator vidi statistiku broja privatnih i javih slika po korisniku sortirano prezimenu korisnika.

Napomena: Svi datumi moraju se unositi od strane korisnika i prikazati korisniku u formatu „d.m.Y“, a vrijeme (00:00:00 – 23:59:59) u obliku „H:i:s“ (ne koristiti date i time HTML tip za input element). Format „d.m.Y” predstavlja kod PHP date funkciji i preslikava se na hrvatski format „dd.mm.gggg”. Format „H:i:s” predstavlja kod PHP date funkciji i preslikava se na hrvatski format „hh.mm.ss”. Poslužitelj se naziva localhost a baza podataka je iwa_2020_vz_projekt. Korisnik za pristup do baze podataka naziva se iwa_2020 a lozinka je foi2020. Kod izrade projektnog rješenja treba se točno držati uputa i NE SMIJE se mijenjati (naziv poslužitelja, baze podataka, struktura tablica, korisnik i lozinka). Završeno rješenje projektnog zadatka treba poslati kroz sustav za predaju rješenja nakon čega slijedi obavijest i dogovor o obrani projekta. Obrana projektnog rješenja se obavlja na računalu i bazi podataka nastavnika.

Projekti ne smiju sadržavati u programskom kodu komentare!




*/

session_start();

?>


<html lang="hr">
    <head>
        <title>Početna - Hrvatske planine</title>
        <meta charset="utf-8">
        <meta author="Nikola Jug">
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        
        <?php 
        include_once("navigacija.php");
        ?>

        <section id="main">
        <?php 
        
        /*
        if (isset($_SESSION["id_korisnik"])) {
        echo "<br>Prijavljeni korisnik: <br>" . $_SESSION['kor_ime'];
        }*/
        ?>

        <h2 style="text-align:center;">Dobrodošli na početnu stranicu Hrvatske Planine!</h2>
        <div class="pocetna-sadrzaj" 
        <?php
         if (!isset($_SESSION["tip_korisnika"])) {echo "style='grid-template-columns: 1fr; justify-items: center'>";}
         else if (isset($_SESSION["tip_korisnika"]) && ($_SESSION["tip_korisnika"] == 2)) {
            echo "style='grid-template-columns: auto; justify-items: center'>";
        } 
        else if (isset($_SESSION["tip_korisnika"]) && $_SESSION["tip_korisnika"] == 1) {echo "style='grid-template-columns: 1fr 1fr'>";} 
        
        else {
            echo ">";
        }?>
            <div class="pocetna-sadrzaj-stavka"  <?php if ( isset($_SESSION["tip_korisnika"]) && $_SESSION["tip_korisnika"] == 2 || isset($_SESSION["tip_korisnika"]) &&$_SESSION["tip_korisnika"] == 1) echo "style='margin: 0 auto'";?>>
                <a href="galerija.php">
                    <img src="slike/galerija-ikona.png" width="100px" class="pocetna-slika">
                    <br>
                    <button class="gumb-pocetna">Pregledaj galeriju javnih slika</button>
                </a>
            </div>

            <!-- moderator vidi: moderirane planine -->

<?php 

    if (isset($_SESSION["tip_korisnika"]) && $_SESSION["tip_korisnika"] == 1) {
        echo "
        <div class='pocetna-sadrzaj-stavka'>
                    <a href='popis_planina_moderatora.php'>
                        <img src='slike/planine-ikona.png' width='100px' class='pocetna-slika'>
                        <br>
                        <button class='gumb-pocetna'>Pregledaj moderirane planine</button>
                    </a>
                </div>
        
        ";
    } else if (isset($_SESSION["tip_korisnika"]) && $_SESSION["tip_korisnika"] == 0) {
        echo "
        <div class='pocetna-sadrzaj-stavka'>
                <a href='popis_planina.php'>
                    <img src='slike/planine-ikona.png' width='100px' class='pocetna-slika'>
                    <br>
                    <button class='gumb-pocetna'>Pregledaj planine</button>
                </a>
            </div>
            <div class='pocetna-sadrzaj-stavka'>
                <a href='popis_korisnika.php'>
                    <img src='slike/korisnici-ikona.png' width='100px' class='pocetna-slika'>
                    <br>
                    <button class='gumb-pocetna'>Pregledaj korisnike</button>
                </a>
            </div>
        ";
    }



?>            
    <!--        -->
        </div>
        </section>

        <?php
        include_once("podnozje.php");
        ?>

    </body>
</html>

