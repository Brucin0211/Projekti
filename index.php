<?php
    if (empty($_GET)) {
        header("Location:index.php?pocetna");
    }

	include ("baza.php");
	if (session_id() == "") session_start();

    $aktivni_korisnik = null;
	$aktivni_korisnik_tip = null;
	$aktivni_korisnik_ime = null;
	$aktivni_korisnik_id = null;

	if (isset($_SESSION['aktivni_korisnik'])) {
		$aktivni_korisnik = $_SESSION['aktivni_korisnik'];
		$aktivni_korisnik_ime = $_SESSION['aktivni_korisnik_ime'];
		$aktivni_korisnik_tip = $_SESSION['aktivni_korisnik_tip'];
		$aktivni_korisnik_id = $_SESSION["aktivni_korisnik_id"];
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Dostava dronom</title>
		<meta name="autor" content="Bruno Cindric"/>
		<meta name="datum" content="18.6.2023."/>
		<meta charset="utf-8"/>
        <style>
            body {
		        margin: 0;
	        }

            .dostavadronom-app {
                display: grid;
                grid-template-rows: 105px auto 90px;
                height: 100vh;
                background-color: #394D5C;
                color: #8BBDE0;
	        }

            .zaglavlje__meni {
                display: inline-flex;
                background-color: #3679A8;
                list-style-type: none;
                padding: 0;
                margin: 0;
                border-radius: 6px;
	        }

            .zaglavlje__meni li {
                padding: 10px;
                border-radius; 6px;
            }

            .zaglavlje h1 {
                text-align: center;
                margin: 0;
            }
            
            .zaglavlje {
                display: flex;
                flex-direction: column;
                align-items: center;
                row-gap: 10px;
                padding: 10px;
            }

            .sadrzaj {
                overflow: hidden;
            }

            .podnozje {
                display: flex;
                flex-direction: column;
                padding-left: 24px;
                padding-top: 10px;
            }

            a {
                color: white;
                text-decoration: none;
            }

            .dronovi-akcije {
                display: flex;
                flex-direction: column;
            }

            .tablica {
                width: 100%;
                padding: 0 20px;
                border-spacing: unset;
                display: flex;
                flex-direction: column;
                height: 100%;
                padding-bottom: 30px;
            }

            .tablica-mojeDostave {
                padding-bottom: 100px;
            }

            .tablica caption {
                height: 50px;
                line-height: 50px;
                font-weight: bold;
                font-size: 20px;
            }

            .tablica thead,.tablica tbody {  
                background-color: #469DDB;
            }

            .tablica thead tr {
                margin-right: 19px;
            }

            .tablica tbody {
                overflow: auto;
                color:#394D5C;
            }

            .tablica tr {
                display: flex;
            }

            .tablica-dronovi tr th:nth-child(1), .tablica-dronovi tr td:nth-child(1) {
                flex: 0 0 80px;
            }

            .tablica-dronovi tr th:nth-child(2), .tablica-dronovi tr td:nth-child(2) {
                flex: 1 0 350px;
            }

            .tablica-dronovi tr th:nth-child(3), .tablica-dronovi tr td:nth-child(3) {
                flex: 1 0 200px;
            }

            .tablica-dronovi tr th:nth-child(4), .tablica-dronovi tr td:nth-child(4) {
                flex: 1 0 100px;
            }

            .tablica-dronovi tr th:nth-child(5), .tablica-dronovi tr td:nth-child(5) {
                flex: 1 0 100px;
            }

            .tablica-dronovi tr th:nth-child(6), .tablica-dronovi tr td:nth-child(6) {
                flex: 1 0 250px;
            }

            .tablica-dronovi tr th.slika, .tablica-dronovi tr td.slika {
                flex: 1 0 150px;
            }

            .tablica-dronovi tr th.prijedeni-kilometri, .tablica-dronovi tr td.prijedeni-kilometri {
                flex: 0 0 140px;
            }

            .tablica-zahtjevi tr th:nth-child(1), .tablica-zahtjevi tr td:nth-child(1) {
                flex: 0 0 40px;
            }

            .tablica-zahtjevi tr.prazna-tablica td:nth-child(1), .tablica-mojeDostave tr.prazna-tablica td:nth-child(1) {
                flex: 1;
            }

            .tablica.tablica-zahtjevi thead tr, .tablica.tablica-mojeDostave thead tr {
                margin: 0;
            }

            .tablica-zahtjevi tr td, .tablica-mojeDostave tr td, .tablica-korisnici tr td, .tablica-vrsteDrona tr td {
                height: 100px;
                overflow: hidden;
            }

            .tablica-zahtjevi tr th:nth-child(2), .tablica-zahtjevi tr td:nth-child(2) {
                flex: 1 0 200px;
            }

            .tablica-zahtjevi tr th:nth-child(3), .tablica-zahtjevi tr td:nth-child(3) {
                flex: 0 0 120px;
            }

            .tablica-zahtjevi tr th:nth-child(4), .tablica-zahtjevi tr td:nth-child(4) {
                flex: 0 0 140px;
            }

            .tablica-zahtjevi tr th:nth-child(5), .tablica-zahtjevi tr td:nth-child(5) {
                flex: 0 0 140px;
            }

            .tablica-zahtjevi tr th:nth-child(6), .tablica-zahtjevi tr td:nth-child(6) {
                flex: 1 0 120px;
            }

            .tablica-zahtjevi tr th:nth-child(7), .tablica-zahtjevi tr td:nth-child(7) {
                flex: 1 0 120px;
            }

            .tablica-zahtjevi tr th:nth-child(8), .tablica-zahtjevi tr td:nth-child(8) {
                flex: 0 0 100px;
            }

            .tablica-zahtjevi tr th:nth-child(9), .tablica-zahtjevi tr td:nth-child(9) {
                flex: 0 0 90px;
            }

            .tablica-zahtjevi tr th:nth-child(10), .tablica-zahtjevi tr td:nth-child(10) {
                flex: 0 0 80px;
            }

            .tablica-zahtjevi tr th:nth-child(11), .tablica-zahtjevi tr td:nth-child(11) {
                flex: 0 0 80px;
            }

            .tablica-zahtjevi tr th:nth-child(12), .tablica-zahtjevi tr td:nth-child(12) {
                flex: 0 0 60px;
            }

            .tablica-zahtjevi tr th:nth-child(13), .tablica-zahtjevi tr td:nth-child(13) {
                flex: 0 0 120px;
            }

            .tablica-zahtjevi tr th:nth-child(14), .tablica-zahtjevi tr td:nth-child(14) {
                flex: 0 0 120px;
            }

            .tablica-mojeDostave tr th:nth-child(1), .tablica-mojeDostave tr td:nth-child(1) {
                flex: 0 0 40px;
            }

            .tablica-mojeDostave tr th:nth-child(2), .tablica-mojeDostave tr td:nth-child(2) {
                flex: 1 0 200px;
            }

            .tablica-mojeDostave tr th:nth-child(3), .tablica-mojeDostave tr td:nth-child(3) {
                flex: 0 0 120px;
            }

            .tablica-mojeDostave tr th:nth-child(4), .tablica-mojeDostave tr td:nth-child(4) {
                flex: 0 0 140px;
            }

            .tablica-mojeDostave tr th:nth-child(5), .tablica-mojeDostave tr td:nth-child(5) {
                flex: 0 0 140px;
            }

            .tablica-mojeDostave tr th:nth-child(6), .tablica-mojeDostave tr td:nth-child(6) {
                flex: 1 0 120px;
            }

            .tablica-mojeDostave tr th:nth-child(7), .tablica-mojeDostave tr td:nth-child(7) {
                flex: 1 0 120px;
            }

            .tablica-mojeDostave tr th:nth-child(8), .tablica-mojeDostave tr td:nth-child(8) {
                flex: 0 0 100px;
            }

            .tablica-mojeDostave tr th:nth-child(9), .tablica-mojeDostave tr td:nth-child(9) {
                flex: 0 0 90px;
            }

            .tablica-mojeDostave tr th:nth-child(10), .tablica-mojeDostave tr td:nth-child(10) {
                flex: 0 0 80px;
            }

            .tablica-mojeDostave tr th:nth-child(11), .tablica-mojeDostave tr td:nth-child(11) {
                flex: 0 0 80px;
            }

            .tablica-mojeDostave tr th:nth-child(12), .tablica-mojeDostave tr td:nth-child(12) {
                flex: 0 0 60px;
            }

            .tablica-mojeDostave tr th:nth-child(13), .tablica-mojeDostave tr td:nth-child(13) {
                flex: 0 0 120px;
            }

            .tablica-mojeDostave tr th:nth-child(14), .tablica-mojeDostave tr td:nth-child(14) {
                flex: 0 0 120px;
            }

            .tablica tr th {
                flex: 1;
                display: flex;
                justify-content: flex-start;
                align-items: center;
                gap: 5px;
                height: 50px;
                color: #394D5C;
                font-weight: bold;
                padding-left: 20px;
            }

            .tablica tr td {
                flex: 1;
                display: flex;
                justify-content: flex-start;
                align-items: center;
                gap: 5px;
                padding-left: 20px;
            }

            .tablica tr td img {
                width: 100px;
                height: 100px;
                object-fit: cover;
            }

            .filtriraj-dostave {
                padding-left: 20px;
            }

            .dostava-forma, .dron-forma, .potvrdi-dostavu-forma, .forma {
                display: flex;
                flex-direction: column;
                padding-left: 20px;
                max-width: 800px;
                margin: 0 auto;
            }

            .dostava-forma-naslov, .dron-forma-naslov, .potvrdi-dostavu-forma-naslov, .forma-naslov {
                text-align: center
            }

            .prazna-tablica td {
                height: 500px;
                width: 100%;
                padding-left: 0 !important;
            }

            .dodaj-dron-gumb, .dodaj-korisnika-gumb, .dodaj-vrstu-drona-gumb {
                padding: 6px;
                margin-left: 20px;
                line-height: 30px;
                background-color: #469DDB;
            }

            .prazna-tablica td div {
                width: 100%;
                text-align: center;
                font-size: 22px;
                font-weight: bold;
            }
        </style>
	</head>

    <body>

        <div class="dostavadronom-app">

            <div class="zaglavlje">
                <h1>Dostava dronom</h1>
                <ul class="zaglavlje__meni">
                    <li>
                        <a href="index.php?pocetna">Početna</a>
                    </li>
                    <?php
                        if($aktivni_korisnik_tip == "obicni"){
                            echo "
                            <li>
                                <a href=\"index.php?dronovi\">Dronovi</a>
                            </li>
                            <li>
                                <a href=\"index.php?dostava\">Dostava</a>
                            </li>
                            <li>
                                <a href=\"index.php?mojeDostave\">Moje dostave</a>
                            </li>
                            ";
                        } else if($aktivni_korisnik_tip == "moderator"){
                            echo "
                            <li>
                                <a href=\"index.php?dronovi\">Dronovi</a>
                            </li>
                            <li>
                                <a href=\"index.php?dostava\">Dostava</a>
                            </li>
                            <li>
                                <a href=\"index.php?mojeDostave\">Moje dostave</a>
                            </li>
                            <li>
                                <a href=\"index.php?popis_zahtjeva\">Popis zahtjeva</a>
                            </li>
                            ";
                        } else if ($aktivni_korisnik_tip == "admin"){
                            echo "
                            <li>
                                <a href=\"index.php?dronovi\">Dronovi</a>
                            </li>
                            <li>
                                <a href=\"index.php?dostava\">Dostava</a>
                            </li>
                            <li>
                                <a href=\"index.php?mojeDostave\">Moje dostave</a>
                            </li>
                            <li>
                                <a href=\"index.php?popis_zahtjeva\">Popis zahtjeva</a>
                            </li>
                            <li>
                                <a href=\"index.php?korisnici\">Korisnici</a>
                            </li>
                            <li>
                                <a href=\"index.php?vrsteDrona\">Vrste dronova</a>
                            </li>
                            ";
                        }

                    ?>
                        <li> <a href ="index.php?o_autoru">O autoru</a>
                    </li>
                </ul>
            </div>

            <div class="sadrzaj">
                <?php
                    if (isset($_GET['pocetna'])) {
                        include("pocetna.php");
                    } else if (isset($_GET['o_autoru'])) {
                        include("o_autoru.html");
                    } else if (isset($_GET['dronovi'])) {
                        include("dronovi.php");
                    } else if (isset($_GET['dostava'])) {
                        include("dostava.php");
                    } else if (isset($_GET['popis_zahtjeva'])) {
                        include("popis_zahtjeva.php");
                    } else if (isset($_GET['mojeDostave'])) {
                        include("mojeDostave.php");
                    }  else if (isset($_GET['potvrdiDostavu'])) {
                        include("potvrdiDostavu.php");
                    } else if (isset($_GET['dron'])) {
                        include("dron.php");
                    } else if (isset($_GET['korisnici'])) {
                        include("korisnici.php");
                    } else if (isset($_GET['korisnik'])) {
                        include("korisnik.php");
                    } else if (isset($_GET['vrsteDrona'])) {
                        include("vrsteDrona.php");
                    } else if (isset($_GET['vrstaDrona'])) {
                        include("vrstaDrona.php");
                    }
                ?>
            </div>

            <div class="podnozje">
                <?php
                    if (!$aktivni_korisnik) {
                        echo "<a href=\"login.php\">Ulogirajte se</a>";
                    } else {
                        echo "
                            <div><b>Korisnik:</b> $aktivni_korisnik_ime</div>
                            <div><b>Tip korisnika:</b> $aktivni_korisnik_tip</div>
                            <a href=\"login.php?odjava=1\">Odjavite se</a>
                        ";
                    }
                ?>
            </div>
        </div>

    </body>

</html>