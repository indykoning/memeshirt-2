<?php
chdir('../../');
$expire = 365*24*3600;
ini_set('session.gc_maxlifetime', $expire);
ini_set('memory_limit', '-1');
ini_set('post_max_size', '-1');
ini_set('upload_max_filesize', '-1');
session_start();
require_once  "includes/db.php";
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$sql = "SELECT * FROM `prijzen` WHERE id=1";
$result = $mysqli->query($sql);
$prijzen = $result->fetch_assoc();
define('PRIJS_XS', $prijzen['XS']);
define('PRIJS_S', $prijzen['S']);
define('PRIJS_M', $prijzen['M']);
define('PRIJS_L', $prijzen['L']);
define('PRIJS_XL', $prijzen['XL']);
define('PRIJS_XXL', $prijzen['XXL']);
$output_width = 3508;
$output_height = 2480;


function setTransparency($new_image,$image_source)
{

    $transparencyIndex = imagecolortransparent($image_source);
    $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);

    if ($transparencyIndex >= 0) {
        $transparencyColor    = imagecolorsforindex($image_source, $transparencyIndex);
    }

    $transparencyIndex    = imagecolorallocate($new_image, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
    imagefill($new_image, 0, 0, $transparencyIndex);
    imagecolortransparent($new_image, $transparencyIndex);

}

if (!empty($_POST['image'])) {
    $img = $_POST['image'];
    ini_set('gd.jpeg_ignore_warning', 1);
    $rand = rand();
    $imagename = $rand . ".jpg";
    $file = getcwd() . '/order_images/' . $imagename;
    for ($i = 0; file_exists($file); $i++) {
        $imagename = $rand++ . ".jpg";
        $file = getcwd() . '/order_images/' . $imagename;
    }

    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $image = imagecreatefromstring($data);
    $image_p = imagecreatetruecolor($output_width, $output_height);
    setTransparency($image_p, $image);
    $width = imagesx($image);
    $height = imagesy($image);
    imagecopyresampled($image_p, $image, 0, 0, 0, 0,$output_width , $output_height, $width, $height);

    ob_start(); // Let's start output buffering.
    imagejpeg($image_p); //This will normally output the image, but because of ob_start(), it won't.
    $data = ob_get_contents(); //Instead, output above is saved to $contents
    ob_end_clean();

    $context = stream_context_create([
        'gs' => [
            'acl' => 'public-read',
            'Content-Type' => 'image/jpeg',
            'enable_cache' => true,
            'enable_optimistic_cache' => true,
            'read_cache_expiry_seconds' => 300,
        ]
    ]);
    if (file_put_contents($file, $data, false, $context) !== false) {

        $user_id = (!empty($_SESSION['ID'])) ? $_SESSION['ID'] : 'Null';
        if (empty($_SESSION['bestelling_id'])) {
            $sql = "INSERT INTO `bestelling`(`status`, `users_id`) VALUES (0, $user_id)";
            $result = $mysqli->query($sql);
            $_SESSION['bestelling_id'] = $mysqli->insert_id;

        }
        $xs = abs($_POST['xs']) * PRIJS_XS;
        $s = abs($_POST['s']) * PRIJS_S;
        $m = abs($_POST['m']) * PRIJS_M;
        $l = abs($_POST['l']) * PRIJS_L;
        $xl = abs($_POST['xl']) * PRIJS_XL;
        $xxl = abs($_POST['xxl']) * PRIJS_XXL;
        $totaal = $xs+$s+$m+$l+$xl+$xxl;
//        $sql = "INSERT INTO `images`(`filename`, `totaal_prijs`, `xs`, `s`, `m`, `l`, `xl`, `xxl`, `bestelling_id`, `kleur`) VALUES ('" . $imagename . "',". $totaal ."," . abs($_POST['xs']) . "," . abs($_POST['s']) . "," . abs($_POST['m']) . "," . abs($_POST['l']) . "," . abs($_POST['xl']) . "," . abs($_POST['xxl']) . "," . $_SESSION['bestelling_id'] . ", '".$_POST['shirtColor']."')";
//        $mysqli->query($sql);


        $stmt = $db->prepare("INSERT INTO `images`(`filename`, `totaal_prijs`, `xs`, `s`, `m`, `l`, `xl`, `xxl`, `bestelling_id`, `kleur`) VALUES ('" . $imagename . "',". $totaal ."," . abs($_POST['xs']) . "," . abs($_POST['s']) . "," . abs($_POST['m']) . "," . abs($_POST['l']) . "," . abs($_POST['xl']) . "," . abs($_POST['xxl']) . "," . $_SESSION['bestelling_id'] . ", :shirtColor)");
        $stmt->bindValue(':shirtColor', filter_var($_POST['shirtColor'], FILTER_SANITIZE_STRING), PDO::PARAM_STR);
        $stmt->execute();
        if (isset($_POST['fotoNaam'])) {
            $stmt = $db->prepare("UPDATE `memes` SET keren_gebruikt = keren_gebruikt + 1");
            $stmt->execute();
        }


    }
}?>