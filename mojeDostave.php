<?php
    $bazakonekcija = spojiSeNaBazu();

    if (isset($_GET['dostavaIzvrsena'])) {
        $izvrsenaDostavaId = $_GET['dostavaIzvrsena'];

        $upitIzvrsiDostavu = "UPDATE dostava SET status = 2 WHERE dostava_id = $izvrsenaDostavaId";

        izvrsiUpit($bazakonekcija, $upitIzvrsiDostavu);

        echo "<script>
            window.location.href = \"index.php?mojeDostave\"
        </script>";
    }
?>

<form class="filtriraj-dostave" method="post" action="index.php?mojeDostave">
  <label for="pocetni_datum">Početni datum:</label>
  <input placeholder="d.m.y H:i:s" type="text" id="pocetni_datum" name="pocetni_datum" required>
  <br>
  <label for="zavrsni_datum">Završni datum:</label>
  <input required placeholder="d.m.y H:i:s" type="text" id="zavrsni_datum" name="zavrsni_datum">
  <br>
  <input type="submit" name="filtriraj_dostave" value="Filtriraj">
</form>

<table class="tablica tablica-mojeDostave">
	<caption>
        Moje dostave
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
            $upit = "SELECT do.dostava_id, d.naziv, k.ime, k.prezime, do.datum_vrijeme_dostave, do.datum_vrijeme_zahtjeva,
            do.opis_posiljke, do.napomene, do.adresa_dostave, do.adresa_polazista, do.dostavaKM, do.dostavaKG, do.hitnost, do.ukupna_cijena, do.status
            FROM dostava AS do JOIN dron AS d ON do.dron_id = d.dron_id JOIN korisnik AS k ON do.korisnik_id = k.korisnik_id";

            if (isset($_POST['filtriraj_dostave'])) {
                $pocetniDatum = DateTime::createFromFormat("d.m.Y H:i:s", $_POST['pocetni_datum']);
                $formatiraniPocetniDatum = $pocetniDatum->format("Y-m-d H:i:s");
                
                $zavrsniDatum = DateTime::createFromFormat("d.m.Y H:i:s", $_POST['zavrsni_datum']);
                $formatiraniZavrsniDatum = $zavrsniDatum->format("Y-m-d H:i:s");
                
                $upit .= " WHERE do.korisnik_id = $aktivni_korisnik_id AND do.datum_vrijeme_zahtjeva BETWEEN '$formatiraniPocetniDatum' AND '$formatiraniZavrsniDatum' GROUP BY do.dostava_id";
            } else {
                $upit .= " WHERE do.korisnik_id = $aktivni_korisnik_id GROUP BY do.dostava_id";
            }

            

            $mojeDostave = izvrsiUpit($bazakonekcija, $upit);

            if ($mojeDostave->num_rows === 0) {
                echo "<tr class=\"prazna-tablica\">
                    <td>
                        <div>Nemate nikakvih dostava!</div>
                    </td>
                </tr>";
            }

            while(list($dostavaId, $dron, $korisnikIme, $korisnikPrezime, $datumDostave, $datumZahtjeva, 
                $opis, $napomene, $adresaDostave, $adresaPolazista, $dostavaKm, $dostavaKg, $hitnost, $ukupnaCijena, $status)=mysqli_fetch_array($mojeDostave)) {

                echo "
                    <tr>
                    <td>$dostavaId</td>
                    <td>$dron</td>
                    <td>$korisnikIme $korisnikPrezime</td>
                    <td>".(isset($datumDostave) ? date('d.m.Y H:i:s',strtotime($datumDostave)) : '-')."</td>
                    <td>".(isset($datumZahtjeva) ? date('d.m.Y H:i:s',strtotime($datumZahtjeva)) : '-')."</td>
                    <td>$opis</td>
                    <td>$napomene</td>
                    <td>$adresaDostave</td>
                    <td>$adresaPolazista</td>
                    <td>$dostavaKm km</td>
                    <td>$dostavaKg kg</td>
                    <td>$hitnost</td>
                    <td>$ukupnaCijena eura</td>
                    <td>
                        $status
                ";
                
                if (isset($datumDostave) && (date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($datumDostave))) && $status != 2) {
                    echo "<a href=\"index.php?mojeDostave&dostavaIzvrsena=$dostavaId\">Oznaci izvrsenim</a>";
                }

                echo "</td>
                </tr>";

            }

            zatvoriVezuNaBazu($bazakonekcija);

        ?>
	</tbody>
</table>
