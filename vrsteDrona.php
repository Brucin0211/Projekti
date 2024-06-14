<?php
    $bazakonekcija = spojiSeNaBazu();

    echo "<a class=\"dodaj-vrstu-drona-gumb\" href=\"index.php?vrstaDrona\">Dodaj vrstu drona</a>";
?>

<table class="tablica tablica-vrsteDrona">
	<caption>
        Vrste dronova
	</caption>
	<thead>
		<tr>
			<th>ID</th>
			<th>Naziv vrste</th>
			<th>Minimalno kilometara</th>
			<th>Maksimalno kilometara</th>
			<th>Cijena po km</th>
			<th>Akcije</th>
		</tr>
	</thead>
	<tbody>
        <?php

            $upit = "SELECT * FROM vrsta_drona ORDER BY vrsta_drona_id";

            $popisDronovaRezultat = izvrsiUpit($bazakonekcija, $upit);

            while(list($vrstaDronaId, $naziv, $minKm, $maxKm, $cijenaPoKm) = mysqli_fetch_array($popisDronovaRezultat)) {
                echo "<tr>
                    <td>$vrstaDronaId</td>
                    <td>$naziv</td>
                    <td>$minKm km</td>
                    <td>$maxKm km</td>
                    <td>$cijenaPoKm eura</td>
                    <td>
                        <a href=\"index.php?vrstaDrona&vrstaDronaId=$vrstaDronaId\">Uredi vrstu drona</a>
                    </td>
                </tr>";    
            }

            zatvoriVezuNaBazu($bazakonekcija);

        ?>
	</tbody>
</table>