<?php
    $bazakonekcija = spojiSeNaBazu();
    $minKm = 0;
    $maxKm = 0;
    $selektiraniDron = isset($_GET['dronId']) ? $_GET['dronId'] : null;

    if (isset($_POST['dostava'])) {
		$dron=$_POST['dronId'];

        $upitCijenaPoKm = "SELECT v.cijenaPoKM FROM dron AS d JOIN vrsta_drona AS v ON d.vrsta_drona_id = v.vrsta_drona_id WHERE d.dron_id = $dron";

        $upitCijenaPoKmRezultat = mysqli_fetch_row(izvrsiUpit($bazakonekcija, $upitCijenaPoKm));

		$polaziste=$_POST['polaziste'];
		$dostavna_adresa=$_POST['dostavna_adresa'];
		$opis=$_POST['opis'];
		$tezina=$_POST['tezina'];
		$kilometri=$_POST['kilometri'];
		$hitnost=$_POST['hitnost'];
		$napomena=$_POST['napomena'];
        $datum = date('Y-m-d H:i:s');
        $ukupnaCijena = $upitCijenaPoKmRezultat[0] * $kilometri;

		$upitDostava = "INSERT INTO dostava
			(dron_id,korisnik_id,datum_vrijeme_zahtjeva, opis_posiljke, napomene, adresa_dostave, adresa_polazista, dostavaKM, dostavaKG, hitnost, ukupna_cijena, status) VALUES
			($dron, $aktivni_korisnik_id, '$datum', '$opis', '$napomena', '$dostavna_adresa', '$polaziste', $kilometri, $tezina, $hitnost, $ukupnaCijena, 0);
		";

		izvrsiUpit($bazakonekcija, $upitDostava);
        zatvoriVezuNaBazu($bazakonekcija);

         echo "<script>
             window.location.href = \"index.php?mojeDostave\"
         </script>";
	}
?>

<script>
    function osvjeziStranicu(selektiranaVrijednost) {
        window.location.href = "index.php?dostava&dronId=" + selektiranaVrijednost;
    }
</script>

<h2 class="dostava-forma-naslov">Zahtjev za dostavom</h2>

<form method="POST" action="index.php?dostava" class="dostava-forma">
    <label for="dronId">Dron:</label>
    <select name="dronId" onchange="osvjeziStranicu(value)" required>
        <?php
            $upit = "SELECT d.dron_id, d.naziv, vd.minKM, vd.maxKM FROM dron AS d JOIN vrsta_drona AS vd ON vd.vrsta_drona_id = d.vrsta_drona_id";
            $dronovi = izvrsiUpit($bazakonekcija, $upit);

            while(list($dron_id, $naziv, $minKmDrona, $maxKmDrona) = mysqli_fetch_array($dronovi)) {
                if (isset($selektiraniDron) && $dron_id == $selektiraniDron) {
                    $minKm = $minKmDrona;
                    $maxKm = $maxKmDrona;

                    echo "<option selected=\"selected\" value=\"$dron_id\">$naziv - $dron_id</option>";
                } else if (!isset($selektiraniDron)) {
                    $selektiraniDron = $dron_id;
                    $minKm = $minKmDrona;
                    $maxKm = $maxKmDrona;

                    echo "<option selected=\"selected\" value=\"$dron_id\">$naziv - $dron_id</option>";
                } else {
                    echo "<option value=\"$dron_id\">$naziv - $dron_id</option>";
                }
            }

            zatvoriVezuNaBazu($bazakonekcija);
        ?>
    </select><br>

    <label for="polaziste">Polazište (adresa):</label>
    <input type="text" name="polaziste" id="polaziste" required><br>

    <label for="dostavna_adresa">Dostavna (adresa):</label>
    <input type="text" name="dostavna_adresa" id="dostavna" required><br>

    <label for="opis">Opis pošiljke:</label>
    <textarea name="opis" id="opis" row=5 cols=30 required>Opis pošiljke</textarea><br>

    <label for="tezina">Težina (kg):</label>
    <input type="number" name="tezina" id="tezina" required><br>

    <label for="kilometri">Kilometri (km):</label>
    <?php
        echo "<input type=\"number\" name=\"kilometri\" id=\"kilometri\" min=\"$minKm\" max=\"$maxKm\" required>"
    ?><br>
    

    <label for="hitnost">Hitnost dostave:</label>
    <select name="hitnost" required>
        <option value="1">Hitno</option>
        <option value="0">Nije hitno</option>
    </select><br>

    <label for="napomene">Napomena:</label>
    <textarea style="resize: none;" name="napomena" rows=10 cols=30 >Napišite napomenu </textarea><br>

    <input type="submit" name="dostava" value="Zatraži dostavu">
</form>

    