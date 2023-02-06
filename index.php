<?php


require "./includes/db.inc.php";

// First check whether the customer is selected or the customer exists. If not, show an error message.
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

// if customer is selected, get the customer data
$id = $_GET['id'];

// And get customer history
// -> either only created at or also updated at? No use for this in this setup. I deleted it.


// Customer history can be false as well. 
$history = getHistory($id);
if ($history) {
    $history = $history[0];
};

// Check if the form is submitted - if so, check whether the meterstand is valid
if (isset($_POST["currentMeter"]) && !empty($_POST["currentMeter"])) {

    if ($history && $_POST["currentMeter"] <= $history["meterstand"]) {
        $updated = false;
        $message = "Meterstand kan niet lager of gelijk zijn aan vorige inzending";
    } else if (!is_numeric($_POST["currentMeter"])) {
        $updated = false;
        $message = "Meterstand moet een getal zijn";
    } else if ($_POST["currentMeter"] > 999999) {
        $updated = false;
        $message = "Meterstand kan niet hoger zijn dan 999999";
    } else {
        // if all checks are passed, insert the new meterstand
        $updated = true;
        insertNewMeterstand($id, $_POST["currentMeter"]);
        $message = "Bedankt voor het invullen van de meterstand. <br> Je nieuwe meterstand is: " . $_POST["currentMeter"] . " &#13221.";
    }
}

if (isset($updated) && $updated == true) {
    // echo '<div class="error">' . $message . '</div>';
    include "./includes/success.inc.php";
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
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <title>Water Group </title>
</head>

<body>

    <br><br>
    <div class="container">
        <div class="card bg-light">
            <article class="card-body mx-auto" style="max-width: 400px;">
                <h4 class="card-title mt-3 text-center">Welkom <?= $customer["voornaam"] ?></h4>
                <?php
                if ($history) {
                    echo '<p>Jouw vorige meterstand was: <strong>' . $history['meterstand'] . '</strong> &#13221. <br> Deze werd ingezonden op: <strong>' . date("d F Y", strtotime($history['created_at'])) . '</strong>. </p>';
                } else {
                    echo '<p>Je hebt nog geen meterstand ingezonden.</p>';
                }
                ?>
                <form action="" method="POST">
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input name="firstName" class="form-control" value="<?= $customer["voornaam"] ?>" disabled type="text">
                    </div>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input name="lastName" class="form-control" value="<?= $customer["achternaam"] ?>" disabled type="text">
                    </div>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input name="street" class="form-control" value="<?= $customer["straatnaam"] ?>" disabled type="text">
                    </div>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input name="number" class="form-control" value="<?= $customer["nummerbus"] ?>" disabled type="text">
                    </div>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input name="postal" class="form-control" value="<?= $customer["postcode"] ?>" disabled type="text">
                    </div>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input name="City" class="form-control" value="<?= $customer["locatie"] ?>" disabled type="text">
                    </div>
                    <div class="form-group input-group">

                        <input name="currentMeter" class="form-control" placeholder="Nieuwe Meterstand" type="text" min="0" max="999999">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"> Submit </button>
                    </div>
                </form>
                <?php
                if (isset($updated) && $updated == false) {
                    echo '<p>' . $message . '</p>';
                }
                ?>
            </article>
        </div>

    </div>
    <br><br>
</body>

</html>