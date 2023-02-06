<?php
$db_host = 'ID396978_watergroep2.db.webhosting.be';
$db_user = 'ID396978_watergroep2';
$db_password = 'watergroepPass2099';
$db_db = 'ID396978_watergroep2';
$db_port = 3306;

try {
    $pdo = new PDO('mysql:host=' . $db_host . '; port=' . $db_port . '; dbname=' . $db_db, $db_user, $db_password);
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}

function getDbInfo()
{
    global $pdo;
    // I chose to use a ternary operator to determine the direction of the sort. Seemed more logical to sort DESC when on views.

    $stmt = $pdo->prepare("SELECT * FROM `watergroep_klanten` ");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCustomer($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM `watergroep_klanten` WHERE `id` = :id");
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getHistory($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM `watergroep_meterstanden` WHERE `klant_id` = :id ORDER BY `created_at` DESC");
    $stmt->execute(['id' => $id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertNewMeterstand($id, $meterstand)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO `watergroep_meterstanden` (`id`, `klant_id`, `meterstand`, `created_at`) VALUES (NULL, :id, :meterstand, CURRENT_TIMESTAMP)");
    $stmt->execute(['id' => $id, 'meterstand' => $meterstand]);
}
