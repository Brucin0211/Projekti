<?php
    $bazakonekcija = spojiSeNaBazu();

    echo "<a class=\"dodaj-korisnika-gumb\" href=\"index.php?korisnik\">Dodaj korisnika</a>";
?>

<table class="tablica tablica-korisnici">
	<caption>
        Korisnici sustava
	</caption>
	<thead>
		<tr>
			<th>ID</th>
			<th>Korisničko ime</th>
			<th>Ime i prezime</th>
			<th>Email</th>
			<th>Lozinka</th>
			<th>Tip korisnika</th>
			<th>Dodjeljena vrsta drona</th>
			<th>Akcije</th>
		</tr>
	</thead>
	<tbody>
        <?php

            $upit = "SELECT k.korisnik_id, k.tip_korisnika_id, t.naziv, k.vrsta_drona_id, v.naziv, k.korime, k.ime, k.prezime, k.email, k.lozinka
            FROM korisnik AS k 
            JOIN tip_korisnika AS t ON t.tip_korisnika_id = k.tip_korisnika_id 
            LEFT JOIN vrsta_drona AS v ON v.vrsta_drona_id = k.vrsta_drona_id
            ORDER BY k.korisnik_id
            ";

            $popisKorisnikaRezultat = izvrsiUpit($bazakonekcija, $upit);

            while(list($korisnikId, $tipKorisnikaId, $tipKorisnikaNaziv, $vrstaDronaId, $vrstaDronaNaziv,
             $korisnickoIme, $ime, $prezime, $email, $lozinka) = mysqli_fetch_array($popisKorisnikaRezultat)) {
                echo "<tr>
                    <td>$korisnikId</td>
                    <td>$korisnickoIme</td>
                    <td>$ime $prezime</td>
                    <td>$email</td>
                    <td>$lozinka</td>
                    <td>$tipKorisnikaId - $tipKorisnikaNaziv</td>
                    <td>$vrstaDronaId - $vrstaDronaNaziv</td>
                    <td>
                        <a href=\"index.php?korisnik&korisnikId=$korisnikId\">Uredi korisnika</a>
                    </td>
                </tr>";    
            }

            zatvoriVezuNaBazu($bazakonekcija);

        ?>
	</tbody>
</table>