<?php
session_start();
include "../php/connessione.php";
$connessione = new mysqli($hostname, $username, $password, "ecommerce");
if ($connessione->connect_error) {
  die("Connessione fallita: " . $connessione->connect_error);
}

if (isset($_SESSION['user_id']) && isset($_POST['id_prodotto'])) {
    $idProdotto = $_POST['id_prodotto'];
    $idCliente = $_SESSION['user_id']; 
    $sql = "SELECT ID FROM carrello WHERE ID_cliente = ? LIMIT 1";
    $stmt = $connessione->prepare($sql);
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $carrello = $result->fetch_assoc();
        $idCarrello = $carrello['ID'];
    } else {
        $sqlCarrello = "INSERT INTO carrello (ID_cliente) VALUES (?)";
        $stmtCarrello = $connessione->prepare($sqlCarrello);
        $stmtCarrello->bind_param("i", $idCliente);
        $stmtCarrello->execute();
        $idCarrello = $stmtCarrello->insert_id;
    }

    $sqlProdottiCarrello = "INSERT INTO prodotti_carrello (ID_carrello, ID_prodotto) VALUES (?, ?)";
    $stmtProdottiCarrello = $connessione->prepare($sqlProdottiCarrello);
    $stmtProdottiCarrello->bind_param("ii", $idCarrello, $idProdotto);
    $stmtProdottiCarrello->execute();

    if (isset($_POST['accessori'])) {
        foreach ($_POST['accessori'] as $idAccessorio) {
            $sqlAccessoriCarrello = "INSERT INTO prodotti_carrello (ID_carrello, ID_prodotto, ID_accessorio) VALUES (?, ?, ?)";
            $stmtAccessoriCarrello = $connessione->prepare($sqlAccessoriCarrello);
            if ($stmtAccessoriCarrello === false) {
                die("Errore nella preparazione della query per aggiungere l'accessorio: " . $connessione->error);
            }
            
            $stmtAccessoriCarrello->bind_param("iii", $idCarrello, $idProdotto, $idAccessorio);
            $stmtAccessoriCarrello->execute();
        }
    }
    

    echo "Prodotto e accessori aggiunti al carrello.";
} else {
    header('Location: ../view/login.php');
    exit();
}

$connessione->close();
?>
