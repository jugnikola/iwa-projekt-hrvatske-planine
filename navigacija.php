
<header>
    <div class="banner-tekst">
    Hrvatske planine
    </div>
</header>

<nav>
<ul>
<li class="navbar"><a class='navigacija' href="index.php">Početna</a></li>
<li class="navbar"><a class='navigacija' href="galerija.php">Galerija</a></li>
<li class="navbar"><a class='navigacija' href="o_autoru.php">O autoru</a></li>

<?php 
// autorizacija - ako je korisnik prijavljen piši "odjava"
if (isset($_SESSION["id_korisnik"])){
    echo "<li class='navbar'><a class='navigacija' href='dodaj_sliku.php'>Dodaj sliku</a></li>";
    echo "<li class='navbar'><a class='navigacija' href='popis_slika.php'>Popis slika</a></li>";
    echo "<li class='navbar' style='float:right'><a class='navigacija' href='prijava.php?odjava'>Odjava</a></li>";
} else {
    echo "<li class='navbar' style='float:right'><a class='navigacija' href='prijava.php'>Prijava</a></li>";
}
?>
</ul>

<?php ?>

<!-- <a href="#"></a> -->
</nav>