<?php
session_start();
include "connessione.php"; // Assicurati che questo file contenga i dati per connettersi al DB

if (!isset($_SESSION['user_id'])) {
  echo "Utente non autenticato.";
  exit; // Ferma l'esecuzione dello script se l'utente non è autenticato
}

$connessione = new mysqli($hostname, $username, $password, "ecommerce");
if ($connessione->connect_error) {
  die("Connessione fallita: " . $connessione->connect_error);
}

$idCliente = $_SESSION['user_id'];

// Inizia una transazione
$connessione->begin_transaction();

try {
  // Recupera l'ID del carrello associato all'utente
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

  // Inserisci un nuovo ordine
  $indirizzo = 'Indirizzo di esempio'; // Sostituire con l'indirizzo reale recuperato dal form
  $stato = 'In attesa';
  $sqlOrdine = "INSERT INTO ordine (indirizzo, stato, ID_carrello) VALUES (?, ?, ?)";
  $stmtOrdine = $connessione->prepare($sqlOrdine);
  $stmtOrdine->bind_param("ssi", $indirizzo, $stato, $idCarrello);
  $stmtOrdine->execute();
  $idOrdine = $connessione->insert_id;

  // Sposta gli articoli da prodotti_carrello a dettaglio_ordine
  $sqlDettaglioOrdine = "INSERT INTO dettaglio_ordine (ID_ordine, ID_prodotto, ID_accessorio) SELECT ?, ID_prodotto, ID_accessorio FROM prodotti_carrello WHERE ID_carrello = ?";
  $stmtDettaglioOrdine = $connessione->prepare($sqlDettaglioOrdine);
  $stmtDettaglioOrdine->bind_param("ii", $idOrdine, $idCarrello);
  $stmtDettaglioOrdine->execute();

  // Svuota il carrello dopo aver trasferito gli articoli
  $sqlSvuotaCarrello = "DELETE FROM prodotti_carrello WHERE ID_carrello = ?";
  $stmtSvuotaCarrello = $connessione->prepare($sqlSvuotaCarrello);
  $stmtSvuotaCarrello->bind_param("i", $idCarrello);
  $stmtSvuotaCarrello->execute();

  $connessione->commit(); // Conferma la transazione
  echo "Checkout completato con successo.";
} catch (Exception $e) {
  $connessione->rollback(); // Annulla la transazione in caso di errore
  echo "Errore durante il checkout: " . $e->getMessage();
}

$connessione->close();
?>
