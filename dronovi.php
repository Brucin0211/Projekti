<?php 
    $bazakonekcija = spojiSeNaBazu();

    $zaduzenaVrsta = null;


    if ($aktivni_korisnik_tip == "moderator" || $aktivni_korisnik_tip == "admin") {
        
        $upitZaduzeneVrste = "SELECT vrsta_drona_id FROM korisnik WHERE korisnik_id = $aktivni_korisnik_id";

        $zaduzenaVrstaRezultat = izvrsiUpit($bazakonekcija, $upitZaduzeneVrste);

        $zaduzenaVrsta = mysqli_fetch_assoc($zaduzenaVrstaRezultat)['vrsta_drona_id'];

        echo "<a class=\"dodaj-dron-gumb\" href=\"index.php?dron\">Dodaj dron</a>";
    }

?>
<table class="tablica tablica-dronovi">
	<caption>
        Popis dronova
	</caption>
	<thead>
		<tr>
			<th>ID</th>
			<th>Naziv
                (<a href="index.php?dronovi&sortKolona=naziv&sortSmjer=asc">Uzlazno</a></option> 
                | <a href="index.php?dronovi&sortKolona=naziv&sortSmjer=desc">Silazno</a></option>)
            </th>
			<th>Vrsta drona</th>
			<th>Min km</th>
			<th>Max km</th>
			<th>Cijena po km (<a href="index.php?dronovi&sortKolona=cijena&sortSmjer=asc">Uzlazno</a></option> 
                | <a href="index.php?dronovi&sortKolona=cijena&sortSmjer=desc">Silazno</a></option>)</th>
            <?php
                if ($aktivni_korisnik_tip == "moderator" || $aktivni_korisnik_tip == "admin") {
                    echo "<th class=\"prijedeni-kilometri\">Prijedeni kilometri</th>";
                }
            ?>
            <th class="slika">Slika</th>
		</tr>
	</thead>
	<tbody>
        <?php
            $sortKolona = isset($_GET['sortKolona']) ? $_GET['sortKolona'] : null;
            $sortSmjer = isset($_GET['sortSmjer']) ? $_GET['sortSmjer'] : null;

            $upit = "SELECT d.dron_id, d.naziv AS naziv, d.poveznica_slika, vd.naziv AS naziv_vrste_drona, vd.vrsta_drona_id, vd.minKM, vd.maxKM, vd.cijenaPoKM AS cijena, SUM(do.dostavaKM) as prijedeniKilometri
                FROM dron AS d
                LEFT JOIN dostava AS do ON do.dron_id = d.dron_id
                JOIN vrsta_drona AS vd ON d.vrsta_drona_id = vd.vrsta_drona_id
                GROUP BY d.dron_id
            ";

            if ($sortKolona) {
                $upit .= "ORDER BY $sortKolona $sortSmjer";
            }

            $popisDronova = izvrsiUpit($bazakonekcija, $upit);

            while(list($dron_id, $naziv_drona, $poveznica_slika, $vrsta_drona_naziv, $vrsta_drona_id, $minKm, $maxKm, $cijenaPoKm, $prijedeniKm)=mysqli_fetch_array($popisDronova)) {
                $zaokruzeniKilometri = round($prijedeniKm);
                echo "<tr>
                    <td>$dron_id</td>
                    <td>$naziv_drona</td>
                    <td>$vrsta_drona_naziv</td>
                    <td>$minKm</td>
                    <td>$maxKm</td>
                    <td>$cijenaPoKm</td>"
                    .($aktivni_korisnik_tip == 'moderator' || $aktivni_korisnik_tip == 'admin' ? "<td class=\"prijedeni-kilometri\">$zaokruzeniKilometri km</td>" : '').
                    "<td class=\"slika\">
                        <img src=\"$poveznica_slika\">
                        <div class=\"dronovi-akcije\">
                            <a href=\"index.php?dostava&dronId=$dron_id\">Zatrazi dostavu</a>
                ";

                if ($zaduzenaVrsta == $vrsta_drona_id || $aktivni_korisnik_tip == "admin") {
                    echo "<a href=\"index.php?dron&dronId=$dron_id\">Uredi podatke</a></div>";
                } else {
                    echo "</div>";
                }

                echo "</td>
                </tr>";    
            }

            zatvoriVezuNaBazu($bazakonekcija);

        ?>
	</tbody>
</table>