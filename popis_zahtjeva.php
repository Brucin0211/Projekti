<table class="tablica tablica-zahtjevi">
	<caption>
        Popis zahtjeva
	</caption>
	<thead>
		<tr>
            <th>ID</th>
			<th>Dron</th>
			<th>Korisnik</th>
			<th>Datum dostave</th>
			<th>Datum zahtjeva</th>
			<th>Opis</th>
            <th>Napomene</th>
            <th>Polazište</th>
            <th>Odredište</th>
            <th>Kilometri</th>
            <th>Kilogrami</th>
            <th>Hitnost</th>
            <th>Ukupna cijena</th>
            <th>Status</th>
		</tr>
	</thead>
	<tbody>
    <?php
        $bazakonekcija = spojiSeNaBazu();
        global $aktivni_korisnik_id;

        $upitZahtjev = " SELECT do.dostava_id, d.naziv, k.ime, k.prezime, do.datum_vrijeme_dostave, do.datum_vrijeme_zahtjeva, do.opis_posiljke,
        do.napomene, do.adresa_dostave, do.adresa_polazista, do.dostavaKM, do.dostavaKG, do.hitnost, do.ukupna_cijena, do.status
        FROM dostava AS do, vrsta_drona AS vd, korisnik AS k, dron AS d
        WHERE k.vrsta_drona_id = vd.vrsta_drona_id
        AND k.korisnik_id = $aktivni_korisnik_id
        AND do.dron_id = d.dron_id
        AND do.dostavaKM BETWEEN vd.minKM AND vd.maxKM
        AND do.status = 0
        ORDER BY do.hitnost DESC";

        $rezultatupita = izvrsiUpit($bazakonekcija, $upitZahtjev);

        if ($rezultatupita->num_rows === 0) {
            echo "<tr class=\"prazna-tablica\">
                <td>
                    <div>Nemate nikakvih zahtjeva!</div>
                </td>
            </tr>";
        }

        while(list($dostavaId, $dron, $korisnikIme, $korisnikPrezime, $datumDostave, $datumZahtjeva,
        $opis, $napomene, $adresaDostave, $adresaPolazista, $dostavaKm, $dostavaKg, $hitnost, $ukupnaCijena, $status) = mysqli_fetch_array($rezultatupita)) {

            echo "
            <tr>
                <td>$dostavaId</td>
                <td>$dron</td>
                <td>$korisnikIme $korisnikPrezime</td>
                <td>".(isset($datumDostave) ? date('d.m.Y H:i:s',strtotime($datumDostave)) : '-')."</td>
                <td>".(isset($datumZahtjeva) ? date('d.m.Y H:i:s',strtotime($datumZahtjeva)) : '-')."</td>
                <td>$opis</td>
                <td>$napomene</td>
                <td>$adresaPolazista</td>
                <td>$adresaDostave</td>
                <td>$dostavaKm km</td>
                <td>$dostavaKg kg</td>
                <td>$hitnost</td>
                <td>$ukupnaCijena eura</td>
                <td>
                    $status
                    <a href=\"index.php?potvrdiDostavu&dostavaId=$dostavaId\">Potvrdi dostavu</a>
                </td>
            </tr>";

        }

        zatvoriVezuNaBazu($bazakonekcija);
        ?>
    </tbody>
</table>