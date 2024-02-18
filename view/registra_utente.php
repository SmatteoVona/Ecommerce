<?php
include "connessione.php";
$connessione = new mysqli($hostname, $username, $password, "ecommerce");

// Sanifica i dati inseriti dall'utente
$nome = $connessione->real_escape_string($_POST['nome']);
$cognome = $connessione->real_escape_string($_POST['cognome']);
$email = $connessione->real_escape_string($_POST['email']);
$password = $connessione->real_escape_string($_POST['password']); // Considera l'uso di password_hash() per una maggiore sicurezza

// Verifica se l'email è già stata utilizzata
$sql = "SELECT * FROM cliente WHERE mail = '$email'";
$result = $connessione->query($sql);
if ($result->num_rows > 0) {
    echo "Email già utilizzata. Per favore, utilizza un'altra email.";
    // Reindirizzamento alla pagina di registrazione con messaggio di errore
    // Puoi usare una query string per passare un messaggio o un codice di errore se necessario
    header("Location: registrazione.php?errore=email_usata");
    exit;
} else {
    // Inserisci il nuovo utente nel database
    $sql = "INSERT INTO cliente (nome, cognome, mail, password) VALUES ('$nome', '$cognome', '$email', '$password')";
    if ($connessione->query($sql) === TRUE) {
        // Reindirizzamento alla pagina di login dopo la registrazione avvenuta con successo
        header("Location: index.php");
        exit;
    } else {
        echo "Errore durante la registrazione: " . $connessione->error;
        // Opzionalmente, potresti voler reindirizzare all'utente ad una pagina di errore o di nuovo alla pagina di registrazione
    }
}

$connessione->close();
?>
