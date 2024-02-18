<?php
session_start(); // Avvia la sessione all'inizio dello script

include "connessione.php";
$connessione = new mysqli($hostname, $username, $password, "ecommerce");
if ($connessione->connect_error) {
    die("Connessione fallita: " . $connessione->connect_error);
}

// Verifica se email e password sono impostate
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Procedi con la verifica dell'utente nel database
    $sql = "SELECT ID, password FROM cliente WHERE mail = ?";
    if ($stmt = $connessione->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Confronto diretto delle password (non sicuro)
            if ($password === $user['password']) {
                // Imposta l'ID dell'utente nella sessione
                $_SESSION['user_id'] = $user['ID'];
                
                // Reindirizza alla pagina desiderata dopo il login
                header("Location: lista_prodotti.php");
                exit;
            } else {
                // Gestisci il caso di password errata
                echo "Password errata.";
            }
        } else {
            // Gestisci il caso di utente non trovato
            echo "Utente non trovato.";
        }
    } else {
        // Gestisci eventuali errori nella preparazione della query
        echo "Errore nella preparazione della query.";
    }
    $connessione->close();
} else {
    // Messaggio di errore se email o password non sono impostate
    echo "Per favore inserisci email e password.";
}
?>
