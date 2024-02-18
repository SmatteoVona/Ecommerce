<?php
session_start();
include "connessione.php";
$connessione = new mysqli($hostname, $username, $password, "ecommerce");
if ($connessione->connect_error) {
  die("Connessione fallita: " . $connessione->connect_error);
}

// Verifica se l'utente è loggato e se è stato inviato un ID prodotto
if (isset($_SESSION['user_id']) && isset($_POST['id_prodotto'])) {
    $idProdotto = $_POST['id_prodotto'];
    $idCliente = $_SESSION['user_id']; // Usa l'ID dell'utente dalla sessione

    // Controlla se l'utente ha già un carrello
    $sql = "SELECT ID FROM carrello WHERE ID_cliente = ? LIMIT 1";
    $stmt = $connessione->prepare($sql);
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Usa l'ID carrello esistente
        $carrello = $result->fetch_assoc();
        $idCarrello = $carrello['ID'];
    } else {
        // Crea un nuovo carrello per l'utente
        $sqlCarrello = "INSERT INTO carrello (ID_cliente) VALUES (?)";
        $stmtCarrello = $connessione->prepare($sqlCarrello);
        $stmtCarrello->bind_param("i", $idCliente);
        $stmtCarrello->execute();
        $idCarrello = $stmtCarrello->insert_id;
    }

    // Aggiungi il prodotto al carrello
    $sqlProdottiCarrello = "INSERT INTO prodotti_carrello (ID_carrello, ID_prodotto, quantita) VALUES (?, ?, 1)";
    $stmtProdottiCarrello = $connessione->prepare($sqlProdottiCarrello);
    $stmtProdottiCarrello->bind_param("ii", $idCarrello, $idProdotto);
    $stmtProdottiCarrello->execute();

    // Aggiungi gli accessori selezionati al carrello, se presenti
    if (isset($_POST['accessori'])) {
        foreach ($_POST['accessori'] as $idAccessorio) {
            // Assumendo che esista una logica per aggiungere accessori nel carrello
            // Potresti dover aggiungere una tabella relazionale tra carrello e accessori, o aggiornare la logica qui
        }
    }

    echo "Prodotto e accessori aggiunti al carrello.";
} else {
    echo "Utente non autenticato o nessun prodotto specificato.";
}

$connessione->close();
?>
