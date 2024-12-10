<?php
require 'db.php'; // Zorg ervoor dat dit bestand de juiste databaseverbinding bevat
session_start();

// Start de sessie en controleer of de gebruiker ingelogd is
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Variabelen voor succes- en foutmeldingen
$success = false;
$error = '';

// Verwerk de POST-aanroep van het formulier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Verkrijg en beveilig de ingevoerde gegevens
        $voornaam = htmlspecialchars($_POST['naam']);
        $achternaam = htmlspecialchars($_POST['achternaam']);
        $geslacht = htmlspecialchars($_POST['geslacht']);
        $email = htmlspecialchars($_POST['email']);
        $telefoon = htmlspecialchars($_POST['telefoon']);
        $adres = htmlspecialchars($_POST['adres']);
        $leeftijd = (int)$_POST['leeftijd'];
        $bericht = htmlspecialchars($_POST['bericht']);

        // SQL-query om de gegevens in te voegen in de database
        $query = "INSERT INTO feedback (voornaam, achternaam, geslacht, email, telefoon, adres, leeftijd, bericht) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Bereid de query voor
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssis", $voornaam, $achternaam, $geslacht, $email, $telefoon, $adres, $leeftijd, $bericht);

        // Voer de query uit en controleer op succes
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Fout: " . $stmt->error;
        }
        $stmt->close(); // Sluit de statement

    } catch (Exception $e) {
        $error = "Fout bij het uitvoeren van de query: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Tilburgse Wandeling</title>
    <link rel="stylesheet" href="feedback.css">
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo-nav">
                <div class="logo">
                    <img src="img/28739.jpg(mediaclass-avatar.4ad399c335d937a0eb393149e4c9195d11275441).jpg" alt="Logo">
                    <span>Tilburgse Wandeling</span>
                </div>
                <nav>
                    <ul>
                        <li><a href="tilburg.html">Home</a></li>
                        <li><a href="activiteiten.html">Activiteiten</a></li>
                        <li><a href="feedback.html">Feedback</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <h1>Geef je Feedback</h1>
        <div class="container">
            <?php
            if ($success) {
                echo '<p class="message">Feedback succesvol verzonden!</p>';
                echo '<a href="feedback_overzicht.php"><button>Bekijk Feedback</button></a>';
            } else {
                if ($error) {
                    echo '<p class="message">' . $error . '</p>';
                }
                ?>
                <form action="verzend_feedback.php" method="post">
                    <div class="form-group">
                        <label for="naam">Voornaam:</label>
                        <input type="text" id="naam" name="naam" required>
                    </div>
                    <div class="form-group">
                        <label for="achternaam">Achternaam:</label>
                        <input type="text" id="achternaam" name="achternaam" required>
                    </div>
                    <div class="form-group">
                        <label>Geslacht:</label>
                        <label for="man">
                            <input type="radio" id="man" name="geslacht" value="man" required>
                            Man
                        </label>
                        <label for="vrouw">
                            <input type="radio" id="vrouw" name="geslacht" value="vrouw" required>
                            Vrouw
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="telefoon">Telefoonnummer:</label>
                        <input type="tel" id="telefoon" name="telefoon" required>
                    </div>
                    <div class="form-group">
                        <label for="adres">Adres:</label>
                        <input type="text" id="adres" name="adres" required>
                    </div>
                    <div class="form-group">
                        <label for="leeftijd">Leeftijd:</label>
                        <input type="number" id="leeftijd" name="leeftijd" required>
                    </div>
                    <div class="form-group">
                        <label for="bericht">Bericht:</label>
                        <textarea id="bericht" name="bericht" rows="4" required></textarea>
                    </div>
                    <button type="submit">Verzend</button>
                </form>
                <?php
            }
            ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Tilburgse Wandeling. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
