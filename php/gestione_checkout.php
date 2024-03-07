<?php
session_start();
include "connessione.php";

if (!isset($_SESSION['user_id'])) {
  echo "Utente non autenticato.";
  exit;
}

$connessione = new mysqli($hostname, $username, $password, "ecommerce");
if ($connessione->connect_error) {
  die("Connessione fallita: " . $connessione->connect_error);
}

$idCliente = $_SESSION['user_id'];
//begin_transaction serve ad aumentare la sicurezza siccome permette di fare il commit solo dopo aver superato dei controlli specifici
$connessione->begin_transaction();

try {
 
  $sqlCarrello = "SELECT ID FROM carrello WHERE ID_cliente = ? LIMIT 1";
  $stmtCarrello = $connessione->prepare($sqlCarrello);
  $stmtCarrello->bind_param("i", $idCliente);
  $stmtCarrello->execute();
  $resultCarrello = $stmtCarrello->get_result();
  if ($resultCarrello->num_rows === 0) {
    throw new Exception("Carrello non trovato.");
  }
  $carrello = $resultCarrello->fetch_assoc();
  $idCarrello = $carrello['ID'];

  $indirizzo = 'Indirizzo di esempio';
  $stato = 'In attesa';
  $sqlOrdine = "INSERT INTO ordine (indirizzo, stato, ID_carrello) VALUES (?, ?, ?)";
  $stmtOrdine = $connessione->prepare($sqlOrdine);
  $stmtOrdine->bind_param("ssi", $indirizzo, $stato, $idCarrello);
  $stmtOrdine->execute();
  $idOrdine = $connessione->insert_id;

  $sqlDettaglioOrdine = "INSERT INTO dettaglio_ordine (ID_ordine, ID_prodotto, ID_accessorio) SELECT ?, ID_prodotto, ID_accessorio FROM prodotti_carrello WHERE ID_carrello = ?";
  $stmtDettaglioOrdine = $connessione->prepare($sqlDettaglioOrdine);
  $stmtDettaglioOrdine->bind_param("ii", $idOrdine, $idCarrello);
  $stmtDettaglioOrdine->execute();

  $sqlSvuotaCarrello = "DELETE FROM prodotti_carrello WHERE ID_carrello = ?";
  $stmtSvuotaCarrello = $connessione->prepare($sqlSvuotaCarrello);
  $stmtSvuotaCarrello->bind_param("i", $idCarrello);
  $stmtSvuotaCarrello->execute();

  //invio il commit del transiction
  $connessione->commit(); 
  echo "Checkout completato con successo.";
} catch (Exception $e) {
  //rollback permette di annullare tutte le modifiche effettuate al db della transaction
  $connessione->rollback();
  echo "Errore durante il checkout: " . $e->getMessage();
}

$connessione->close();
?>
