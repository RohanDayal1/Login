<?php
// auteur: RD
// Functie om verbinding te maken met de database
function ConnectDb()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Stel de PDO-foutmodus in op exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Verbonden met de database";
        return $conn;
    } catch (PDOException $e) {
        echo "Verbinding mislukt: " . $e->getMessage();
        return null;
    }
}

// Gegevens ophalen vanuit het formulier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Controleren of het wachtwoord overeenkomt met de bevestiging
    if ($password != $confirm_password) {
        echo "Wachtwoord en bevestiging komen niet overeen";
        exit;
    }

    // Maak verbinding met de database
    $conn = ConnectDb();

    if ($conn) {
        try {
            // Bereid de SQL-query voor
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            // Voer de query uit
            $stmt->execute();

            echo "Registratie succesvol";
        } catch (PDOException $e) {
            echo "Fout bij het registreren: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registratie pagina</title>
</head>

<body>
    <h2>Registratie</h2>
    <form action="" method="POST">
        <label for="username">Gebruikersnaam:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Wachtwoord:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="confirm_password">Bevestig wachtwoord:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <input type="submit" value="Registreren">
    </form>
</body>

</html>

