<?php 
    $bazakonekcija = spojiSeNaBazu();
    $zaduzenaVrsta = null;
    $dronId = null;
    $nazivDrona = "";
    $vrstaDrona = null;
    $slika = "";

    if (isset($_GET["dronId"])) {
        $dronId = $_GET["dronId"];

        $sqlDronPodaci = "SELECT * FROM dron WHERE dron_id = $dronId";
        $dronRezultat = izvrsiUpit($bazakonekcija, $sqlDronPodaci);
        $dronRezultatAsoc = mysqli_fetch_assoc($dronRezultat);
        $nazivDrona = $dronRezultatAsoc['naziv'];
        $vrstaDrona = $dronRezultatAsoc['vrsta_drona_id'];
        $slika = $dronRezultatAsoc['poveznica_slika'];
    }

    if ($aktivni_korisnik_tip == "moderator") {   
        $upitZaduzeneVrste = "SELECT vrsta_drona_id FROM korisnik WHERE korisnik_id = $aktivni_korisnik_id";
        $zaduzenaVrstaRezultat = izvrsiUpit($bazakonekcija, $upitZaduzeneVrste);

        $zaduzenaVrsta = mysqli_fetch_assoc($zaduzenaVrstaRezultat)['vrsta_drona_id'];
    }

    if (isset($_POST['dron'])) {
        $nazivDrona = $_POST['naziv_drona'];
        $slika = $_POST['slika'];
        $odabranaVrstaDrona = isset($_POST['vrsta_drona']) ? $_POST['vrsta_drona'] : $zaduzenaVrsta;
        $sql;

        if (isset($dronId)) {
            $sql = "UPDATE dron SET naziv = '$nazivDrona', poveznica_slika = '$slika', vrsta_drona_id = $odabranaVrstaDrona WHERE dron_id = $dronId";
        } else {        
            $sql = "INSERT INTO dron (vrsta_drona_id, poveznica_slika, naziv) VALUES ($odabranaVrstaDrona, '$slika', '$nazivDrona')";
        }

        izvrsiUpit($bazakonekcija, $sql);
        zatvoriVezuNaBazu($bazakonekcija);

        echo "<script>
                window.location.href = \"index.php?dronovi\"
        </script>";
    }
?>

<h2 class="dron-forma-naslov">
    <?php
        if (isset($dronId)) {
            echo "Uredi dron";
        } else {
            echo "Dodaj dron";
        }
    ?>
</h2>

<form method="POST" <?php echo (isset($dronId) ? "action=\"index.php?dron&dronId=$dronId\"" : "action=\"index.php?dron\"");?> class="dron-forma">
    <label for="vrsta_drona">Vrsta drona:</label>
    <select name="vrsta_drona" <?php echo ($aktivni_korisnik_tip == "admin" ? '' : 'disabled');?>>
        <?php
            $upit = "SELECT vrsta_drona_id, naziv FROM vrsta_drona";
            $vrsteDrona = izvrsiUpit($bazakonekcija, $upit);

            while(list($vrsta_drona_id, $naziv) = mysqli_fetch_array($vrsteDrona)) {
                if (isset($zaduzenaVrsta) && $vrsta_drona_id == $zaduzenaVrsta) {
                    echo "<option selected=\"selected\" value=\"$vrsta_drona_id\">$naziv</option>";
                } else if (!isset($zaduzenaVrsta) && $vrsta_drona_id == $vrstaDrona) {
                    $zaduzenaVrsta = $vrstaDrona;
                    echo "<option selected=\"selected\" value=\"$vrsta_drona_id\">$naziv</option>";
                } else {
                    echo "<option value=\"$vrsta_drona_id\">$naziv</option>";
                }
            }

            zatvoriVezuNaBazu($bazakonekcija);
        ?>
    </select><br>

    <label for="naziv_drona">Naziv drona:</label>
    <input type="text" name="naziv_drona" id="naziv_drona" value="<?php echo $nazivDrona; ?>" required><br>

    <label for="slika">Slika (url):</label>
    <input type="text" name="slika" id="slika" value="<?php echo $slika; ?>" placeholder="Unesite url slike"><br>

    <input type="submit" name="dron" value="<?php echo (isset($dronId) ? 'Spremi promjene' : 'Dodaj dron'); ?>">
</form>