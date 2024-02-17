<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
  <script src="assets/js/color-modes.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dettagli Prodotto · Bootstrap v5.3</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
  <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/product.css" rel="stylesheet">
</head>
<body>

<main>
  <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
    <?php
    include "connessione.php";
    $connessione = new mysqli($hostname, $username, $password, "ecommerce");
    if ($connessione->connect_error) {
      die("Connessione fallita: " . $connessione->connect_error);
    }
    
    $id_prodotto = isset($_GET['id']) ? $_GET['id'] : die('ID Prodotto non specificato.');
    $sql = "SELECT prodotto.ID, prodotto.nome AS nome_prodotto, prodotto.descrizione, prodotto.prezzo, categoria.nome AS nome_categoria, prodotto.percorso_immagine FROM prodotto INNER JOIN categoria ON prodotto.ID_categoria = categoria.ID WHERE prodotto.ID = ?";
    
    if ($stmt = $connessione->prepare($sql)) {
        $stmt->bind_param("i", $id_prodotto);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            echo '<div class="col-md-6 p-lg-5 mx-auto my-5">';
            echo '<img src="'.$row["percorso_immagine"].'" class="img-fluid" alt="Immagine Prodotto">'; // Mostra l'immagine del prodotto
            echo '<h1 class="display-4 fw-bold">'.$row["nome_prodotto"].'</h1>';
            echo '<p class="lead">'.$row["descrizione"].'</p>';
            echo '<p><strong>Categoria:</strong> '.$row["nome_categoria"].'</p>';
            echo '<p><strong>Prezzo:</strong> €'.$row["prezzo"].'</p>';
            echo '<a href="acquisto.php?id='.$row["ID"].'" class="btn btn-primary">Acquista ora</a>'; // Link per fare l'acquisto
            echo '</div>';
        } else {
            echo "Prodotto non trovato.";
        }
        $stmt->close();
    } else {
        echo "Errore nella preparazione della query.";
    }
    $connessione->close();
    ?>
  </div>
</main>

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
