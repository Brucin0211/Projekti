<?php

    $bazakonekcija = spojiSeNaBazu();
    $ime = "";
    $prezime = "";
    $email = "";
    $korime = "";
    $lozinka = "";
    $vrstaDrona = null;
    $inicijalniTipKorisnikaId = null;
    $inicijalnaVrstaDronaId = null;
    $korisnikId = null;

    if (isset($_GET["korisnikId"])) {
        $korisnikId = $_GET["korisnikId"];

        $upit = "SELECT * FROM korisnik WHERE korisnik_id = $korisnikId";
        $korisnikRezultat = izvrsiUpit($bazakonekcija, $upit);
        $korisnikRezultatAsoc = mysqli_fetch_assoc($korisnikRezultat);

        $ime = $korisnikRezultatAsoc['ime'];
        $prezime = $korisnikRezultatAsoc['prezime'];
        $korime = $korisnikRezultatAsoc['korime'];
        $lozinka = $korisnikRezultatAsoc['lozinka'];
        $email = $korisnikRezultatAsoc['email'];
        $inicijalniTipKorisnikaId = $korisnikRezultatAsoc['tip_korisnika_id'];
        $inicijalnaVrstaDronaId = $korisnikRezultatAsoc['vrsta_drona_id'];
    }

    if (isset($_POST['korisnik'])) {
        $ime = $_POST['ime'];
        $prezime = $_POST['prezime'];
        $email = $_POST['email'];
        $korime = $_POST['korime'];
        $lozinka = $_POST['lozinka'];
        $vrstaDronaId = isset($_POST['vrstaDrona']) ? $_POST['vrstaDrona'] : NULL;
        $tipKorisnikaId = $_POST['tipKorisnika'];
        

        $sql;

        if (isset($korisnikId)) {
            $sql = "UPDATE korisnik SET tip_korisnika_id = $tipKorisnikaId, vrsta_drona_id = ".(isset($vrstaDronaId) ? $vrstaDronaId : "NULL").",
             korime = '$korime', email = '$email', lozinka = '$lozinka', ime = '$ime', prezime = '$prezime' WHERE korisnik_id = $korisnikId";
        } else {    
            $sql = "INSERT INTO korisnik (tip_korisnika_id, vrsta_drona_id, korime, ime, prezime, email, lozinka) 
            VALUES ($tipKorisnikaId, ".(isset($vrstaDronaId) ? $vrstaDronaId : "NULL").", '$korime', '$ime', '$prezime', '$email', '$lozinka')";
        }

        izvrsiUpit($bazakonekcija, $sql);
        zatvoriVezuNaBazu($bazakonekcija);

        echo "<script>
                window.location.href = \"index.php?korisnici\"
        </script>";
    }
?>
<h2 class="forma-naslov">
    <?php
        if (isset($korisnikId)) {
            echo "Uredi korisnika";
        } else {
            echo "Dodaj korisnika";
        }
    ?>
</h2>

<form method="POST" <?php echo (isset($korisnikId) ? "action=\"index.php?korisnik&korisnikId=$korisnikId\"" : "action=\"index.php?korisnik\"");?> class="forma">

    <label for="ime">Ime:</label>
    <input type="text" name="ime" id="ime" value="<?php echo $ime; ?>" required><br>

    <label for="prezime">Prezime:</label>
    <input type="text" name="prezime" id="prezime" value="<?php echo $prezime; ?>" required><br>

    <label for="email">Email:</label>
    <input type="text" name="email" id="email" value="<?php echo $email; ?>" required><br>

    <label for="korime">Korisničko ime:</label>
    <input type="text" name="korime" id="korime" value="<?php echo $korime; ?>" required><br>

    <label for="lozinka">Lozinka:</label>
    <input type="text" name="lozinka" id="lozinka" value="<?php echo $lozinka; ?>" required><br>

    <label for="tipKorisnika">Tip korisnika:</label>
    <select name="tipKorisnika" id="tipKorisnika" onchange="postaviVrstuDrona(value)">
        <?php
            $upit = "SELECT * FROM tip_korisnika";
            $tipoviKorisnikaRezultat = izvrsiUpit($bazakonekcija, $upit);

            while(list($tipKorisnikaId, $naziv) = mysqli_fetch_array($tipoviKorisnikaRezultat)) {
                if (isset($inicijalniTipKorisnikaId) && $tipKorisnikaId == $inicijalniTipKorisnikaId) {
                    echo "<option selected=\"selected\" value=\"$tipKorisnikaId\">$naziv</option>";
                } else {
                    echo "<option value=\"$tipKorisnikaId\">$naziv</option>";
                }
            }
        ?>
    </select><br>
    
    <label id="vrstaDronaLabela" for="vrstaDrona">Dodijeljena vrsta drona:</label>
    <select name="vrstaDrona" id="vrstaDrona">
        <?php
            $upit = "SELECT vrsta_drona_id, naziv FROM vrsta_drona";
            $vrsteDronaRezultat = izvrsiUpit($bazakonekcija, $upit);

            while(list($vrstaDronaId, $naziv) = mysqli_fetch_array($vrsteDronaRezultat)) {
                if (isset($inicijalnaVrstaDronaId) && $vrstaDronaId == $inicijalnaVrstaDronaId) {
                    echo "<option selected=\"selected\" value=\"$vrstaDronaId\">$naziv</option>";
                } else {
                    echo "<option value=\"$vrstaDronaId\">$naziv</option>";
                }
            }
        ?>
    </select><br>

    <input type="submit" name="korisnik" value="<?php echo (isset($korisnikId) ? 'Spremi promjene' : 'Dodaj korisnika'); ?>">
</form>

<script>
    var tipKorisnikaVrijednost = document.getElementById("tipKorisnika")?.value
    postaviVrstuDrona(tipKorisnikaVrijednost)

    function postaviVrstuDrona(tipKorisnikaVrijednost) {
        var vrstaDronaElement = document.getElementById("vrstaDrona")
        var vrstaDronaLabelaElement = document.getElementById("vrstaDronaLabela")

        vrstaDronaElement.style = tipKorisnikaVrijednost != 1 ? "display: none;" : "display: unset;"
        vrstaDronaLabelaElement.style = tipKorisnikaVrijednost != 1 ? "display: none;" : "display: unset;"
        vrstaDronaElement.disabled = tipKorisnikaVrijednost != 1
    }

</script>