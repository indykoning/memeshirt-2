<nav class="topnav" id="nav">
    <a href="home" class="nav_img"><img src="links/logo.png" height="30" alt="logo"></a>
    <a href="javascript:void(0);" id="burgermenu" class="icon"><i class="material-icons">&#xE5D2;</i></a>
    <?php
    $sql = "SELECT bestelling_id FROM images WHERE bestelling_id = ".$_SESSION['bestelling_id'];
    $result = $mysqli->query($sql);
    echo "<a href='winkelwagen' class='nav_a nav_img'><img src='links/winkelwagen.png' alt='winkelwagen' height='30'><span class='winkel'>($result->num_rows)</span></a>";
    ?>
    <?php
    if (rank == 1) {
        echo '<a href="admin?page=admin" class="nav_a">Admin</a>';
    }
    ?>
    <a href="winkelen" class="nav_a">Winkelen</a>
    <a href="ontwerpen" class="nav_a">Ontwerpen</a>
    <?php
    if (LOGGED_IN){
        echo '<a href="?logout" class="nav_a">Log uit</a>';
    }else {
        echo '<a href="inloggen" class="nav_a">Log in</a>';
    };
    ?>
</nav>
<div style="height: 50px;"></div>