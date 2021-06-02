<?php

/* 
	Poslužitelj se naziva localhost a baza podataka je iwa_2020_vz_projekt. Korisnik za pristup do baze podataka naziva se iwa_2020 a lozinka je foi2020. 
	Kod izrade projektnog rješenja treba se točno držati uputa i NE SMIJE se mijenjati (naziv poslužitelja, baze podataka, struktura tablica, korisnik i lozinka).
*/

// Konstante za spajanje na bazu podataka
define("POSLUZITELJ","localhost"); // adresa servera
define("BAZA","iwa_2020_vz_projekt"); // naziv sheme
define("BAZA_KORISNIK","iwa_2020"); // korisničko ime
define("BAZA_LOZINKA","foi2020"); // lozinka

function spojiSeNaBazu(){
	$veza = mysqli_connect(POSLUZITELJ,BAZA_KORISNIK,BAZA_LOZINKA);
	
	if(!$veza){
		echo "GREŠKA: 
		Problem sa spajanjem u datoteci baza.php funkcija otvoriVezu:  
		".mysqli_connect_error();
		return False;
	}
	
	mysqli_select_db($veza,BAZA); // odabir SHEME nad kojom ćemo izvoditi operacije
	
	if(mysqli_error($veza)!==""){
		echo "GREŠKA: 
		Problem sa odabirom baze u baza.php funkcija otvoriVezu:  
		".mysqli_error($veza);
		return False;
	}
	
	mysqli_set_charset($veza,"utf8");
	
	if(mysqli_error($veza)!==""){
		echo "GREŠKA: 
		Problem sa odabirom baze u baza.php funkcija otvoriVezu:  
		".mysqli_error($veza);
		
	}

	return $veza;
}

function izvrsiUpit($veza, $upit){
	
	$rezultat = mysqli_query($veza,$upit);
	
	if(mysqli_error($veza)!==""){
		echo "GREŠKA: 
		Problem sa upitom: ".$upit." : u datoteci baza.php funkcija izvrsiUput:  
		".mysqli_error($veza);
	}
	
	return $rezultat;
}

function zatvoriVezuNaBazu($veza){
	mysqli_close($veza);
}	

?>