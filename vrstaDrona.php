<?php

    $bazakonekcija = spojiSeNaBazu();
    $naziv = "";
    $minKm = "";
    $maxKm = "";
    $cijenaPoKm = "";
    $vrstaDronaId = null;

    if (isset($_GET["vrstaDronaId"])) {
        $vrstaDronaId = $_GET["vrstaDronaId"];

        $upit = "SELECT * FROM vrsta_drona WHERE vrsta_drona_id = $vrstaDronaId";
        $vrstaDronaRezultat = izvrsiUpit($bazakonekcija, $upit);
        $vrstaDronaRezultatAsoc = mysqli_fetch_assoc($vrstaDronaRezultat);

        $naziv = $vrstaDronaRezultatAsoc['naziv'];
        $minKm = $vrstaDronaRezultatAsoc['minKM'];
        $maxKm = $vrstaDronaRezultatAsoc['maxKM'];
        $cijenaPoKm = $vrstaDronaRezultatAsoc['cijenaPoKM'];
    }

    if (isset($_POST['vrstaDrona'])) {
        $naziv = $_POST['naziv'];
        $minKm = $_POST['minKm'];
        $maxKm = $_POST['maxKm'];
        $cijenaPoKm = $_POST['cijenaPoKm'];

        $sql;

        if (isset($vrstaDronaId)) {
            $sql = "UPDATE vrsta_drona SET naziv = '$naziv', minKM = $minKm, maxKM = $maxKm, cijenaPoKM = $cijenaPoKm WHERE vrsta_drona_id = $vrstaDronaId";
        } else {    
            $sql = "INSERT INTO vrsta_drona (naziv, minKM, maxKM, cijenaPoKM) 
            VALUES ('$naziv', $minKm, $maxKm, $cijenaPoKm)";
        }

        izvrsiUpit($bazakonekcija, $sql);
        zatvoriVezuNaBazu($bazakonekcija);

        echo "<script>
                window.location.href = \"index.php?vrsteDrona\"
        </script>";
    }
?>
<h2 class="forma-naslov">
    <?php
        if (isset($vrstaDronaId)) {
            echo "Uredi vrstu drona";
        } else {
            echo "Dodaj vrstu drona";
        }
    ?>
</h2>

<form method="POST" <?php echo (isset($vrstaDronaId) ? "action=\"index.php?vrstaDrona&vrstaDronaId=$vrstaDronaId\"" : "action=\"index.php?vrstaDrona\"");?> class="forma">

    <label for="naziv">Naziv:</label>
    <input type="text" name="naziv" value="<?php echo $naziv; ?>" required><br>

    <label for="minKm">Minimalno kilometara:</label>
    <input type="number" name="minKm" value="<?php echo $minKm; ?>" required><br>
    
    <label for="maxKm">Maksimalno kilometara:</label>
    <input type="number" name="maxKm" value="<?php echo $maxKm; ?>" required><br>

    <label for="cijenaPoKm">Cijena po kilometru:</label>
    <input type="number" step="0.1" name="cijenaPoKm" value="<?php echo $cijenaPoKm; ?>" required><br>
    
    <input type="submit" name="vrstaDrona" value="<?php echo (isset($vrstaDronaId) ? 'Spremi promjene' : 'Dodaj vrstu drona'); ?>">
</form>