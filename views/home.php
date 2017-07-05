<div class="wrapper">
    <div class="containerdiv updatesectie">
        <p>Gratis verzending | Binnen 2 werkdagen in huis</p>
    </div>
    <div class="containerdiv titelsectie">
        <h1>Style your meme and let the meme style you</h1>
    </div>
    <div class="containerdiv stapsectie">
        <div>
            <h2>Kies</h2>
            <hr>
            <img src="links/stap1.jpg" alt="stap1">
            <p>Upload een foto of kies uit een van onze bestaande memes.</p>
        </div>
        <div>
            <h2>Ontwerp</h2>
            <hr>
            <img src="" alt="stap2">
            <p>Ontwerp je meme naar je eigen wensen.</p>
        </div>
        <div>
            <h2>Bestel</h2>
            <hr>
            <img src="" alt="stap3">
            <p>Bestel en ontvang je t-shirt.</p>
        </div>
    </div>
    <div class="containerdiv memesectie">
        <h1>Kies een van onze bestaande memes</h1>
        <div class="memewrapper">
        <?php
        $sql = "SELECT * FROM memes ORDER BY `keren_gebruikt` DESC";
        $result = $mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<div class='memeblok' onclick='window.location.href = \"ontwerpen?foto=" . urlencode($row['filename']) . "\"'>";
            echo "<h3>".$row['titel']."</h3>";
            echo "<img class='memeimg' src='".$row['filename']."' alt='".$row['titel']."'</img>";
            echo "</div>";
        }?>
        </div>
        <button onclick="window.location.href='ontwerpen'" type="button" class="">Ontwerp je eigen shirt</button>
        <br>
    </div>

