<style>
    .top-dronovi {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding-left: 24px;
        height: 100%;
    }

    .top-dronovi__popis-dronova {
        overflow: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .top-dronovi__dron-container {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .top-dronovi__dron-container img {
        width: 250px;
        height: 250px;
        object-fit: cover;
        border-radius: 6px;
    }

    .top-dronovi__dron-container__informacije {
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }

    .top-dronovi__dron-container__informacije ul {
        list-style-type: none;
        padding: 0;
    } 
</style>

<div class="top-dronovi">
    <h2>Top 5 dronova</h2>

    <div class="top-dronovi__popis-dronova">
        <?php
            $bazakonekcija = spojiSeNaBazu();
            $topdronovi = izvrsiUpit($bazakonekcija,
            "SELECT d.dron_id, d.naziv AS naziv_drona, d.poveznica_slika,d.vrsta_drona_id, COUNT(ds.dostava_id) AS broj_dostava,vd.naziv AS naziv_vrste_drona, vd.minKM, vd.maxKM, vd.cijenaPoKM
            FROM dron AS d
            JOIN dostava AS ds ON d.dron_id = ds.dron_id
            JOIN vrsta_drona AS vd ON d.vrsta_drona_id = vd.vrsta_drona_id
            GROUP BY d.dron_id, d.naziv
            ORDER BY broj_dostava DESC
            LIMIT 5;
            ");

            while(list($dron_id, $naziv_drona, $poveznica_slika, $vrsta_drona_id, $broj_dostava, $vrsta_drona_naziv, $minKm, $maxKm, $cijenaPoKm)=mysqli_fetch_array($topdronovi))echo "

                <div class=\"top-dronovi__dron-container\">
                    <img src=\"$poveznica_slika\" alt=\"$naziv_drona\">
                    <div class=\"top-dronovi__dron-container__informacije\">
                        <h3>$naziv_drona</h3>
                        <ul>
                            <li><b>Broj dostava:</b> $broj_dostava</li>
                            <li><b>Vrsta drona:</b> $vrsta_drona_naziv</li>
                            <li><b>Minimalno kilometara:</b> $minKm km</li>
                            <li><b>Maksimalno kilometara:</b> $maxKm km</li>
                            <li><b>Cijena po km:</b> $cijenaPoKm eura</li>
                        </ul>
                    </div>
                </div>
            
            ";

            zatvoriVezuNaBazu($bazakonekcija);
        ?>
    </div>
</div>