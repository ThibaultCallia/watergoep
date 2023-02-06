<?php


require "./includes/db.inc.php";

if (isset($_GET) && !empty($_GET['id'])) {
    $customer = getCustomer($_GET['id']);
    if (!$customer) {
        echo "No customer found";
        exit();
    }
} else {
    echo "No customer selected";
    exit();
}

$id = $_GET['id'];
//now get the customer data - meter stand
// -> either only created at or also updated at? No use for this in this setup  

$history = getHistory($id);
if ($history) {
    $history = $history[0];
};

if (isset($_POST["currentMeter"]) && !empty($_POST["currentMeter"])) {

    if ($history && $_POST["currentMeter"] <= $history["meterstand"]) {
        $updated = false;
        $message = "Meterstand kan niet lager of gelijk zijn aan vorige inzending";
    } else {
        $updated = true;
        insertNewMeterstand($id, $_POST["currentMeter"]);
        $message = "Bedankt voor het invullen van de meterstand. Je nieuwe meterstand is: " . $_POST["currentMeter"] . " m3.";
    }
}


if (isset($updated) && $updated == true) {
    echo '<div class="error">' . $message . '</div>';
    exit();
}

$pdo = null;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Water Group </title>
</head>



<body>
    <h1>Welkom <?= $customer["voornaam"] ?></h1>

    <?php
    if ($history) {
        echo '<div class="prev_info">
        <p>Jouw vorige meterstand was: <strong>' . $history['meterstand'] . '</strong> m3.</p>
        <p>Deze werd ingezonden op: <strong>' . date("d F Y", strtotime($history['created_at'])) . '</strong>. </p>
        </div>';
    } else {
        echo '<div class="prev_info">
        <p>Je hebt nog geen meterstand ingezonden.</p>
        </div>';
    }
    ?>

    <form action="" method="POST">
        <div class="formel"><label for="firstName">Voornaam: </label><input type="text" name="firstName" value="<?= $customer["voornaam"] ?>" disabled /></div>
        <div class="formel"><label for="lastName">Achternaam: </label><input type="text" name="lastName" value="<?= $customer["achternaam"] ?>" disabled /></div>
        <div class="formel"><label for="street">Straat: </label><input type="text" name="street" value="<?= $customer["straatnaam"] ?>" disabled /></div>
        <div class="formel"><label for="number">Nummer: </label><input type="text" name="number" value="<?= $customer["nummerbus"] ?>" disabled /></div>
        <div class="formel"><label for="postal">Postcode: </label><input type="text" name="postal" value="<?= $customer["postcode"] ?>" disabled /></div>
        <div class="formel"><label for="city">Stad: </label><input type="text" name="city" value="<?= $customer["locatie"] ?>" disabled /></div>
        <div class="formel"><label for="currentMeter">Huidige meterstand: </label><input type="text" name="currentMeter" min="0" max="999999" /></div>
        <input type="submit" value="Submit" />
    </form>
    <?php
    if (isset($updated) && $updated == false) {
        echo '<div class="error">' . $message . '</div>';
    }
    ?>
</body>

</html>