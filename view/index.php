<!doctype html>
<html lang="ita" data-bs-theme="auto">

<head>
  <script src="../assets/js/color-modes.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prodotto · Bootstrap v5.3</title>
  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
  <!--<link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">-->
  <link href="../css/index.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
  <?php
  include "connessione.php";
  $connessione = new mysqli($hostname, $username, $password, "ecommerce");
  if ($connessione->connect_error) {
    die("Connessione fallita: " . $connessione->connect_error);
  }
  ?>

  <main>
    <header class="p-3 text-bg-dark">
      <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
          <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
            <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
              <use xlink:href="#bootstrap" />
            </svg>
          </a>

          <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><a href="#" class="nav-link px-2 text-secondary">Home</a></li>
            <li><a href="#" class="nav-link px-2 text-white">Features</a></li>
            <li><a href="#" class="nav-link px-2 text-white">Pricing</a></li>
            <li><a href="#" class="nav-link px-2 text-white">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 text-white">About</a></li>
          </ul>

          <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
            <input type="search" class="form-control form-control-dark text-bg-dark" placeholder="Search..."
              aria-label="Search">
          </form>

          <?php
  // Avvia la sessione all'inizio del file PHP
  session_start();

  // Controlla se l'utente è già autenticato
  if (isset($_SESSION['user_id'])) {
      // Mostra il pulsante di logout
      echo '<a href="logout.php" class="btn btn-outline-danger me-2">Logout</a>';
  } else {
      // Mostra i pulsanti di login e sign-up
      echo '<a href="login.php" class="btn btn-outline-light me-2">Login</a>';
      echo '<a href="registrazione.php" class="btn btn-warning">Sign-up</a>';
  }
  ?>
        </div>
      </div>
      <section class="py-5 text-center container">
        <div class="row py-lg-5">
          <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">Catalogo Prodotti</h1>
            <p class="lead text-muted">Scopri la nostra vasta gamma di prodotti, selezionati per te.</p>
          </div>
        </div>
      </section>

      <div class="py-5 bg-light">
        <div class="container">
          <div class="row row-cols-3 g-3">
            <?php
            include "connessione.php";
            $connessione = new mysqli($hostname, $username, $password, "ecommerce");
            if ($connessione->connect_error) {
              die("Connessione fallita: " . $connessione->connect_error);
            }

            // Assumendo che tu voglia visualizzare tutti i prodotti, rimuoveremo il WHERE clause.
// Se vuoi filtrare per un ID specifico, dovrai ottenere quell'ID da qualche parte, ad esempio da $_GET o $_POST
            $sql = "SELECT prodotto.ID, prodotto.nome AS nome_prodotto, prodotto.descrizione, prodotto.prezzo, categoria.nome AS nome_categoria FROM prodotto INNER JOIN categoria ON prodotto.ID_categoria = categoria.ID";

            // Utilizzo del metodo prepare() per preparare la query
            $stmt = $connessione->prepare($sql);

            // Se la tua intenzione era di filtrare per un ID specifico, qui andrebbe associato il valore dell'ID:
// $stmt->bind_param("i", $id_prodotto); // Dove $id_prodotto contiene l'ID del prodotto che vuoi filtrare.
            
            // Esecuzione della query preparata
            $stmt->execute();

            // Ottenimento del risultato
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo '<div class="col">';
                echo '<div class="card">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row["nome_prodotto"] . '</h5>';
                echo '<p class="card-text">' . $row["descrizione"] . '</p>';
                echo '<p class="card-text"><small class="text-muted">Categoria: ' . $row["nome_categoria"] . '</small></p>';
                echo '<div class="d-flex justify-content-between align-items-center">';
                echo '<div class="btn-group">';
                echo '<a href="dettaglio_prodotto.php?id=' . $row["ID"] . '" class="btn btn-sm btn-outline-secondary">View</a>';
                echo '</div>';
                echo '<small class="text-muted">€ ' . $row["prezzo"] . '</small>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
              }
            } else {
              echo "Nessun prodotto trovato.";
            }
            $connessione->close();
            ?>
          </div>
        </div>
      </div>
  </main>

  <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>