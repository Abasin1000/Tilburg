<?php
$servername = "localhost"; // Of de servernaam van jouw database
$username = "root"; // Jouw MySQL-gebruikersnaam
$password = ""; // Jouw MySQL-wachtwoord
$database = "tilburg_wandeling";

$conn = new mysqli($servername, $username, $password, $database);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
?>
