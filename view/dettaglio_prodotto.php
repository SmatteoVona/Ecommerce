<!doctype html>
<html lang="it" data-bs-theme="auto">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dettaglio prodotto</title>

  <link href="../css/product.css" rel="stylesheet">
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="../assets/js/color-modes.js"></script>

</head>

<body>

  <header class="p-3 text-bg-dark">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="index.php" class="nav-link px-2 text-white">Home</a></li>
        </ul>

        <?php
        session_start();
        if (isset($_SESSION['user_id'])) {
          echo '<a href="logout.php" class="btn btn-outline-danger me-2">Logout</a>';
          echo '<a href="checkout.php" class="btn btn-outline-light me-2">Carrello</a>';

        } else {
          echo '<a href="login.php" class="btn btn-outline-light me-2">Login</a>';
          echo '<a href="registrazione.php" class="btn btn-warning">Sign-up</a>';
        }
        ?>

      </div>
      <main>
        <div class="container py-5">
          <?php
          include "../php/connessione.php";
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
              echo '<div class="text-center">';
              echo '<h1 class="display-4 fw-bold">' . $row["nome_prodotto"] . '</h1>';
              echo '<p class="lead">' . $row["descrizione"] . '</p>';
              echo '<p><strong>Categoria:</strong> ' . $row["nome_categoria"] . '</p>';
              echo '<p><strong>Prezzo:</strong> €' . $row["prezzo"] . '</p>';
              echo '<img src="../' . $row["percorso_immagine"] . '" class="img-fluid" alt="Immagine Prodotto" style="max-width: 50%; height: auto;">';

              echo '</div>';
            } else {
              echo "Prodotto non trovato.";
            }

            echo '<form action="../php/aggiungi_al_carrello.php" method="POST" class="text-center">';
            echo '<h3>Seleziona accessori aggiuntivi:</h3>';

            echo '<input type="hidden" name="id_prodotto" value="' . $id_prodotto . '">';

            $sqlAccessori = "SELECT ID, nome, prezzo FROM accessorio";
            $resultAccessori = $connessione->query($sqlAccessori);

            if ($resultAccessori->num_rows > 0) {
              while ($rowAccessori = $resultAccessori->fetch_assoc()) {
                echo '<div class="form-check">';
                echo '<input class="form-check-input" type="checkbox" name="accessori[]" value="' . $rowAccessori["ID"] . '">';
                echo '<label class="form-check-label">' . $rowAccessori["nome"] . ' - €' . $rowAccessori["prezzo"] . '</label>';
                echo '</div>';
              }
            } else {
              echo "Nessun accessorio disponibile.";
            }

            echo '<button type="submit" class="btn btn-primary mt-3">Aggiungi al carrello</button>';
            echo '</form>';

            $stmt->close();
          } else {
            echo "Errore nella preparazione della query.";
          }
          $connessione->close();
          ?>
        </div>
      </main>

    </div>

</body>

</html>