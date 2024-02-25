
<!doctype html>
<html lang="it" data-bs-theme="auto">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dettaglio prodotto</title>
  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/product/">

  <link href="../css/product.css" rel="stylesheet">

  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="../assets/js/color-modes.js"></script>

</head>

<body>

  <header class="p-3 text-bg-dark">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
          <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
            <use xlink:href="#bootstrap" />
          </svg>
        </a>

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
     
    
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
      <symbol id="aperture" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
        stroke-width="2" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" />
        <path
          d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94" />
      </symbol>
      <symbol id="cart" viewBox="0 0 16 16">
        <path
          d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
      </symbol>
      <symbol id="chevron-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd"
          d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
      </symbol>
    </svg>


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
    </footer>
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>