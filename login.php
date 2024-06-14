<?php
    include ("baza.php");

    if (session_id() == "") session_start();

    if (isset($_GET['odjava'])) {
		unset($_SESSION["aktivni_korisnik"]);
		unset($_SESSION['aktivni_korisnik_ime']);
		unset($_SESSION["aktivni_korisnik_tip"]);
		unset($_SESSION["aktivni_korisnik_id"]);
		session_destroy();
		header("Location:index.php");
	}

    $bp = spojiSeNaBazu();

	$greska = "";

	if (isset($_POST['login_podaci'])) {
		$kor_ime = mysqli_real_escape_string($bp, $_POST['korisnicko_ime']);
		$lozinka = mysqli_real_escape_string($bp, $_POST['lozinka']);

		if (!empty($kor_ime) && !empty($lozinka)) {
			$sql = "SELECT k.korisnik_id, k.ime, k.prezime, t.naziv
            FROM korisnik AS k
            JOIN tip_korisnika AS t ON k.tip_korisnika_id = t.tip_korisnika_id 
            WHERE korime = '$kor_ime' AND lozinka = '$lozinka'";

			$rs = izvrsiUpit($bp, $sql);
			if (mysqli_num_rows($rs) == 0) $greska="Ne postoji korisnik s navedenim korisničkim imenom i lozinkom!";
			else {
				list($id, $ime, $prezime, $tip)=mysqli_fetch_array($rs);
				$_SESSION['aktivni_korisnik'] = $kor_ime;
				$_SESSION['aktivni_korisnik_ime'] = $ime." ".$prezime;
				$_SESSION["aktivni_korisnik_id"] = $id;
				$_SESSION['aktivni_korisnik_tip'] = $tip;
				header("Location:index.php");
			}
		}
		else $greska = "Molim unesite korisničko ime i lozinku!";
	}
?>

<style>
    .login {
        height: 100vh;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #394D5C;
        color: #8BBDE0;
        margin: 0;
    }

    body {
        margin: 0;
    }

    .login-forma {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        height: 300px;
        width: 500px;
        border-radius: 6px;
        border: 2px solid;
        background-color: #469DDB;
        color: #394D5C;
    }

    .login-forma button {
        width: 250px;
        height: 30px;
        border: none;
        border-radius: 6px;
        background-color: #1D425C;
        color: #469DDB;
        cursor: pointer;
        margin-top: 10px;
    }

    .login-forma__kontrola {
        width: 250px;
    }

    .login-forma__kontrola label {
        display: block;
    }

    .login-forma__kontrola input {
        width: 100%;
        height: 30px;
        background-color: #8BBDE0;
        color: #394D5C;
        border: none;
    }

    .login-forma__kontrola input:focus {
        border: none;
    }

    .login-forma__poruka {
        color: rgba(150, 10, 60, 1);
    }
</style>

<form class="login" method="POST" action="login.php">
    <div class="login-forma">

        <div class="login-forma__kontrola">
            <label for="korisnicko_ime">Korisničko ime:</label>
            <input id="korisnicko_ime" name="korisnicko_ime" type="text">
        </div>

        <div class="login-forma__kontrola">
            <label for="lozinka">Lozinka:</label>
            <input id="lozinka" name="lozinka" type="password">
        </div>

        <button name="login_podaci" type="submit">Prijavite se</button>

        <p class="login-forma__poruka">
            <?php
                echo $greska;
            ?>
        </p>
    </div>
</form>