<?php
    $bazakonekcija = spojiSeNaBazu();

    $inicijalniDronId;
    $polaziste;
    $adresaDostave;
    $opis;
    $tezina;
    $kilometri;
    $hitnost;
    $napomena;
    $dostavaId;

    if (isset($_GET['dostavaId'])) {
        $dostavaId = $_GET['dostavaId'];

        $upitDostavaPoId = "SELECT * FROM dostava WHERE dostava_id = $dostavaId";

        $upitDostavaPoIdRezultat = mysqli_fetch_assoc(izvrsiUpit($bazakonekcija, $upitDostavaPoId));

        $inicijalniDronId = $upitDostavaPoIdRezultat['dron_id'];
        $polaziste = $upitDostavaPoIdRezultat['adresa_polazista'];
        $adresaDostave = $upitDostavaPoIdRezultat['adresa_dostave'];
        $opis = $upitDostavaPoIdRezultat['opis_posiljke'];
        $tezina = $upitDostavaPoIdRezultat['dostavaKG'];
        $kilometri = $upitDostavaPoIdRezultat['dostavaKM'];
        $hitnost = $upitDostavaPoIdRezultat['hitnost'];
        $napomena = $upitDostavaPoIdRezultat['napomene'];
    }

    if (isset($_POST['dostava'])) {
		$dron = $_POST['dron'];

        $upitCijenaPoKm = "SELECT v.cijenaPoKM FROM dron AS d JOIN vrsta_drona AS v ON d.vrsta_drona_id = v.vrsta_drona_id WHERE d.dron_id = $dron";
        $upitCijenaPoKmRezultat = mysqli_fetch_row(izvrsiUpit($bazakonekcija, $upitCijenaPoKm));

        $datumVrijemeDostave = DateTime::createFromFormat("d.m.Y H:i:s", $_POST['datum_vrijeme_dostave']);
        $formatiraniDatumVrijemeDostave = $datumVrijemeDostave->format("Y-m-d H:i:s");

        $ukupnaCijena = $upitCijenaPoKmRezultat[0] * $kilometri;

        $upitPotvrdiDostavu = "UPDATE dostava
        SET dron_id = $dron, datum_vrijeme_dostave = '$formatiraniDatumVrijemeDostave', ukupna_cijena = $ukupnaCijena, status = 1
        WHERE dostava_id = $dostavaId";

		izvrsiUpit($bazakonekcija, $upitPotvrdiDostavu);
        zatvoriVezuNaBazu($bazakonekcija);

        echo "<script>
            window.location.href = \"index.php?pocetna\"
        </script>";
	}
?>

<style>
    .forma{
        display: auto;
        flex-direction: column;
        padding-left: 16px;
        font-size: 16px;
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
        text-align: left;
    }
</style>

<h2 class="potvrdi-dostavu-forma-naslov">Potvrdi dostavu</h2>

<form method="POST" <?php echo "action=\"index.php?potvrdiDostavu&dostavaId=$dostavaId\""; ?> class="potvrdi-dostavu-forma">
    <label for="dron">Dron:</label>
    <select name="dron" required>
        <?php
            $upit = "SELECT d.dron_id, d.naziv FROM dron AS d
            JOIN vrsta_drona AS vd ON vd.vrsta_drona_id = d.vrsta_drona_id 
            JOIN korisnik AS k ON k.vrsta_drona_id = vd.vrsta_drona_id
            WHERE k.korisnik_id = $aktivni_korisnik_id
            GROUP BY d.dron_id";
            $dronoviKorisnika = izvrsiUpit($bazakonekcija, $upit);

            while(list($dron_id, $naziv) = mysqli_fetch_array($dronoviKorisnika)) {
                if ($dron_id == $inicijalniDronId) {
                    echo "<option selected=\"selected\" value=\"$dron_id\">$naziv - $dron_id</option>";
                } else {
                    echo "<option value=\"$dron_id\">$naziv - $dron_id</option>";
                }
            }

            zatvoriVezuNaBazu($bazakonekcija);
        ?>
    </select> <br>

    <label for="polaziste">Polazište (adresa):</label>
    <input type="text" name="polaziste" id="polaziste" value="<?php echo $polaziste; ?>" disabled><br>

    <label for="dostavna_adresa">Dostavna (adresa):</label>
    <input type="text" name="dostavna_adresa" id="dostavna" value="<?php echo $adresaDostave; ?>" disabled><br>

    <label for="opis">Opis pošiljke:</label>
    <textarea name="opis" id="opis" row=5 cols=30 disabled><?php echo $opis; ?></textarea><br>

    <label for="tezina">Težina (kg):</label>
    <input type="number" name="tezina" id="tezina" disabled value="<?php echo $tezina; ?>"><br>

    <label for="kilometri">Kilometri (km):</label>
    <input type="number" name="kilometri" id="kilometri" disabled value="<?php echo $kilometri; ?>"><br>

    <label for="hitnost">Hitnost dostave:</label>
    <select name="hitnost" disabled>
        <option value="1" <?php echo $hitnost == 1 ? "selected=\"selected\"" : "" ?>>Hitno</option>
        <option value="0" <?php echo $hitnost == 0 ? "selected=\"selected\"" : "" ?>>Nije hitno</option>
    </select><br>

    <label for="napomene">Napomena:</label>
    <textarea name="napomena" row=10 cols=30 disabled><?php echo $napomena; ?></textarea><br>

    <label for="datum_vrijeme_dostave">Datum i vrijeme dostave:</label>
    <input required placeholder="d.m.Y H:i:s" type="text" id="datum_vrijeme_dostave" name="datum_vrijeme_dostave">
    <br>

    <input type="submit" name="dostava" value="Potvrdi dostavu"><br>
</form> <br>

