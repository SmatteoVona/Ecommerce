<?php
include "../php/connessione.php";
$connessione = new mysqli($hostname, $username, $password, "ecommerce");

$nome = $connessione->real_escape_string($_POST['nome']);
$cognome = $connessione->real_escape_string($_POST['cognome']);
$email = $connessione->real_escape_string($_POST['email']);
$password = $connessione->real_escape_string($_POST['password']); // Considera l'uso di password_hash() per una maggiore sicurezza

$sql = "SELECT * FROM cliente WHERE mail = '$email'";
$result = $connessione->query($sql);
if ($result->num_rows > 0) {
    echo "Email già utilizzata. Per favore, utilizza un'altra email.";
    header("Location: registrazione.php?errore=email_usata");
    exit;
} else {
    $sql = "INSERT INTO cliente (nome, cognome, mail, password) VALUES ('$nome', '$cognome', '$email', '$password')";
    if ($connessione->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Errore durante la registrazione: " . $connessione->error;
    }
}

$connessione->close();
?>
